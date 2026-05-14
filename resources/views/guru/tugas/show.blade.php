@extends('layouts.app')
@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas')
@section('content')
<div class="mb-4">
    <a href="{{ route('guru.tugas.index') }}" class="btn btn-outline" style="background:#fff;border:1px solid #E2E8F0"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <div>
            <h3 style="margin-bottom: 4px;">{{ $tuga->judul }}</h3>
            @if($tuga->similarity_status != 'unchecked')
                <span class="badge badge-{{ $tuga->similarity_badge_color }}" style="font-size:11px;">
                    <i class="fas {{ $tuga->similarity_status == 'completed' ? 'fa-check-circle' : ($tuga->similarity_status == 'processing' ? 'fa-spinner fa-spin' : 'fa-exclamation-circle') }}"></i>
                    Similarity: {{ $tuga->similarity_status_label }}
                    @if($tuga->similarity_checked_at)
                        ({{ $tuga->similarity_checked_at->format('d M Y H:i') }})
                    @endif
                </span>
            @endif
        </div>
        <div class="flex gap-2">
            <form action="{{ route('guru.similarity.run', $tuga) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm" {{ $tuga->similarity_status == 'processing' ? 'disabled' : '' }}>
                    <i class="fas fa-search-plus"></i> {{ $tuga->similarity_status == 'processing' ? 'Memproses...' : 'Cek Similarity' }}
                </button>
            </form>
            @if($tuga->similarityResults->count() > 0)
                <a href="{{ route('guru.similarity.detail', $tuga->id) }}" class="btn btn-outline btn-sm" style="background:#fff;">
                    <i class="fas fa-list-alt"></i> Hasil Similarity
                </a>
            @endif
            <a href="{{ route('guru.nilai.edit', $tuga) }}" class="btn btn-warning btn-sm"><i class="fas fa-star"></i> Nilai</a>
        </div>
    </div>
    <div class="card-body">
        <div class="grid-2">
            <div><strong>Kelas:</strong> {{ $tuga->kelas->nama_kelas }}</div>
            <div><strong>Mapel:</strong> {{ $tuga->mapel->nama_mapel }}</div>
            <div><strong>Deadline:</strong> {{ $tuga->deadline->format('d M Y H:i') }}</div>
            <div><strong>Status Tugas:</strong> <span class="badge {{ $tuga->isExpired() ? 'badge-red' : 'badge-green' }}">{{ $tuga->isExpired() ? 'Berakhir' : 'Aktif' }}</span></div>
        </div>
        @if($tuga->deskripsi)<div style="margin-top:16px;padding:12px;background:var(--primary-50);border-radius:8px">{{ $tuga->deskripsi }}</div>@endif
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Jawaban Siswa ({{ $jawaban->count() }})</h3></div>
    <div class="card-body table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Waktu Submit</th>
                    <th>Status OCR & Teks</th>
                    <th>File Jawaban</th>
                </tr>
            </thead>
            <tbody>
            @forelse($jawaban as $j)
                <tr>
                    <td>
                        <div style="font-weight:600; color:var(--text-strong);">{{ $j->siswa->nama }}</div>
                        <div style="font-size:11px; color:var(--text-muted);">NIS: {{ $j->siswa->nis }}</div>
                    </td>
                    <td>{{ $j->submitted_at ? $j->submitted_at->format('d M Y H:i') : '-' }}</td>
                    <td>
                        <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-start;">
                            @if($j->storage_path)
                                <span class="badge badge-{{ $j->ocr_badge_color }}" style="font-size:10px;">
                                    OCR: {{ $j->ocr_status_label }}
                                </span>
                            @endif
                            
                            @if($j->extracted_text || $j->processed_text)
                                <button type="button" onclick="showOcrText({{ $j->id }})" class="btn btn-outline btn-sm" style="padding:2px 8px; font-size:11px; background:#fff;">
                                    <i class="fas fa-align-left"></i> Lihat Teks OCR
                                </button>
                            @elseif($j->jawaban_text)
                                <div style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;" title="{{ $j->jawaban_text }}">
                                    {{ $j->jawaban_text }}
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:11px;">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($j->storage_path)
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <div style="font-size:12px; font-weight:500; color:#0F172A; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $j->original_filename ?: basename($j->storage_path) }}">
                                    <i class="fas fa-cloud text-primary" style="margin-right:4px;"></i> {{ $j->original_filename ?: basename($j->storage_path) }}
                                </div>
                                <div style="display:flex; gap:4px;">
                                    <a href="{{ route('guru.similarity.view-file', $j) }}" target="_blank" class="btn btn-outline btn-sm" style="padding:2px 6px; font-size:11px; background:#fff;" title="Lihat File">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('guru.jawaban.download', $j) }}" class="btn btn-primary btn-sm" style="padding:2px 6px; font-size:11px;" title="Download File">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        @elseif($j->file_path)
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <div style="font-size:12px; color:var(--text-muted);">File Lokal Lama</div>
                                <a href="{{ route('guru.jawaban.download', $j) }}" class="btn btn-outline btn-sm" style="width:fit-content;"><i class="fas fa-download"></i> Download</a>
                            </div>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center" style="padding:24px;color:var(--text-muted)">Belum ada jawaban.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
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
                            <span class="badge badge-primary">Status OCR: ${data.ocr_status_label}</span>
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
