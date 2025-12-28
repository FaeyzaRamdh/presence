@extends('templates.app')

@section('content')
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">Dashboard Admin</h2>
        <span class="text-muted small">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </span>
    </div>

    @if(Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }} <b>{{ Auth::user()->name }}</b>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $statusCounts = ['belum' => 0, 'sedang' => 0, 'selesai' => 0];
        $userStatuses = [];

        foreach ($users as $user) {
            $todayPresence = \App\Models\Presence::where('user_id', $user->id)
                ->whereDate('jam_masuk', \Carbon\Carbon::today())
                ->first();

            if (!$todayPresence) {
                $status = 'Belum Bekerja';
                $badgeClass = 'bg-secondary';
                $statusCounts['belum']++;
            } elseif ($todayPresence->jam_masuk && !$todayPresence->jam_keluar) {
                $status = 'Sedang Bekerja';
                $badgeClass = 'bg-warning text-dark';
                $statusCounts['sedang']++;
            } else {
                $status = 'Selesai Bekerja';
                $badgeClass = 'bg-success';
                $statusCounts['selesai']++;
            }

            $userStatuses[$user->id] = [
                'status' => $status,
                'badge' => $badgeClass
            ];
        }

        $totalPresenceToday = $statusCounts['sedang'] + $statusCounts['selesai'];
        $attendancePercentage = $totalUsers > 0
            ? ($totalPresenceToday / $totalUsers * 100)
            : 0;
    @endphp

    <!-- STATISTIC CARDS -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Karyawan</h6>
                    <h2 class="fw-bold text-primary">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Hadir Hari Ini</h6>
                    <h2 class="fw-bold text-success">{{ $totalPresenceToday }}</h2>
                    <div class="progress mt-2" style="height:6px">
                        <div class="progress-bar bg-success"
                             style="width: {{ $attendancePercentage }}%"></div>
                    </div>
                    <small class="text-muted">
                        {{ number_format($attendancePercentage, 1) }}%
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <h6 class="text-muted text-center mb-3">Ringkasan Status</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sedang Bekerja</span>
                        <span class="badge bg-warning text-dark">
                            {{ $statusCounts['sedang'] }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Selesai Bekerja</span>
                        <span class="badge bg-success">
                            {{ $statusCounts['selesai'] }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Belum Bekerja</span>
                        <span class="badge bg-secondary">
                            {{ $statusCounts['belum'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary mb-0">
                            Grafik Status Kehadiran Hari Ini
                        </h5>
                    </div>

                    <div style="max-width:480px;margin:auto">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            Daftar Kehadiran Karyawan
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="180">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $userStatuses[$user->id]['badge'] }}">
                                    {{ $userStatuses[$user->id]['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    new Chart(document.getElementById('attendanceChart'), {
        type: 'doughnut',
        data: {
            labels: ['Belum Bekerja', 'Sedang Bekerja', 'Selesai Bekerja'],
            datasets: [{
                data: [
                    {{ $statusCounts['belum'] }},
                    {{ $statusCounts['sedang'] }},
                    {{ $statusCounts['selesai'] }}
                ],
                backgroundColor: ['#6c757d', '#ffc107', '#198754']
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<style>
.card {
    transition: all .2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.08);
}
</style>
@endsection
