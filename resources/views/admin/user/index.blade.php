@extends('templates.app')

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('users.trash') }}" class="btn btn-warning me-2">Recycle Bin</a>

            <a href="{{ route('users.export') }}" class="btn btn-success me-2">
                Export (.xlsx)
            </a>

            <a href="{{ route('users.exportPDF') }}" class="btn btn-primary me-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('users.create') }}" class="btn btn-success">Tambah Karyawan</a>
        </div>

        <h5>Data Karyawan</h5>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('users.delete', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
