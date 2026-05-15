@extends('layouts.app')
@section('title', 'Hasil Similarity')
@section('page-title', 'Hasil Similarity — '.$tuga->judul)
@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('guru.tugas.show', $tuga) }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0">
        <i class="fas fa-arrow-left"></i> Kembali ke Detail Tugas
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <div>
            <h3 style="margin-bottom: 4px;">Hasil Analisis Kemiripan — {{ $tuga->judul }}</h3>
            <div style="font-size:12px; color:var(--text-muted);">
                Kelas: <strong>{{ $tuga->kelas->nama_kelas ?? '-' }}</strong> | 
                Status Terakhir: 
                <span class="badge badge-{{ $tuga->similarity_badge_color }}" id="statusBadge" style="font-size:10px;">
                    {{ $tuga->similarity_status_label }}
                </span>
            </div>
        </div>
        <form action="{{ route('guru.similarity.run', $tuga) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm" id="runBtn" {{ $tuga->similarity_status == 'processing' ? 'disabled' : '' }}>
                <i class="fas {{ $tuga->similarity_status == 'processing' ? 'fa-spinner fa-spin' : 'fa-sync-alt' }}"></i> 
                {{ $tuga->similarity_status == 'processing' ? 'Sedang Diproses...' : 'Re-Analisis' }}
            </button>
        </form>
    </div>
    
    <div class="card-body">
        <div id="processingBanner" style="display: {{ $tuga->similarity_status == 'processing' ? 'flex' : 'none' }}; align-items:center; gap:12px; background:#EFF6FF; border:1px solid #BFDBFE; padding:16px; border-radius:12px; margin-bottom:20px; color:#1E3A8A;">
            <i class="fas fa-circle-notch fa-spin" style="font-size:24px; color:#3B82F6;"></i>
            <div>
                <div style="font-weight:600; font-size:14px;">Proses Pengecekan Sedang Berjalan di Background</div>
                <div style="font-size:12px; color:#3B82F6;">Halaman ini akan diperbarui secara otomatis begitu proses analisis selesai. Anda tidak perlu memuat ulang halaman.</div>
            </div>
        </div>

        <div class="table-wrapper">
            @if($results->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width:35%;">Dokumen Siswa A</th>
                        <th style="width:35%;">Dokumen Siswa B</th>
                        <th style="width:15%;">Tingkat Kemiripan</th>
                        <th style="width:15%;">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($results as $r)
                    <tr style="background: {{ $r->similarity_percentage >= 70 ? '#FFF5F5' : ($r->similarity_percentage >= 40 ? '#FFFAF0' : 'inherit') }}">
                        <td>
                            <div style="font-weight:600; color:var(--text-strong);">{{ $r->jawaban1->siswa->nama ?? '-' }}</div>
                            <div style="display:flex; gap:6px; margin-top:6px;">
                                @if($r->jawaban1?->storage_path)
                                    <a href="{{ route('guru.similarity.view-file', $r->jawaban1) }}" target="_blank" class="btn btn-outline btn-sm" style="padding:2px 6px; font-size:10px; background:#fff;" title="Lihat File Asli">
                                        <i class="fas fa-file-alt text-primary"></i> File Asli
                                    </a>
                                @endif
                                @if($r->jawaban1?->extracted_text || $r->jawaban1?->processed_text)
                                    <button type="button" onclick="showOcrText({{ $r->jawaban1->id }})" class="btn btn-outline btn-sm" style="padding:2px 6px; font-size:10px; background:#fff;" title="Lihat Teks OCR">
                                        <i class="fas fa-align-left text-success"></i> Teks OCR
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600; color:var(--text-strong);">{{ $r->jawaban2->siswa->nama ?? '-' }}</div>
                            <div style="display:flex; gap:6px; margin-top:6px;">
                                @if($r->jawaban2?->storage_path)
                                    <a href="{{ route('guru.similarity.view-file', $r->jawaban2) }}" target="_blank" class="btn btn-outline btn-sm" style="padding:2px 6px; font-size:10px; background:#fff;" title="Lihat File Asli">
                                        <i class="fas fa-file-alt text-primary"></i> File Asli
                                    </a>
                                @endif
                                @if($r->jawaban2?->extracted_text || $r->jawaban2?->processed_text)
                                    <button type="button" onclick="showOcrText({{ $r->jawaban2->id }})" class="btn btn-outline btn-sm" style="padding:2px 6px; font-size:10px; background:#fff;" title="Lihat Teks OCR">
                                        <i class="fas fa-align-left text-success"></i> Teks OCR
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700; font-size:16px; color: {{ $r->similarity_percentage >= 70 ? '#DC2626' : ($r->similarity_percentage >= 40 ? '#D97706' : '#059669') }};">
                                {{ $r->similarity_percentage }}%
                            </div>
                            <div style="width:100%; background:#E2E8F0; height:6px; border-radius:3px; margin-top:4px; overflow:hidden;">
                                <div style="width: {{ $r->similarity_percentage }}%; background: {{ $r->similarity_percentage >= 70 ? '#DC2626' : ($r->similarity_percentage >= 40 ? '#F59E0B' : '#10B981') }}; height:100%;"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $r->badge_color }}" style="font-size:11px; display:inline-block; margin-bottom:4px;">
                                {{ $r->similarity_category }}
                            </span>
                            <div style="font-size:11px; color:var(--text-muted);">
                                {{ $r->status_label }}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <div style="text-align:center;padding:40px;color:var(--text-muted)">
                    <i class="fas fa-user-shield" style="font-size:48px;margin-bottom:16px;display:block;color:#CBD5E1;"></i>
                    <h4 style="color:var(--text-strong); margin-bottom:8px;">Belum Ada Hasil Perbandingan</h4>
                    <p style="font-size:13px; max-width:400px; margin:0 auto;">Jika tugas ini memiliki minimal 2 pengumpulan jawaban, klik tombol <strong>Re-Analisis</strong> di atas untuk memproses deteksi kemiripan teks menggunakan Cosine Similarity.</p>
                </div>
            @endif
        </div>

        <div class="mobile-cards">
            @forelse($results as $r)
                <div class="mobile-card">
                    <div class="mobile-card-title">Perbandingan Siswa</div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Siswa A</span>
                        <span class="mobile-card-value"><strong>{{ $r->jawaban1->siswa->nama ?? '-' }}</strong></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Siswa B</span>
                        <span class="mobile-card-value"><strong>{{ $r->jawaban2->siswa->nama ?? '-' }}</strong></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kemiripan</span>
                        <span class="mobile-card-value">
                            <span style="font-weight:700; color: {{ $r->similarity_percentage >= 70 ? '#DC2626' : ($r->similarity_percentage >= 40 ? '#D97706' : '#059669') }};">
                                {{ $r->similarity_percentage }}%
                            </span>
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kategori</span>
                        <span class="mobile-card-value"><span class="badge badge-{{ $r->badge_color }}">{{ $r->similarity_category }}</span></span>
                    </div>

                    <div class="mobile-card-actions">
                        @if($r->jawaban1?->storage_path)
                            <a href="{{ route('guru.similarity.view-file', $r->jawaban1) }}" target="_blank" class="btn btn-outline btn-sm">A: File</a>
                        @endif
                        @if($r->jawaban2?->storage_path)
                            <a href="{{ route('guru.similarity.view-file', $r->jawaban2) }}" target="_blank" class="btn btn-outline btn-sm">B: File</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada hasil perbandingan.</div>
            @endforelse
        </div>

        {{ $results->links() }}
    </div>
</div>

@push('scripts')
<script>
// Background job status polling
document.addEventListener('DOMContentLoaded', function() {
    let currentStatus = @json($tuga->similarity_status);
    const tugasId = @json($tuga->id);
    const pollInterval = 3000; // 3 detik

    if (currentStatus === 'processing') {
        const intervalId = setInterval(() => {
            fetch(`/guru/similarity/${tugasId}/status`)
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'processing') {
                        clearInterval(intervalId);
                        window.location.reload();
                    }
                })
                .catch(err => console.error('Error polling status:', err));
        }, pollInterval);
    }
});

function showOcrText(jawabanId) {
    Swal.fire({
        title: 'Memuat Teks OCR...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/guru/similarity/ocr-text/${jawabanId}`)
        .then(response => {
            if (!response.ok) throw new Error('Gagal memuat data');
            return response.json();
        })
        .then(data => {
            Swal.fire({
                title: `Teks Ekstraksi: ${data.siswa_nama}`,
                html: `
                    <div style="text-align:left; font-size:13px; line-height:1.6;">
                        <div style="margin-bottom:12px;">
                            <span class="badge badge-primary">Status Teks: ${data.ocr_status_label}</span>
                        </div>
                        <div style="font-weight:600; color:var(--primary); margin-bottom:4px; border-bottom:1px solid #E2E8F0; padding-bottom:4px;">Teks Mentah (Ekstraksi):</div>
                        <div style="background:#F8FAFC; padding:10px; border-radius:8px; max-height:150px; overflow-y:auto; margin-bottom:16px; white-space:pre-wrap; border:1px solid #E2E8F0; font-family:monospace;">${escapeHtml(data.extracted_text)}</div>
                        
                        <div style="font-weight:600; color:var(--primary); margin-bottom:4px; border-bottom:1px solid #E2E8F0; padding-bottom:4px;">Teks Terproses (Siap Uji):</div>
                        <div style="background:#F8FAFC; padding:10px; border-radius:8px; max-height:150px; overflow-y:auto; white-space:pre-wrap; border:1px solid #E2E8F0; font-family:monospace;">${escapeHtml(data.processed_text)}</div>
                    </div>
                `,
                width: '600px',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3B82F6'
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengambil teks OCR.'
            });
        });
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/&/g, "&amp;")
               .replace(/</g, "&lt;")
               .replace(/>/g, "&gt;")
               .replace(/"/g, "&quot;")
               .replace(/'/g, "&#039;");
}
</script>
@endpush
@endsection
