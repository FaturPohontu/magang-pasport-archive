
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIPASKANIM</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS Custom -->
    <link href="<?= base_url('css/login.css') ?>" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-navy" href="#">
            <img src="<?= base_url('img/logo-imigrasi.png') ?>" width="42" alt="Logo Imigrasi">
            <span>SIPASKANIM</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link active fw-semibold" href="#beranda">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#informasi">
                        <i class="bi bi-info-circle me-1"></i> Informasi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#tentang">
                        <i class="bi bi-building me-1"></i> Tentang
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#login" class="btn btn-navy rounded-pill px-4">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-section" id="beranda">
    <div class="container py-5">
        <div class="row align-items-center g-5">

            <!-- HERO TEXT -->
            <div class="col-lg-7">

                <span class="badge hero-badge px-3 py-2 mb-3">
                SISTEM ARSIP DIGITAL
                </span>

                <h1 class="fw-bold display-5 mb-3">
                Selamat Datang di
                    <span class="hero-highlight">SIPASKANIM</span>
                </h1>

                <p class="fs-5 fw-medium text-navy mb-3">
                    Sistem Informasi Arsip Paspor
                    Kantor Imigrasi Gorontalo
                </p>

                <p class="hero-description mb-4">
                    Kelola arsip dokumen secara digital dengan lebih
                    mudah, terstruktur, dan efisien. SIPASKANIM membantu
                    proses pengelolaan, pencarian, serta penyimpanan
                    arsip dalam satu sistem terintegrasi.
                </p>
            </div>

            <!-- LOGIN CARD -->
            <div class="col-lg-5" id="login">

                <div class="card login-card border-0 shadow-lg mx-auto">

                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <img src="<?= base_url('img/logo-imigrasi.png') ?>"
                                 width="65" class="mb-3" alt="Logo Imigrasi">

                            <h4 class="fw-bold text-navy mb-1">
                                LOGIN
                            </h4>

                            <p class="login-description small mb-0">
                                Silakan masuk untuk mengakses sistem
                            </p>
                        </div>

                        <!-- BACKEND ERROR -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger small text-center">
                                <?= esc(session()->getFlashdata('error')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success small text-center">
                                <?= esc(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-warning small">
                                <ul class="mb-0 ps-3">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- LOGIN FORM -->
                        <form action="<?= site_url('login') ?>" method="post">

                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-person"></i>
                                    </span>

                                    <input type="text"
                                           class="form-control"
                                           id="username"
                                           name="username"
                                           value="<?= esc(old('username') ?? '') ?>"
                                           placeholder="Masukkan username"
                                           autocomplete="username"
                                           required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    Password
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           name="password"
                                           placeholder="Masukkan password"
                                           autocomplete="current-password"
                                           required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-navy w-100 py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                MASUK
                            </button>

                        </form>

                        <div class="text-center mt-4">
                            <small class="login-description">
                                Pastikan username dan password
                                Anda sudah benar.
                            </small>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- INFORMASI SISTEM -->
<section class="py-5" id="informasi">
    <div class="container">

        <div class="border-bottom pb-3 mb-5">
            <h3 class="fw-bold feature-title">
                INFORMASI SISTEM
            </h3>

            <p class="text-secondary mb-0">
                Mengenal layanan dan fungsi SIPASKANIM.
            </p>
        </div>

        <div class="row g-4" id="fitur">

            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-folder2-open fs-1 text-primary"></i>

                    <div>
                        <h5 class="fw-bold">Arsip Digital</h5>
                        <p class="login-description small">
                            Penyimpanan dokumen arsip secara digital
                            agar lebih terstruktur dan mudah dikelola.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-camera fs-1 text-primary"></i>

                    <div>
                        <h5 class="fw-bold">Pemindaian Dokumen</h5>
                        <p class="login-description small">
                            Membantu proses digitalisasi dokumen
                            melalui pemindaian dan pengenalan teks.
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-search fs-1 text-primary"></i>

                    <div>
                        <h5 class="fw-bold">Pencarian Arsip</h5>
                        <p class="login-description small">
                            Mempermudah pencarian dan penelusuran
                            informasi dokumen yang telah tersimpan.
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-database fs-1 text-primary"></i>

                    <div>
                        <h5 class="fw-bold">Pengelolaan Data</h5>
                        <p class="login-description small">
                            Pengelolaan informasi arsip dalam satu
                            sistem yang terpusat.
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-shield-lock fs-1 text-primary"></i>

                    <div>
                        <h5 class="fw-bold">Keamanan Data</h5>
                        <p class="login-description small">
                            Akses sistem melalui proses autentikasi
                            pengguna.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-clock-history fs-1 text-primary"></i>

                    <div>
                        <h5 class="fw-bold">Akses Informasi</h5>
                        <p class="login-description small">
                            Memudahkan petugas dalam mengakses
                            informasi arsip yang dibutuhkan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer-section py-5" id="tentang">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="<?= base_url('img/logo-imigrasi.png') ?>"
                         width="45" alt="Logo Imigrasi">

                    <h4 class="fw-bold mb-0 text-white">
                        SIPASKANIM
                    </h4>
                </div>

                <p class="text-light opacity-75">
                    SIPASKANIM merupakan Sistem Informasi Arsip
                    Paspor yang dikembangkan untuk membantu proses
                    pengelolaan dan digitalisasi arsip pada
                    Kantor Imigrasi Gorontalo.
                </p>
            </div>


            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-3">
                    MENU
                </h6>

                <ul class="list-unstyled footer-links">
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#informasi">Informasi Sistem</a></li>
                    <li><a href="#fitur">Fitur Sistem</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                </ul>
            </div>


            <div class="col-6 col-lg-3">
                <h6 class="fw-bold text-white mb-3">
                    INFORMASI
                </h6>

                <ul class="list-unstyled footer-links">
                    <li>Kantor Imigrasi Gorontalo</li>
                    <li>Sistem Arsip Digital</li>
                    <li>Informasi Pengelolaan Arsip</li>
                </ul>
            </div>

        </div>

        <hr class="border-light opacity-25 my-4">

        <div class="text-center text-light opacity-75 small">
            &copy; <?= date('Y') ?> SIPASKANIM.
            Sistem Informasi Arsip Paspor.
        </div>

    </div>
</footer>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>