<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Imagick;

class OcrService
{
    /**
     * Router utama: ekstrak teks berdasarkan MIME type file.
     *
     * @param string $localPath Path file lokal sementara
     * @param string $mimeType MIME type file
     * @return string Teks yang diekstrak
     * @throws \Exception Jika gagal mengekstrak
     */
    public function extractText(string $localPath, string $mimeType): string
    {
        return match (true) {
            str_contains($mimeType, 'pdf') => $this->extractFromPdf($localPath),
            in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png']) => $this->extractFromImage($localPath),
            str_contains($mimeType, 'wordprocessingml') || str_contains($mimeType, 'msword') => $this->extractFromDocx($localPath),
            default => throw new \Exception("Tipe file tidak didukung untuk ekstraksi: {$mimeType}"),
        };
    }

    /**
     * Ekstrak teks dari PDF — coba digital dulu, kalau kosong fallback ke OCR.
     */
    private function extractFromPdf(string $path): string
    {
        // Coba ekstrak sebagai PDF digital
        $text = $this->extractFromDigitalPdf($path);

        // Jika teks terlalu sedikit, mungkin ini PDF scan — coba OCR
        if (strlen(trim($text)) < 50) {
            Log::info("OcrService: PDF teks sedikit, mencoba OCR...");
            $ocrText = $this->extractFromScannedPdf($path);
            if (strlen(trim($ocrText)) > strlen(trim($text))) {
                return $ocrText;
            }
        }

        return $text;
    }

    /**
     * Ekstrak teks dari PDF digital menggunakan smalot/pdfparser.
     */
    private function extractFromDigitalPdf(string $path): string
    {
        if (!class_exists('\Smalot\PdfParser\Parser')) {
            Log::warning("OcrService: smalot/pdfparser tidak tersedia.");
            return '';
        }

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($path);
            return $pdf->getText();
        } catch (\Exception $e) {
            Log::error("OcrService: Gagal parsing PDF digital — {$e->getMessage()}");
            return '';
        }
    }

    /**
     * Ekstrak teks dari file gambar menggunakan Tesseract OCR.
     * Fallback graceful jika Tesseract tidak terinstal.
     */
    private function extractFromImage(string $path): string
    {
        $tesseractPath = config('services.tesseract.path', 'tesseract');

        // Cek apakah Tesseract tersedia
        if (!$this->isTesseractAvailable($tesseractPath)) {
            throw new \Exception("Tesseract OCR belum terinstal atau path tidak ditemukan: {$tesseractPath}");
        }

        try {
            // Cek apakah library tersedia
            if (class_exists('\thiagoalessio\TesseractOCR\TesseractOCR')) {
                $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR($path);
                $ocr->executable($tesseractPath);
                $ocr->lang('ind', 'eng'); // Bahasa Indonesia + English
                return $ocr->run();
            }

            // Fallback: panggil Tesseract langsung via command line
            return $this->runTesseractCli($path, $tesseractPath);
        } catch (\Exception $e) {
            Log::error("OcrService: OCR gagal untuk gambar — {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Ekstrak teks dari PDF scan: convert halaman ke gambayr lalu OCR.
     */
    private function extractFromScannedPdf(string $path): string
    {
        $tesseractPath = config('services.tesseract.path', 'tesseract');

        if (!$this->isTesseractAvailable($tesseractPath)) {
            Log::warning("OcrService: Tesseract tidak tersedia untuk OCR PDF scan.");
            return '';
        }

        try {
            // Gunakan Imagick jika tersedia untuk convert PDF -> image
            if (extension_loaded('imagick')) {
                return $this->ocrPdfWithImagick($path, $tesseractPath);
            }

            // Fallback: coba GD + pdfparser untuk ambil gambar embedded
            Log::warning("OcrService: Imagick tidak tersedia. Tidak bisa OCR PDF scan.");
            return '';
        } catch (\Exception $e) {
            Log::error("OcrService: Gagal OCR PDF scan — {$e->getMessage()}");
            return '';
        }
    }

    /**
     * OCR PDF menggunakan Imagick: convert pages to images, lalu OCR.
     */
    private function ocrPdfWithImagick(string $pdfPath, string $tesseractPath): string
    {
        $tempDir = sys_get_temp_dir() . '/lms_ocr_' . uniqid();
        mkdir($tempDir, 0755, true);

        $allText = '';
        try {
            /** @var \Imagick $imagick */
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($pdfPath);

            $pageCount = $imagick->getNumberImages();
            $maxPages = min($pageCount, 20); // Batasi 20 halaman

            for ($i = 0; $i < $maxPages; $i++) {
                $imagick->setIteratorIndex($i);
                $imagick->setImageFormat('png');
                $tempImage = "{$tempDir}/page_{$i}.png";
                $imagick->writeImage($tempImage);

                $pageText = $this->runTesseractCli($tempImage, $tesseractPath);
                $allText .= $pageText . "\n";

                // Hapus file temp halaman
                @unlink($tempImage);
            }

            $imagick->clear();
            $imagick->destroy();
        } catch (\Exception $e) {
            Log::error("OcrService: Imagick OCR error — {$e->getMessage()}");
        } finally {
            @rmdir($tempDir);
        }

        return $allText;
    }

    /**
     * Ekstrak teks dari file DOCX menggunakan ZipArchive.
     */
    private function extractFromDocx(string $path): string
    {
        if (!class_exists('\ZipArchive')) {
            throw new \Exception("PHP ZipArchive extension tidak aktif.");
        }

        $content = '';
        $zip = new \ZipArchive();
        if ($zip->open($path) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                // Docx menyimpan teks di tag <w:t>, strip_tags cukup untuk ambil teks mentah
                $content = strip_tags($data);
            } else {
                $zip->close();
            }
        }

        return $content;
    }

    /**
     * Jalankan Tesseract OCR via command line langsung.
     */
    private function runTesseractCli(string $imagePath, string $tesseractPath): string
    {
        $outputBase = sys_get_temp_dir() . '/lms_ocr_output_' . uniqid();
        $outputFile = $outputBase . '.txt';

        // Escape path untuk Windows
        $escapedTesseract = escapeshellarg($tesseractPath);
        $escapedImage = escapeshellarg($imagePath);
        $escapedOutput = escapeshellarg($outputBase);

        $command = "{$escapedTesseract} {$escapedImage} {$escapedOutput} -l ind+eng 2>&1";

        exec($command, $output, $returnCode);

        $text = '';
        if (file_exists($outputFile)) {
            $text = file_get_contents($outputFile);
            @unlink($outputFile);
        }

        if ($returnCode !== 0) {
            Log::warning("OcrService: Tesseract CLI return code {$returnCode}", ['output' => implode("\n", $output)]);
        }

        return $text ?: '';
    }

    /**
     * Cek apakah Tesseract binary tersedia.
     */
    private function isTesseractAvailable(string $path): bool
    {
        // Cek langsung apakah file executable ada
        if (file_exists($path)) {
            return true;
        }

        // Coba jalankan tesseract --version
        $escapedPath = escapeshellarg($path);
        exec("{$escapedPath} --version 2>&1", $output, $returnCode);

        return $returnCode === 0;
    }
}
