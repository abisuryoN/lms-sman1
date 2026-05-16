<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    private string $baseUrl;
    private string $serviceRoleKey;
    private ?string $defaultBucket;

    public function __construct(?string $defaultBucket = null)
    {
        $this->baseUrl = rtrim(config('services.supabase.url'), '/');
        $this->serviceRoleKey = config('services.supabase.service_role_key');
        $this->defaultBucket = $defaultBucket ?? config('services.supabase.bucket');
    }

    /**
     * Upload file ke Supabase Storage private bucket.
     */
    public function upload(string $localFilePath, string $storagePath, string $mimeType = 'application/octet-stream', ?string $bucket = null): bool
    {
        try {
            $bucket = $bucket ?? $this->defaultBucket;
            $fileContent = file_get_contents($localFilePath);
            if ($fileContent === false) {
                Log::error("SupabaseStorage: Gagal membaca file lokal: {$localFilePath}");
                return false;
            }

            $url = "{$this->baseUrl}/storage/v1/object/{$bucket}/{$storagePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => $mimeType,
                'x-upsert' => 'true',
            ])->withBody($fileContent, $mimeType)->post($url);

            if ($response->successful()) {
                Log::info("SupabaseStorage: Upload berhasil — [{$bucket}] {$storagePath}");
                return true;
            }

            Log::error("SupabaseStorage: Upload gagal — [{$bucket}] {$storagePath}", [
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
     */
    public function getSignedUrl(string $storagePath, ?int $expiresIn = null, ?string $downloadFilename = null, ?string $bucket = null): ?string
    {
        try {
            $bucket = $bucket ?? $this->defaultBucket;
            $expiresIn = $expiresIn ?? config('services.supabase.signed_url_expires', 600);

            $url = "{$this->baseUrl}/storage/v1/object/sign/{$bucket}/{$storagePath}";

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
                    $fullUrl = "{$this->baseUrl}/storage/v1{$signedUrl}";
                    if ($downloadFilename) {
                        $separator = str_contains($fullUrl, '?') ? '&' : '?';
                        $fullUrl .= $separator . 'download=' . urlencode($downloadFilename);
                    }
                    return $fullUrl;
                }
            }

            Log::error("SupabaseStorage: Gagal generate signed URL — [{$bucket}] {$storagePath}", [
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
     * Hapus file dari Supabase Storage.
     * Mengembalikan true jika berhasil atau file memang tidak ada.
     */
    public function delete(string $storagePath, ?string $bucket = null): bool
    {
        try {
            $bucket = $bucket ?? $this->defaultBucket;
            $url = "{$this->baseUrl}/storage/v1/object/{$bucket}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => 'application/json',
            ])->delete($url, [
                'prefixes' => [$storagePath],
            ]);

            if ($response->successful()) {
                Log::info("SupabaseStorage: Delete berhasil atau file tidak ada — [{$bucket}] {$storagePath}");
                return true;
            }

            // Jika error 404 pada bucket, itu masalah konfigurasi
            if ($response->status() === 404) {
                Log::error("SupabaseStorage: Bucket tidak ditemukan — [{$bucket}]");
                return false;
            }

            Log::error("SupabaseStorage: Delete gagal — [{$bucket}] {$storagePath}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat delete — {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Hapus banyak file sekaligus.
     * Supabase API menerima array of prefixes.
     */
    public function deleteMultiple(array $storagePaths, ?string $bucket = null): array
    {
        if (empty($storagePaths)) {
            return ['success' => true, 'count' => 0];
        }

        try {
            $bucket = $bucket ?? $this->defaultBucket;
            $url = "{$this->baseUrl}/storage/v1/object/{$bucket}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
                'Content-Type' => 'application/json',
            ])->delete($url, [
                'prefixes' => $storagePaths,
            ]);

            if ($response->successful()) {
                $deleted = $response->json();
                Log::info("SupabaseStorage: Bulk delete berhasil — [{$bucket}] " . count($storagePaths) . " files requested.");
                return [
                    'success' => true,
                    'count' => count($deleted),
                    'requested' => count($storagePaths)
                ];
            }

            Log::error("SupabaseStorage: Bulk delete gagal — [{$bucket}]", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return ['success' => false, 'count' => 0];
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat bulk delete — {$e->getMessage()}");
            return ['success' => false, 'count' => 0];
        }
    }

    /**
     * Download file dari Supabase Storage ke path lokal sementara.
     */
    public function download(string $storagePath, string $localDestination, ?string $bucket = null): bool
    {
        try {
            $bucket = $bucket ?? $this->defaultBucket;
            $url = "{$this->baseUrl}/storage/v1/object/{$bucket}/{$storagePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->serviceRoleKey}",
            ])->get($url);

            if ($response->successful()) {
                $dir = dirname($localDestination);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                file_put_contents($localDestination, $response->body());
                Log::info("SupabaseStorage: Download berhasil — [{$bucket}] {$storagePath}");
                return true;
            }

            Log::error("SupabaseStorage: Download gagal — [{$bucket}] {$storagePath}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("SupabaseStorage: Exception saat download — {$e->getMessage()}");
            return false;
        }
    }
}
