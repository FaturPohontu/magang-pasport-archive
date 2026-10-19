<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIPASKANIM</title>

    <!-- Google Font (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- File CSS Kustom Login Kamu -->
    <link href="<?= base_url('css/login.css') ?>" rel="stylesheet">
</head>
<body>

<div class="login-wrapper">
    
    <!-- HEADER: Logo, Judul & Subtitle -->
    <div class="header-box">
        <img src="<?= base_url('img/logo-imigrasi.png') ?>" alt="Logo Imigrasi">
        <h4>SIPASKANIM</h4>
        <p>Sistem Arsip Paspor Kantor Imigrasi Gorontalo</p>
    </div>

    <!-- CARD FORM LOGIN -->
    <div class="login-card">
        <h4 class="text-center fw-bold text-navy mb-4" style="letter-spacing: 0.5px;">LOGIN</h4>

        <!-- AREA BACK-END (TIDAK DIGANGGU SAMA SEKALI) -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger p-2 text-center" style="font-size: 13px;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success p-2 text-center" style="font-size: 13px;">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-warning p-2" style="font-size: 13px;">
                <ul class="mb-0 px-3">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    class="form-control custom-input"
                    id="username"
                    name="username"
                    value="<?= esc(old('username') ?? '') ?>"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    class="form-control custom-input"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-navy">
                MASUK
            </button>
        </form>
        <!-- AKHIR AREA BACK-END -->
    </div>

</div>

<!-- Bootstrap JS via CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>