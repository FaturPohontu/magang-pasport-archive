
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'SIPASKANIM') ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- CSS buatan sendiri -->
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
</head>
<body>

    <!-- SIDEBAR BOOTSTRAP OFFCANVAS -->
    <div class="offcanvas offcanvas-start sidebar-panel"
         tabindex="-1"
         id="sidebarMenu"
         aria-labelledby="sidebarMenuLabel">

        <div class="offcanvas-header sidebar-heading">
            <div>
                <h5 class="offcanvas-title" id="sidebarMenuLabel">
                    SIPASKANIM
                </h5>
                <small>Sistem Arsip Paspor</small>
            </div>

            <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="offcanvas"
                    aria-label="Tutup menu">
            </button>
        </div>

        <div class="user-info">
            <small>Login sebagai</small>
            <strong><?= esc(session()->get('name') ?? 'Pengguna') ?></strong>
        </div>

        <div class="offcanvas-body">
            <nav class="sidebar-nav">
                <a href="<?= base_url('dashboard') ?>" class="sidebar-link">
                    <span>📊</span> Dashboard
                </a>

                <a href="<?= base_url('scan') ?>" class="sidebar-link">
                    <span>📷</span> Scan & Input Paspor
                </a>

                <hr class="border-light opacity-25">

                <a href="<?= base_url('logout') ?>"
                   class="sidebar-link"
                   onclick="return confirm('Yakin ingin keluar?')">
                    <span>🚪</span> Logout
                </a>
            </nav>
        </div>

        <div class="sidebar-footer">
            SIPASKANIM © <?= date('Y') ?>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <header class="topbar">
            <!-- Tombol pembuka sidebar -->
            <button class="btn menu-toggle"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-label="Buka menu navigasi">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div>
                <strong>Sistem Arsip Paspor</strong>
                <small class="d-block text-secondary">
                    Kantor Imigrasi Gorontalo
                </small>
            </div>
        </header>

        <div class="page-content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Bootstrap JS Bundle: diperlukan untuk Offcanvas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
