<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - Login</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body class="min-vh-100 d-flex align-items-center justify-content-center bg-primary bg-gradient p-4">


    <div class="card shadow login-card p-4 mx-auto"style="max-width: 450px; width: 100%; border-radius: 15px;">
        <div class="text-center mb-4">
            <i id="fingerIcon" class="fas fa-fingerprint fa-3x text-secondary"></i>

            <h3 class="fw-bold mt-2">INTRAlog</h3>
            <p class="text-muted">Silakan login untuk melanjutkan</p>
        </div>

        <!-- Alert -->
        @if (Session::get('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ Session::get('success') }}
            </div>
        @endif

        @if (Session::get('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ Session::get('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa fa-envelope text-primary"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        placeholder="Masukkan email" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <small class="text-danger"><i class="fa fa-exclamation-circle me-1"></i>{{ $message }}</small>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa fa-lock text-primary"></i></span>

                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Masukkan password">

                    <span class="password-toggle text-primary" onclick="togglePassword()">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <small class="text-danger"><i class="fa fa-exclamation-circle me-1"></i>{{ $message }}</small>
                @enderror
            </div>

            <!-- Button -->
            <button type="submit" class="btn btn-primary w-100 fw-semibold mb-3">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>

            <p class="text-center">
                <a href="https://wa.me/62895404747799?text=Halo%20Admin,%20saya%20lupa%20password.%20Mohon%20dibantu%20untuk%20pembuatan%20password%20baru."
                    class="text-primary" target="_blank">
                    Lupa Password?
                </a>
            </p>



        </form>

        <div class="text-center mt-3 text-muted">
            &copy; {{ date('Y') }} Sistem Absensi
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.querySelector(".password-toggle i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        const icon = document.getElementById('fingerIcon');

        // saat mouse masuk
        icon.addEventListener('mouseenter', function() {
            icon.classList.remove('text-secondary');
            icon.classList.add('text-primary');
        });

        // saat mouse keluar
        icon.addEventListener('mouseleave', function() {
            icon.classList.remove('text-primary');
            icon.classList.add('text-secondary');
        });
    </script>

</body>

</html>
