@extends('templates.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- Header Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center py-4">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <div class="bg-primary rounded-circle p-3 me-3">
                                <i class="fas fa-clock text-white fs-4"></i>
                            </div>
                            <h1 class="h3 mb-0 text-primary fw-bold">Absensi Masuk</h1>
                        </div>
                        <p class="text-muted mb-0">Lakukan absensi harian dengan mengambil foto selfie terlebih dahulu</p>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="card shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-3 fs-5"></i>
                                    <div>
                                        <h6 class="mb-1">Terjadi Kesalahan</h6>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li class="small">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-times-circle me-3 fs-5"></i>
                                    <div>
                                        {{ session('error') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('presence.store') }}" method="POST" enctype="multipart/form-data"
                            id="presenceForm">
                            @csrf

                            <!-- Status Field -->
                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold mb-3">
                                    <i class="fas fa-user-check me-2 text-primary"></i>Status Kehadiran
                                </label>
                                <select name="status" id="status" class="form-select form-select-lg py-3" required>
                                    <option value="">-- Pilih Status Kehadiran --</option>
                                    <option value="hadir">✓ Hadir</option>
                                    <option value="izin">📝 Izin</option>
                                    <option value="sakit">🏥 Sakit</option>
                                </select>
                                <div class="form-text text-muted mt-2">
                                    Pilih sesuai dengan kondisi kehadiran Anda
                                </div>
                            </div>

                            <!-- Camera Section -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-3">
                                    <i class="fas fa-camera me-2 text-primary"></i>Foto Absen Selfie
                                </label>

                                <!-- Camera Preview Card -->
                                <div class="card border-light mb-3">
                                    <div class="card-body text-center p-4">
                                        <div class="position-relative d-inline-block mb-3">
                                            <video id="video" class="rounded shadow-sm" width="100%" max-width="480"
                                                height="320" autoplay playsinline></video>
                                            <div
                                                class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                                <div class="rounded-circle bg-white bg-opacity-25 p-2">
                                                    <i class="fas fa-user text-white fs-1"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                                            <button type="button" id="snap" class="btn btn-primary btn-lg px-4 py-3">
                                                <i class="fas fa-camera me-2"></i>Ambil Foto
                                            </button>
                                            <button type="button" id="retakeBtn"
                                                class="btn btn-outline-secondary btn-lg px-4 py-3 d-none">
                                                <i class="fas fa-redo me-2"></i>Ambil Ulang
                                            </button>
                                        </div>

                                        <div class="mt-4">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Pastikan wajah terlihat jelas dalam foto untuk keperluan verifikasi
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden Elements -->
                                <canvas id="canvas" width="640" height="480" class="d-none"></canvas>
                                <input type="file" name="foto" id="foto" class="d-none">

                                <!-- Photo Preview Card -->
                                <div class="card border-primary mt-3" id="previewCard" style="display:none;">
                                    <div class="card-header bg-primary bg-opacity-10">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-check-circle me-2"></i>Preview Foto
                                        </h6>
                                    </div>
                                    <div class="card-body text-center p-3">
                                        <img id="preview" src="" alt="Preview Foto"
                                            class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center pt-3 mt-4 border-top">
                                <a href="{{ route('user.home') }}" class="btn btn-outline-secondary px-4 py-2">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-success px-5 py-2 fw-semibold">
                                    <i class="fas fa-check-circle me-2"></i>Simpan Absensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info shadow-sm mt-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-info fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading mb-2">Tips Absensi</h6>
                            <ul class="mb-0 small">
                                <li>Pastikan koneksi internet stabil sebelum melakukan absensi</li>
                                <li>Ambil foto di tempat yang cukup terang</li>
                                <li>Pilih status kehadiran yang sesuai</li>
                                <li>Absensi hanya dapat dilakukan sekali per hari</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const snap = document.getElementById('snap');
        const retakeBtn = document.getElementById('retakeBtn');
        const preview = document.getElementById('preview');
        const previewCard = document.getElementById('previewCard');
        const fotoInput = document.getElementById('foto');
        const form = document.getElementById('presenceForm');
        let stream = null;

        // Akses kamera
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: {
                        ideal: 640
                    },
                    height: {
                        ideal: 480
                    }
                }
            })
            .then(s => {
                stream = s;
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
                alert('Tidak bisa mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
            });

        // Tombol ambil foto
        snap.addEventListener('click', () => {
            if (!stream) {
                alert('Kamera tidak tersedia. Silakan refresh halaman dan berikan izin akses kamera.');
                return;
            }

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Tampilkan preview
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);
            preview.src = dataURL;
            previewCard.style.display = 'block';

            // Tampilkan tombol ambil ulang, sembunyikan tombol ambil foto
            snap.classList.add('d-none');
            retakeBtn.classList.remove('d-none');

            // Convert dataURL ke Blob dan simpan di input file
            fetch(dataURL)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], 'absen_' + Date.now() + '.jpg', {
                        type: 'image/jpeg'
                    });
                    fotoInput.files = new DataTransfer().files;
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fotoInput.files = dataTransfer.files;
                });
        });

        // Tombol ambil ulang
        retakeBtn.addEventListener('click', () => {
            // Sembunyikan preview
            previewCard.style.display = 'none';

            // Tampilkan tombol ambil foto, sembunyikan tombol ambil ulang
            snap.classList.remove('d-none');
            retakeBtn.classList.add('d-none');

            // Kosongkan input file
            fotoInput.value = '';
        });

        // Submit form pakai FormData agar foto pasti terkirim
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!fotoInput.files[0]) {
                alert('Silakan ambil foto terlebih dahulu!');
                return;
            }

            const formData = new FormData(this);
            formData.set('foto', fotoInput.files[0]); // pastikan foto ada

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Absensi berhasil disimpan!');
                        window.location.href = '{{ route('user.home') }}';
                    } else {
                        alert(data.error || 'Terjadi kesalahan saat upload foto');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal mengirim data ke server');
                });
        });

        // Clean up stream saat halaman ditutup
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });
    </script>
@endsection
