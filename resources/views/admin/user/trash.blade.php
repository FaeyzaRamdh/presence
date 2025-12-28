@extends('templates.app')

@section('content')
<div class="container mt-4">

    <h4>Recycle Bin - Karyawan Terhapus</h4>

    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif

    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <table class="table table-bordered">
        <thead class="table-danger text-center">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th width="200px">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $i => $user)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <td class="d-flex gap-2">
                    <a href="{{ route('users.restore', $user->id) }}" class="btn btn-success btn-sm">Restore</a>

                    <form action="{{ route('users.force-delete', $user->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Hapus permanen?')" class="btn btn-danger btn-sm">
                            Hapus Permanen
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
