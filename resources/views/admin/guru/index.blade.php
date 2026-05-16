@extends('layouts.app')
@section('title', 'Data Guru')
@section('page-title', 'Data Guru')
@section('content')
<div class="card">
    <div class="card-header responsive-header">
        <h3>Daftar Guru</h3>
        <div class="header-actions flex gap-2">
            <a href="{{ route('admin.import.guru') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-excel"></i> Import</a>
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" style="margin-bottom:16px" class="flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari nama/NIP..." value="{{ request('search') }}" style="max-width:300px">
            <button class="btn btn-primary btn-sm">Cari</button>
        </form>
        
        <div class="desktop-table table-wrapper">
            <table>
                <thead><tr><th>No</th><th>NIP</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($guru as $i => $g)
                    <tr>
                        <td>{{ $guru->firstItem() + $i }}</td>
                        <td><code>{{ $g->nip }}</code></td>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->user->email }}</td>
                        <td>{{ $g->telepon ?? '-' }}</td>
                        <td class="flex gap-2">
                            <a href="{{ route('admin.guru.edit', $g) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.guru.reset-password', $g) }}" method="POST">
                                @csrf
                                <button type="button" class="btn btn-outline btn-sm btn-delete" data-confirm="Password guru akan dikembalikan ke NIP mereka. Lanjutkan?" title="Reset Password"><i class="fas fa-key"></i></button>
                            </form>
                            <form action="{{ route('admin.guru.destroy', $g) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-confirm="Hapus data guru dan akun user mereka secara permanen?" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data guru.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-cards">
            @forelse($guru as $g)
                <div class="mobile-card">
                    <div class="mobile-card-title">{{ $g->nama }}</div>
                    
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">NIP</span>
                        <span class="mobile-card-value"><code>{{ $g->nip }}</code></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Email</span>
                        <span class="mobile-card-value">{{ $g->user->email }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Telepon</span>
                        <span class="mobile-card-value">{{ $g->telepon ?? '-' }}</span>
                    </div>

                    <div class="mobile-card-actions">
                        <a href="{{ route('admin.guru.edit', $g) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.guru.reset-password', $g) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-outline btn-sm btn-delete" data-confirm="Password guru akan dikembalikan ke NIP mereka. Lanjutkan?" title="Reset Password"><i class="fas fa-key"></i> Reset PW</button>
                        </form>
                        <form action="{{ route('admin.guru.destroy', $g) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-confirm="Hapus data guru dan akun user mereka secara permanen?" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data guru.</div>
            @endforelse
        </div>
        {{ $guru->links() }}
    </div>
</div>
@endsection
