@extends('layouts.app')
@section('title', 'Data Guru')
@section('page-title', 'Data Guru')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Guru</h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.import.guru') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-excel"></i> Import</a>
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" style="margin-bottom:16px" class="flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari nama/NIP..." value="{{ request('search') }}" style="max-width:300px">
            <button class="btn btn-primary btn-sm">Cari</button>
        </form>
        <div class="table-wrapper">
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
                            <a href="{{ route('admin.guru.edit', $g) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.guru.reset-password', $g) }}" method="POST" onsubmit="return confirm('Reset password ke NIP?')">@csrf<button class="btn btn-outline btn-sm" title="Reset Password"><i class="fas fa-key"></i></button></form>
                            <form action="{{ route('admin.guru.destroy', $g) }}" method="POST" onsubmit="return confirm('Hapus guru ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Belum ada data guru.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $guru->withQueryString()->links('pagination.custom') }}
    </div>
</div>
@endsection
