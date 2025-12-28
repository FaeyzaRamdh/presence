@extends('templates.app')

@section('content')
<div class="container">
    <h2>Recycle Bin - Data Absensi</h2>

    <a href="{{ route('presence.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presences as $key => $presence)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $presence->user->name }}</td>
                <td>{{ ucfirst($presence->status) }}</td>
                <td>{{ $presence->jam_masuk }}</td>
                <td>{{ $presence->jam_keluar ?? '-' }}</td>
                <td>
                    @if($presence->foto)
                        <img src="{{ asset($presence->foto) }}" width="50">
                    @endif
                </td>
                <td>
                    <a href="{{ route('presence.restore', $presence->id) }}" class="btn btn-success btn-sm">
                        Restore
                    </a>

                    <form action="{{ route('presence.forceDelete', $presence->id) }}" 
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus permanen data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus Permanen</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
