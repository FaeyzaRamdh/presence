<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Absensi</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand me-2" href="#">
                <i id="fingerIcon" class="fas fa-fingerprint fa-sm text-primary " style="margin-top: -1px;"
                    aria-label="Absensi Logo"></i>

            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if (Auth::user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dataMasterDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Data Master</a>
                                <ul class="dropdown-menu" aria-labelledby="dataMasterDropdown">
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}">Data Pegawai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('presence.index') }}">Data Kehadiran</a>
                                    </li>
                                </ul>
                            </li>
                        @elseif(Auth::user()->role == 'user')
                            <li class="nav-item"><a class="nav-link" href="{{ route('user.home') }}">Absensi</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Beranda</a></li>
                    @endauth
                </ul>

                <div class="d-flex align-items-center">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link px-3 me-2 text-dark">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten halaman -->
    @yield('content')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('script')
</body>

</html>
