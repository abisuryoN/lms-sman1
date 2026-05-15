@extends('layouts.app')
@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')
@section('content')
<div class="card">
    <div class="card-header responsive-header" style="justify-content: space-between;">
        <h3>Daftar Tahun Ajaran</h3>
        <div class="header-actions" style="display: flex; gap: 8px;">
            <form id="form-akhiri-tahun" action="{{ route('admin.tahun-ajaran.akhiri') }}" method="POST">
                @csrf
                <button type="button" onclick="confirmAkhiriTahun()" class="btn btn-danger btn-sm"><i class="fas fa-power-off"></i> Tutup Tahun Ajaran</button>
            </form>
            <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Baru</a>
        </div>
    </div>
    <div class="card-body">
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>Tahun</th><th style="text-align: center;">Semester</th><th style="text-align: center;">Status</th><th style="text-align: center;">Jumlah Kelas</th><th style="text-align: center;">Aksi</th></tr></thead>
                <tbody>
                @foreach($tahunAjaran as $t)
                    <tr>
                        <td><strong>{{ $t->nama_tahun }}</strong></td>
                        <td style="text-align: center;">{{ $t->semester }}</td>
                        <td style="text-align: center;"><span class="badge {{ $t->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($t->status) }}</span></td>
                        <td style="text-align: center;">{{ $t->kelas_count }}</td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                @if($t->status !== 'aktif')
                                    <form action="{{ route('admin.tahun-ajaran.activate', $t) }}" method="POST" style="display:inline" onsubmit="return confirm('Aktifkan tahun ajaran ini?')">@csrf<button class="btn btn-success btn-sm" title="Jadikan Tahun Ajaran Aktif"><i class="fas fa-check"></i> Aktifkan</button></form>
                                @endif
                                
                                <a href="{{ route('admin.tahun-ajaran.edit', $t) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                
                                @if($t->status !== 'aktif')
                                    <form action="{{ route('admin.tahun-ajaran.destroy', $t) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($tahunAjaran as $t)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $t->nama_tahun }}</div>
                    
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Semester</span>
                        <span class="mobile-card-value">{{ $t->semester }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Status</span>
                        <span class="mobile-card-value">
                            <span class="badge {{ $t->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($t->status) }}</span>
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Jumlah Kelas</span>
                        <span class="mobile-card-value">{{ $t->kelas_count }}</span>
                    </div>

                    <div class="mobile-card-actions">
                        @if($t->status !== 'aktif')
                            <form action="{{ route('admin.tahun-ajaran.activate', $t) }}" method="POST" onsubmit="return confirm('Aktifkan tahun ajaran ini?')">@csrf<button class="btn btn-success btn-sm" title="Jadikan Tahun Ajaran Aktif"><i class="fas fa-check"></i> Aktifkan</button></form>
                        @endif
                        <a href="{{ route('admin.tahun-ajaran.edit', $t) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                        @if($t->status !== 'aktif')
                            <form action="{{ route('admin.tahun-ajaran.destroy', $t) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data tahun ajaran.</div>
            @endforelse
        </div>
        {{ $tahunAjaran->links('pagination.custom') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmAkhiriTahun() {
        Swal.fire({
            title: 'Tutup Tahun Ajaran?',
            html: `
                <div style="text-align: left; font-size: 14px;">
                    Mengakhiri tahun ajaran akan berdampak pada:
                    <ul style="margin-top: 10px; padding-left: 20px; line-height: 1.6;">
                        <li>Siswa kelas XII otomatis <strong>LULUS</strong>.</li>
                        <li>Siswa kelas X dan XI otomatis <strong>NAIK KELAS</strong>.</li>
                        <li>Penugasan mengajar guru dan Wali Kelas akan <strong>DIRESET</strong>.</li>
                        <li>Tahun Ajaran Baru (Ganjil) akan dibuat otomatis.</li>
                    </ul>
                    <p style="margin-top: 15px; color: var(--danger); font-weight: 600;">Apakah Anda sangat yakin ingin melanjutkan?</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="fas fa-power-off"></i> Ya, Tutup Sekarang',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'premium-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-akhiri-tahun').submit();
            }
        });
    }
</script>
@endpush
