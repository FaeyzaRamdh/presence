@extends('templates.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">🕒 Absensi Masuk</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('presence.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="status" class="form-label">Status Kehadiran</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto (opsional)</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">✅ Absen Masuk</button>
        <a href="{{ route('user.home') }}" class="btn btn-secondary">⬅ Kembali</a>
    </form>
</div>
@endsection