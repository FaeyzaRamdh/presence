@extends('templates.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">🕒 Absensi Pulang</h2>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5>Detail Absensi Masuk</h5>
            <p><strong>Tanggal:</strong> {{ $presence->created_at->format('d/m/Y') }}</p>
            <p><strong>Jam Masuk:</strong> {{ $presence->jam_masuk }}</p>
            <p><strong>Status:</strong> {{ ucfirst($presence->status) }}</p>
        </div>
    </div>

    <form action="{{ route('presence.update', $presence->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="alert alert-info">
            <strong>Info:</strong> Klik tombol di bawah untuk melakukan absen pulang.
        </div>

        <button type="submit" class="btn btn-warning">🚪 Absen Pulang</button>
        <a href="{{ route('presence.index') }}" class="btn btn-secondary">⬅ Kembali</a>
    </form>
</div>
@endsection