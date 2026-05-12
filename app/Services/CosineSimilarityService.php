<?php

namespace App\Services;

use App\Models\JawabanTugas;
use App\Models\SimilarityResult;
use App\Models\Tugas;

class CosineSimilarityService
{
    /**
     * Indonesian stopwords to filter out common words
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
     * Step 1: Preprocess text
     * - lowercase
     * - remove punctuation
     * - tokenize
     * - remove stopwords
     */
    public function preprocess(string $text): array
    {
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');

        // Remove punctuation and special characters, keep alphanumeric and spaces
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));

        // Tokenize (split into words)
        $tokens = explode(' ', $text);

        // Remove stopwords and short tokens (< 2 chars)
        $tokens = array_filter($tokens, function ($token) {
            return strlen($token) >= 2 && !in_array($token, $this->stopwords);
        });

        return array_values($tokens);
    }

    /**
     * Step 2: Build Term Frequency (TF) vector
     */
    public function buildTFVector(array $tokens): array
    {
        $tf = [];
        $totalTokens = count($tokens);

        if ($totalTokens === 0) {
            return $tf;
        }

        // Count frequency of each token
        $frequency = array_count_values($tokens);

        // Normalize by total number of tokens
        foreach ($frequency as $term => $count) {
            $tf[$term] = $count / $totalTokens;
        }

        return $tf;
    }

    /**
     * Step 3: Calculate Cosine Similarity between two TF vectors
     * Returns percentage (0-100)
     */
    public function calculateSimilarity(array $vec1, array $vec2): float
    {
        if (empty($vec1) || empty($vec2)) {
            return 0.0;
        }

        // Get all unique terms from both vectors
        $allTerms = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

        // Calculate dot product
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

        // Avoid division by zero
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0.0;
        }

        // Cosine similarity formula: dot(A,B) / (|A| * |B|)
        $similarity = $dotProduct / ($magnitude1 * $magnitude2);

        // Convert to percentage
        return round($similarity * 100, 2);
    }

    /**
     * Step 4: Compare all answers for a specific assignment (tugas)
     * Only compares jawaban within the SAME tugas_id
     */
    public function compareAnswers(int $tugasId): array
    {
        $tugas = Tugas::findOrFail($tugasId);

        // Get all text-based answers for this specific assignment
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

        // Delete old similarity results for this tugas
        SimilarityResult::where('tugas_id', $tugasId)->delete();

        // Preprocess and build TF vectors for each answer
        $vectors = [];
        foreach ($jawaban as $j) {
            $tokens = $this->preprocess($j->jawaban_text);
            $vectors[$j->id] = $this->buildTFVector($tokens);
        }

        $results = [];
        $jawabanArray = $jawaban->values();

        // Compare every pair of answers (n*(n-1)/2 comparisons)
        for ($i = 0; $i < $jawabanArray->count(); $i++) {
            for ($k = $i + 1; $k < $jawabanArray->count(); $k++) {
                $j1 = $jawabanArray[$i];
                $j2 = $jawabanArray[$k];

                $percentage = $this->calculateSimilarity(
                    $vectors[$j1->id],
                    $vectors[$j2->id]
                );

                // Determine status
                $status = 'safe';
                if ($percentage > 70) {
                    $status = 'plagiat';
                } elseif ($percentage >= 30) {
                    $status = 'warning';
                }

                // Save to database
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

        // Sort by highest similarity first
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

        return [
            'message' => 'Analisis similarity selesai.',
            'total_jawaban' => $jawaban->count(),
            'total_comparisons' => count($results),
            'results' => $results,
        ];
    }
}
