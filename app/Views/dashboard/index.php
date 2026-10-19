<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Memanggil CSS Terpisah -->
<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">

<div class="dashboard-wrapper">

    <!-- HEADER HERO BLUE -->
    <div class="header-hero">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Brand Logo & Title -->
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url('img/logo-imigrasi.png') ?>" alt="Logo" class="brand-logo">
                <div>
                    <h4 class="brand-title">SAPASKANIM</h4>
                    <p class="brand-subtitle">Sistem Arsip Paspor Kantor Imigrasi Gorontalo</p>
                </div>
            </div>

            <!-- Tombol SCAN Top-Right -->
            <a href="#" class="scan-btn">
                <svg class="scan-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4M4 4h4v4H4V4zm12 0h4v4h-4V4zM4 16h4v4H4v-4zm12 0h4v4h-4v-4z"></path>
                </svg>
                SCAN
            </a>
        </div>

        <!-- Ucapan Selamat Datang -->
        <div class="welcome-box">
            <p class="welcome-label">Selamat Datang...</p>
            <h1 class="welcome-name"><?= session()->get('name') ?></h1>
        </div>
    </div>

    <!-- CONTENT CONTAINER (Kartu Putih Lengkung Desktop) -->
    <div class="content-sheet">
        <div class="row g-4">
            
            <!-- Card 1: Paspor Hari Ini (Kuning-Emas) -->
            <div class="col-md-4">
                <div class="stat-card card-gold">
                    <div class="stat-val">0</div>
                    <p class="stat-txt">Paspor Hari Ini</p>
                </div>
            </div>

            <!-- Card 2: WNI / WNA Hari Ini (Teal) -->
            <div class="col-md-4">
                <div class="stat-card card-teal">
                    <div class="stat-val" style="font-size: 1.8rem;">WNI / WNA</div>
                    <div class="wni-wna-box">
                        <span>WNI: <strong>0</strong></span>
                        <span>|</span>
                        <span>WNA: <strong>0</strong></span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Paspor Tahun 2026 (Biru Tua) -->
            <div class="col-md-4">
                <div class="stat-card card-blue">
                    <div class="stat-val">0</div>
                    <p class="stat-txt">Pasport yang sudah di scan 2026</p>
                </div>
            </div>

        </div>
    </div>

</div>
<?= $this->endSection() ?>