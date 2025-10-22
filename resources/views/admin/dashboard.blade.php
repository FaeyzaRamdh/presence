@extends('templates.app')

@section('content')
<div class="container mt-3">
    <h5 class="my-3">Dashboard Admin</h5>

    @if(Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
        </div> 
    @endif

    <div class="row">
        <!-- Total Karyawan -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Karyawan</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalUsers ?? 0 }}</h5>
                    <p class="card-text">Jumlah karyawan yang terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Total Kehadiran Hari Ini -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Kehadiran Hari Ini</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalPresenceToday ?? 0 }}</h5>
                    <p class="card-text">Kehadiran karyawan hari ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Karyawan belum absen -->
    <div class="mt-4">
        <h5>Karyawan Belum Absen Hari Ini</h5>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>
            @forelse($notPresentUsers as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Semua karyawan telah absen hari ini.</td>
            </tr>
            @endforelse
        </table>
    </div>
</div>
@endsection
