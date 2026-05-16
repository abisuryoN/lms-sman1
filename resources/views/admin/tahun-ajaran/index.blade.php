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
                    <tr style="{{ $t->is_archived ? 'opacity: 0.6; background: #f8fafc;' : '' }}">
                        <td>
                            <strong>{{ $t->nama_tahun }}</strong>
                            @if($t->is_archived)
                                <span class="badge badge-gray" style="font-size: 10px; margin-left: 4px;"><i class="fas fa-archive"></i> Terarsip</span>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $t->semester }}</td>
                        <td style="text-align: center;">
                            <span class="badge {{ $t->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($t->status) }}</span>
                        </td>
                        <td style="text-align: center;">{{ $t->kelas_count }}</td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                @if($t->status !== 'aktif')
                                    <form action="{{ route('admin.tahun-ajaran.activate', $t) }}" method="POST" style="display:inline" onsubmit="return confirm('Aktifkan tahun ajaran ini?')">@csrf<button class="btn btn-success btn-sm" title="Jadikan Tahun Ajaran Aktif"><i class="fas fa-check"></i> Aktifkan</button></form>
                                @endif
                                
                                <a href="{{ route('admin.tahun-ajaran.edit', $t) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                
                                <form action="{{ route('admin.tahun-ajaran.archive', $t) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn btn-warning btn-sm" title="{{ $t->is_archived ? 'Pulihkan dari Arsip' : 'Arsipkan' }}">
                                        <i class="fas {{ $t->is_archived ? 'fa-box-open' : 'fa-archive' }}"></i>
                                    </button>
                                </form>

                                @if($t->status !== 'aktif')
                                    <button type="button" class="btn btn-danger btn-sm" title="Cleanup Storage" onclick="showCleanupModal({{ $t->id }}, '{{ $t->full_name }}')">
                                        <i class="fas fa-broom"></i>
                                    </button>

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
                <div class="mobile-card" style="{{ $t->is_archived ? 'opacity: 0.8;' : '' }}">
                    <div class="mobile-card-title">
                        {{ $t->nama_tahun }}
                        @if($t->is_archived)
                            <span class="badge badge-gray" style="font-size: 10px; float: right;"><i class="fas fa-archive"></i> Arsip</span>
                        @endif
                    </div>
                    
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
                            <form action="{{ route('admin.tahun-ajaran.activate', $t) }}" method="POST" onsubmit="return confirm('Aktifkan tahun ajaran ini?')">@csrf<button class="btn btn-success btn-sm" title="Aktifkan"><i class="fas fa-check"></i></button></form>
                        @endif
                        <a href="{{ route('admin.tahun-ajaran.edit', $t) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.tahun-ajaran.archive', $t) }}" method="POST" style="display:inline">@csrf<button class="btn btn-warning btn-sm" title="Arsip"><i class="fas {{ $t->is_archived ? 'fa-box-open' : 'fa-archive' }}"></i></button></form>
                        
                        @if($t->status !== 'aktif')
                            <button type="button" class="btn btn-danger btn-sm" title="Cleanup" onclick="showCleanupModal({{ $t->id }}, '{{ $t->full_name }}')"><i class="fas fa-broom"></i></button>
                            <form action="{{ route('admin.tahun-ajaran.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button></form>
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

{{-- Cleanup Modal --}}
<div id="cleanupModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-card" style="width: 100%; max-width: 450px; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding: 20px; background: #FEF2F2; border-bottom: 1px solid #FEE2E2; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: #FEE2E2; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fas fa-broom"></i>
            </div>
            <div>
                <h4 style="margin: 0; color: #991B1B;">Cleanup Storage</h4>
                <p id="cleanup-tahun-name" style="margin: 0; font-size: 13px; color: #B91C1C;"></p>
            </div>
        </div>
        <form id="form-cleanup" method="POST" style="padding: 20px;">
            @csrf
            <div style="background: #FFFBEB; border: 1px solid #FEF3C7; padding: 12px; border-radius: 12px; margin-bottom: 20px;">
                <p style="margin: 0; font-size: 13px; color: #92400E; line-height: 1.5;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i>
                    Tindakan ini akan menghapus semua file materi dan soal tugas dari Supabase Storage secara <strong>permanen</strong>. Metadata di database akan dikosongkan.
                </p>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="checkbox-container" style="font-size: 14px; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="include_submissions" value="1" style="width: 18px; height: 18px;">
                    <span>Hapus juga file jawaban siswa (submissions)</span>
                </label>
                <small style="display: block; margin-left: 28px; color: var(--text-muted); font-size: 11px;">Jawaban siswa adalah bukti tugas, centang hanya jika sudah benar-benar tidak dibutuhkan.</small>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Ketik <span style="color: #EF4444;">HAPUS FILE</span> untuk konfirmasi:</label>
                <input type="text" id="cleanup-confirm-text" name="confirmation" class="form-control" placeholder="HAPUS FILE" autocomplete="off" oninput="validateCleanup(this)">
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="button" onclick="closeCleanupModal()" class="btn btn-gray" style="flex: 1;">Batal</button>
                <button type="submit" id="btn-execute-cleanup" class="btn btn-danger" style="flex: 1;" disabled>Eksekusi Hapus</button>
            </div>
        </form>
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

    function showCleanupModal(id, name) {
        const modal = document.getElementById('cleanupModal');
        const form = document.getElementById('form-cleanup');
        const nameText = document.getElementById('cleanup-tahun-name');
        
        form.action = `/admin/tahun-ajaran/${id}/cleanup`;
        nameText.innerText = name;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeCleanupModal() {
        const modal = document.getElementById('cleanupModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('cleanup-confirm-text').value = '';
        document.getElementById('btn-execute-cleanup').disabled = true;
    }

    function validateCleanup(input) {
        const btn = document.getElementById('btn-execute-cleanup');
        btn.disabled = input.value !== 'HAPUS FILE';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('cleanupModal');
        if (event.target == modal) {
            closeCleanupModal();
        }
    }
</script>
@endpush
