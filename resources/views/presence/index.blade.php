@extends('templates.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">📋 Data Absensi</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (Auth::user()->role === 'karyawan')
            <a href="{{ route('presence.create') }}" class="btn btn-primary mb-3">🕒 Absen Sekarang</a>
        @endif
        <div class="d-flex justify-content-end mb-3">

            <a href="{{ route('presence.trash') }}" class="btn btn-warning mb-3 me-2">
            Recycle Bin </a>

            <a href="{{ route('presence.export') }}" class="btn btn-success mb-3 me-2">Export (.xlsx)</a>

            <a href="{{ route('presence.exportPDF') }}" class="btn btn-primary mb-3" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>



        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>Tanggal</th>
                    @if (Auth::user()->role === 'admin')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($presences as $presence)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $presence->user->name ?? '-' }}</td>
                        <td>{{ ucfirst($presence->status) }}</td>
                        <td>
                            @if ($presence->foto)
                                <img src="{{ asset($presence->foto) }}" alt="Foto Absensi" width="70" class="rounded">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>

                        <td>{{ $presence->created_at->format('d M Y H:i') }}</td>
                        @if (Auth::user()->role === 'admin')
                            <td>
                                <form action="{{ route('presence.destroy', $presence->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
