@extends('templates.app')

@section('content')
<div class="container mt-3">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="card-title mb-1">Selamat Datang, {{ Auth::user()->name }}</h5>
                    <p class="card-text text-muted mb-0">Ini adalah halaman karyawan untuk absen.</p>
                </div>
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 50px; height: 50px;">
                    <i class="fas fa-user text-white"></i>
                </div>
            </div>

            @php
                $todayPresence = App\Models\Presence::where('user_id', Auth::id())
                    ->whereDate('jam_masuk', Carbon\Carbon::today())
                    ->first();
            @endphp

            @if (!$todayPresence)
                <!-- Tombol Absen Masuk -->
                <div class="d-grid gap-2 d-md-flex mb-3">
                    <a href="{{ route('presence.create') }}" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-clock me-2"></i>Absen Masuk
                    </a>
                </div>
            @elseif (!$todayPresence->jam_keluar)
                <!-- Tombol Absen Pulang -->
                <div class="d-grid gap-2 d-md-flex mb-3">
                    <a href="{{ route('presence.edit', $todayPresence->id) }}" class="btn btn-warning btn-lg me-2">
                        <i class="fas fa-door-open me-2"></i>Absen Pulang
                    </a>
                </div>
            @else
                <!-- Absensi Selesai -->
                @php
                    $jamMasuk = \Carbon\Carbon::parse($todayPresence->jam_masuk);
                    $jamKeluar = \Carbon\Carbon::parse($todayPresence->jam_keluar);
                    $durasi = $jamKeluar->diff($jamMasuk);
                    $durasiKerja = $durasi->h . ' jam ' . $durasi->i . ' menit';
                @endphp

                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>
                            <strong>Absensi Selesai</strong>
                            <p class="mb-0">
                                Anda sudah melakukan absen masuk dan pulang hari ini.<br>
                                Waktu kerja: {{ $durasiKerja }} ({{ $todayPresence->jam_masuk }} - {{ $todayPresence->jam_keluar }})
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tombol Riwayat -->
            <div class="d-grid gap-2 d-md-flex mb-3">
                <a href="{{ route('presence.index') }}" class="btn btn-secondary">
                    <i class="fas fa-history me-2"></i>Lihat Riwayat Absensi
                </a>
            </div>

            <!-- Statistik Ringkas -->
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check text-primary fs-1 mb-2"></i>
                            <h5 class="card-title">Hari Ini</h5>
                            <p class="card-text fs-4">{{ $todayPresence ? 'Absen' : 'Belum Absen' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar text-success fs-1 mb-2"></i>
                            <h5 class="card-title">Status</h5>
                            @php
                                if (!$todayPresence) {
                                    $status = 'Belum Bekerja';
                                } elseif ($todayPresence && !$todayPresence->jam_keluar) {
                                    $status = 'Sedang Bekerja';
                                } else {
                                    $status = 'Selesai Bekerja';
                                }
                            @endphp
                            <p class="card-text fs-4">{{ $status }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock text-info fs-1 mb-2"></i>
                            <h5 class="card-title">Jam Kerja</h5>
                            @if ($todayPresence && $todayPresence->jam_masuk && $todayPresence->jam_keluar)
                                <p class="card-text fs-4">{{ $todayPresence->jam_masuk }} - {{ $todayPresence->jam_keluar }}</p>
                            @elseif ($todayPresence && $todayPresence->jam_masuk)
                                <p class="card-text fs-4">{{ $todayPresence->jam_masuk }} - ...</p>
                            @else
                                <p class="card-text fs-4">Belum Absen</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- card-body -->
    </div> <!-- card -->
</div> <!-- container -->
@endsection
