<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    private string $baseUrl;
    private string $serviceRoleKey;
    private string $bucket;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.supabase.url'), '/');
        $this->serviceRoleKey = config('services.supabase.service_role_key');
        $this->bucket = config('services.supabase.bucket');
    }

    /**
     * Upload file ke Supabase Storage private bucket.
     *
     * @param string $localFilePath Path file lokal di server
     * @param string $storagePath Path tujuan di bucket (contoh: "kelas-x/tugas-1/siswa-1.pdf")
     * @param string $mimeType MIME type file
     * @return bool
     */
    public function upload(string $localFilePath, string $storagePath, string $mimeType = 'application/octet-stream'): bool
    {
        try {
            $fileContent = file_get_contents($localFilePath);
            if ($fileContent === false) {
                Log::error("SupabaseStorage: Gagal membaca file lokal: {$localFilePath}");
                return false;
            }

            $url = "{$this->baseUrl}/storage/v1/object/{$this->bucket}/{$storagePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => $mimeType,
                'x-upsert' => 'true',
            ])->withBody($fileContent, $mimeType)->post($url);

            if ($response->successful()) {
                Log::info("SupabaseStorage: Upload berhasil — {$storagePath}");
                return true;
            }

            Log::error("SupabaseStorage: Upload gagal — {$storagePath}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat upload — {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Generate signed URL untuk akses file private.
     * Default expired 10 menit (600 detik).
     *
     * @param string $storagePath Path file di bucket
     * @param int $expiresIn Durasi dalam detik
     * @return string|null
     */
    public function getSignedUrl(string $storagePath, ?int $expiresIn = null, ?string $downloadFilename = null): ?string
    {
        try {
            $expiresIn = $expiresIn ?? config('services.supabase.signed_url_expires', 600);

            $url = "{$this->baseUrl}/storage/v1/object/sign/{$this->bucket}/{$storagePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => 'application/json',
            ])->post($url, [
                'expiresIn' => $expiresIn,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $signedUrl = $data['signedURL'] ?? null;
                if ($signedUrl) {
                    // Supabase mengembalikan path relatif, perlu ditambah base URL
                    $fullUrl = "{$this->baseUrl}/storage/v1{$signedUrl}";
                    if ($downloadFilename) {
                        $separator = str_contains($fullUrl, '?') ? '&' : '?';
                        $fullUrl .= $separator . 'download=' . urlencode($downloadFilename);
                    }
                    return $fullUrl;
                }
            }

            Log::error("SupabaseStorage: Gagal generate signed URL — {$storagePath}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat generate signed URL — {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Download file dari Supabase Storage ke path lokal sementara.
     *
     * @param string $storagePath Path file di bucket
     * @param string $localDestination Path lokal tujuan download
     * @return bool
     */
    public function download(string $storagePath, string $localDestination): bool
    {
        try {
            $url = "{$this->baseUrl}/storage/v1/object/{$this->bucket}/{$storagePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
            ])->get($url);

            if ($response->successful()) {
                $dir = dirname($localDestination);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                file_put_contents($localDestination, $response->body());
                Log::info("SupabaseStorage: Download berhasil — {$storagePath}");
                return true;
            }

            Log::error("SupabaseStorage: Download gagal — {$storagePath}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat download — {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Hapus file dari Supabase Storage.
     *
     * @param string $storagePath Path file di bucket
     * @return bool
     */
    public function delete(string $storagePath): bool
    {
        try {
            $url = "{$this->baseUrl}/storage/v1/object/{$this->bucket}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => 'application/json',
            ])->delete($url, [
                'prefixes' => [$storagePath],
            ]);

            if ($response->successful()) {
                Log::info("SupabaseStorage: Delete berhasil — {$storagePath}");
                return true;
            }

            Log::error("SupabaseStorage: Delete gagal — {$storagePath}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat delete — {$e->getMessage()}");
            return false;
        }
    }
}
