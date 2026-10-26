
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">

<div class="dashboard-wrapper">

    <!-- HEADER -->
    <div class="header-hero">
        <div class="dashboard-header">

            <div class="brand-group">
                <img
                    src="<?= base_url('img/logo-imigrasi.png') ?>"
                    alt="Logo Imigrasi"
                    class="brand-logo"
                >

                <div class="brand-info">
                    <h4 class="brand-title">SIPASKANIM</h4>
                    <p class="brand-subtitle">
                        Sistem Arsip Paspor Kantor Imigrasi Gorontalo
                    </p>
                </div>
            </div>

            <a href="#" class="scan-btn">
                <svg
                    class="scan-icon"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4M4 4h4v4H4V4zm12 0h4v4h-4V4zM4 16h4v4H4v-4zm12 0h4v4h-4v-4z"
                    />
                </svg>
                <span>SCAN</span>
            </a>

        </div>

        <!-- WELCOME -->
        <div class="welcome-box">
            <p class="welcome-label">Selamat Datang,</p>
            <h1 class="welcome-name">
                <?= esc(session()->get('name') ?? 'Pengguna') ?>
            </h1>
            <p class="welcome-description">
                Berikut ringkasan data arsip paspor Anda.
            </p>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="content-sheet">

        <div class="section-heading">
            <div>
                <h2>Ringkasan Dashboard</h2>
                <p>Informasi data pemindaian paspor</p>
            </div>
        </div>

        <div class="stats-grid">

            <!-- CARD 1 -->
            <div class="stat-card card-gold">
                <div class="stat-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <rect x="5" y="3" width="14" height="18" rx="2"/>
                        <path d="M9 7h6M9 11h6M9 15h3"/>
                    </svg>
                </div>

                <div class="stat-content">
                    <p class="stat-txt">Paspor Hari Ini</p>
                    <div class="stat-val">0</div>
                    <span class="stat-note">Data paspor yang dipindai hari ini</span>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="stat-card card-teal">
                <div class="stat-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <circle cx="9" cy="8" r="3"/>
                        <path d="M3 20v-2a6 6 0 0 1 12 0v2"/>
                        <circle cx="17" cy="9" r="2.5"/>
                        <path d="M17 15a4 4 0 0 1 4 4v1"/>
                    </svg>
                </div>

                <div class="stat-content">
                    <p class="stat-txt">Kategori Pemohon</p>

                    <div class="wni-wna-box">
                        <div class="category-item">
                            <span>WNI</span>
                            <strong>0</strong>
                        </div>

                        <div class="category-divider"></div>

                        <div class="category-item">
                            <span>WNA</span>
                            <strong>0</strong>
                        </div>
                    </div>

                    <span class="stat-note">Jumlah berdasarkan kategori pemohon</span>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="stat-card card-blue">
                <div class="stat-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18M8 14h3M8 17h7"/>
                    </svg>
                </div>

                <div class="stat-content">
                    <p class="stat-txt">Total Pemindaian Tahun 2026</p>
                    <div class="stat-val">0</div>
                    <span class="stat-note">Akumulasi pemindaian paspor tahun 2026</span>
                </div>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>
