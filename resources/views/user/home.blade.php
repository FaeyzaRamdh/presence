@extends('templates.app')

@section('content')
<div class="container mt-3">
    <div class="card">
        <div class="card-body">
            <h5>Selamat Datang, {{ Auth::user()->name }}</h5>
            <p>Ini adalah halaman karyawan untuk absen.</p>

            <!-- Cek apakah sudah absen hari ini -->
            @php
                $todayPresence = App\Models\Presence::where('user_id', Auth::id())
                    ->whereDate('created_at', Carbon\Carbon::today())
                    ->first();
            @endphp

            @if(!$todayPresence)
                <!-- Tombol Absen Masuk -->
                <a href="{{ route('presence.create') }}" class="btn btn-primary">🕒 Absen Masuk</a>
            @elseif(!$todayPresence->jam_keluar)
                <!-- Tombol Absen Pulang -->
                <a href="{{ route('presence.edit', $todayPresence->id) }}" class="btn btn-warning">🚪 Absen Pulang</a>
            @else
                <div class="alert alert-info">
                    ✅ Anda sudah melakukan absen masuk dan pulang hari ini.
                </div>
            @endif

            <a href="{{ route('presence.index') }}" class="btn btn-secondary">📊 Lihat Riwayat Absensi</a>
        </div>
    </div>
</div>
@endsection