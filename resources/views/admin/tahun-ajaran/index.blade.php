@extends('layouts.app')
@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')
@section('content')
<div class="card">
    <div class="card-header"><h3>Daftar Tahun Ajaran</h3><a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Baru</a></div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Tahun</th><th>Semester</th><th>Status</th><th>Jumlah Kelas</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($tahunAjaran as $t)
                    <tr>
                        <td><strong>{{ $t->nama_tahun }}</strong></td>
                        <td>{{ $t->semester }}</td>
                        <td><span class="badge {{ $t->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($t->status) }}</span></td>
                        <td>{{ $t->kelas_count }}</td>
                        <td>
                            @if($t->status !== 'aktif')
                                <form action="{{ route('admin.tahun-ajaran.activate', $t) }}" method="POST" style="display:inline" onsubmit="return confirm('Aktifkan tahun ajaran ini?')">@csrf<button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Aktifkan</button></form>
                            @else
                                <span class="badge badge-green">Sedang Aktif</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $tahunAjaran->links('pagination.custom') }}
    </div>
</div>
@endsection
