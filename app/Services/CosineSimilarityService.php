<?php

namespace App\Services;

use App\Models\JawabanTugas;
use App\Models\SimilarityResult;
use App\Models\Tugas;

class CosineSimilarityService
{
    /**
     * Daftar kata umum yang tidak terlalu berpengaruh saat membandingkan jawaban
     */
    private array $stopwords = [
        'dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'dengan', 'adalah',
        'ini', 'itu', 'atau', 'juga', 'sudah', 'saya', 'anda', 'kami', 'kita',
        'mereka', 'dia', 'akan', 'bisa', 'ada', 'tidak', 'bukan', 'hanya',
        'lebih', 'sangat', 'telah', 'sedang', 'masih', 'belum', 'harus',
        'dapat', 'oleh', 'karena', 'jika', 'maka', 'saat', 'ketika',
        'setelah', 'sebelum', 'antara', 'dalam', 'luar', 'atas', 'bawah',
        'seperti', 'sama', 'lain', 'semua', 'setiap', 'para', 'tersebut',
        'yaitu', 'bahwa', 'tentang', 'tanpa', 'melalui', 'secara', 'serta',
        'namun', 'tetapi', 'melainkan', 'selain', 'maupun', 'agar', 'supaya',
        'apabila', 'adapun', 'demikian', 'begitu', 'sehingga', 'meskipun',
        'walaupun', 'biarpun', 'sejak', 'sampai', 'hingga', 'terhadap',
        'mengenai', 'perihal', 'yakni', 'sebagai', 'merupakan',
        'the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
        'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could',
        'should', 'may', 'might', 'shall', 'can', 'of', 'in', 'to', 'for',
        'with', 'on', 'at', 'by', 'from', 'as', 'into', 'through', 'during',
        'before', 'after', 'above', 'below', 'between', 'under', 'again',
        'further', 'then', 'once', 'here', 'there', 'when', 'where', 'why',
        'how', 'all', 'each', 'every', 'both', 'few', 'more', 'most', 'other',
        'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so',
        'than', 'too', 'very', 'just', 'because', 'but', 'and', 'if', 'or',
        'while', 'about', 'against', 'it', 'this', 'that', 'these', 'those',
    ];

    /**
     * Tahap awal untuk merapikan teks jawaban
     * - ubah jadi huruf kecil
     * - hapus tanda baca
     * - pecah teks jadi kata-kata
     * - buang kata umum
     */
    public function preprocess(string $text): array
    {
        // Ubah semua huruf jadi kecil biar perbandingannya konsisten
        $text = mb_strtolower($text, 'UTF-8');

        // Hapus tanda baca dan karakter khusus, sisakan huruf, angka, dan spasi
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);

        // Rapikan spasi yang berlebihan
        $text = preg_replace('/\s+/', ' ', trim($text));

        // Pecah teks jadi kumpulan kata
        $tokens = explode(' ', $text);

        // Buang kata umum dan kata yang terlalu pendek
        $tokens = array_filter($tokens, function ($token) {
            return strlen($token) >= 2 && !in_array($token, $this->stopwords);
        });

        return array_values($tokens);
    }

    /**
     * Membuat vektor Term Frequency (TF) dari kata-kata yang sudah dirapikan
     */
    public function buildTFVector(array $tokens): array
    {
        $tf = [];
        $totalTokens = count($tokens);

        if ($totalTokens === 0) {
            return $tf;
        }

        // Hitung berapa kali tiap kata muncul
        $frequency = array_count_values($tokens);

        // Bagi dengan total kata agar nilainya lebih seimbang
        foreach ($frequency as $term => $count) {
            $tf[$term] = $count / $totalTokens;
        }

        return $tf;
    }

    /**
     * Menghitung kemiripan dua jawaban dengan Cosine Similarity
     * Hasil akhirnya berupa persentase 0 sampai 100
     */
    public function calculateSimilarity(array $vec1, array $vec2): float
    {
        if (empty($vec1) || empty($vec2)) {
            return 0.0;
        }

        // Gabungkan semua kata unik dari dua jawaban
        $allTerms = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

        // Hitung dot product dan panjang masing-masing vektor
        $dotProduct = 0.0;
        $magnitude1 = 0.0;
        $magnitude2 = 0.0;

        foreach ($allTerms as $term) {
            $val1 = $vec1[$term] ?? 0.0;
            $val2 = $vec2[$term] ?? 0.0;

            $dotProduct += $val1 * $val2;
            $magnitude1 += $val1 * $val1;
            $magnitude2 += $val2 * $val2;
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        // Kalau salah satu vektor kosong, hasilnya dianggap 0
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0.0;
        }

        // Rumus cosine similarity: dot(A,B) / (|A| * |B|)
        $similarity = $dotProduct / ($magnitude1 * $magnitude2);

        // Ubah hasilnya ke bentuk persen
        return round($similarity * 100, 2);
    }

    /**
     * Membandingkan semua jawaban dalam satu tugas yang sama
     */
    public function compareAnswers(int $tugasId): array
    {
        $tugas = Tugas::findOrFail($tugasId);

        // Ambil jawaban teks dari tugas ini saja
        $jawaban = JawabanTugas::where('tugas_id', $tugasId)
            ->whereNotNull('jawaban_text')
            ->where('jawaban_text', '!=', '')
            ->with('siswa')
            ->get();

        if ($jawaban->count() < 2) {
            return [
                'message' => 'Minimal 2 jawaban diperlukan untuk perbandingan.',
                'results' => [],
            ];
        }

        // Hapus hasil lama supaya data tidak dobel saat dicek ulang
        SimilarityResult::where('tugas_id', $tugasId)->delete();

        // Rapikan teks lalu buat vektor untuk tiap jawaban
        $vectors = [];
        foreach ($jawaban as $j) {
            $tokens = $this->preprocess($j->jawaban_text);
            $vectors[$j->id] = $this->buildTFVector($tokens);
        }

        $results = [];
        $jawabanArray = $jawaban->values();

        // Bandingkan setiap pasangan jawaban satu per satu
        for ($i = 0; $i < $jawabanArray->count(); $i++) {
            for ($k = $i + 1; $k < $jawabanArray->count(); $k++) {
                $j1 = $jawabanArray[$i];
                $j2 = $jawabanArray[$k];

                $percentage = $this->calculateSimilarity(
                    $vectors[$j1->id],
                    $vectors[$j2->id]
                );

                // Tentukan status dari nilai kemiripannya
                $status = 'safe';
                if ($percentage > 70) {
                    $status = 'plagiat';
                } elseif ($percentage >= 30) {
                    $status = 'warning';
                }

                // Simpan hasil pengecekan ke database
                $result = SimilarityResult::create([
                    'tugas_id' => $tugasId,
                    'jawaban_1_id' => $j1->id,
                    'jawaban_2_id' => $j2->id,
                    'similarity_percentage' => $percentage,
                    'status' => $status,
                    'tahun_ajaran_id' => $tugas->tahun_ajaran_id,
                ]);

                $results[] = [
                    'siswa_1' => $j1->siswa->nama,
                    'siswa_2' => $j2->siswa->nama,
                    'percentage' => $percentage,
                    'status' => $status,
                ];
            }
        }

        // Urutkan dari nilai similarity paling tinggi
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

        return [
            'message' => 'Analisis similarity selesai.',
            'total_jawaban' => $jawaban->count(),
            'total_comparisons' => count($results),
            'results' => $results,
        ];
    }
}
