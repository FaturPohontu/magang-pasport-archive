
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= esc($title ?? 'SIPASKANIM') ?></title>

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <!-- CSS Layout -->
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
</head>

<body>

    <!-- SIDEBAR BOOTSTRAP OFFCANVAS -->
    <div
        class="offcanvas offcanvas-start sidebar-panel"
        tabindex="-1"
        id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel"
    >
        <!-- Header Sidebar -->
        <div class="offcanvas-header sidebar-heading">

            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>

                <div>
                    <h5 class="offcanvas-title" id="sidebarMenuLabel">
                        SIPASKANIM
                    </h5>
                    <small>Sistem Arsip Paspor</small>
                </div>
            </div>

            <button
                type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Tutup menu"
            ></button>
        </div>

        <!-- Informasi Pengguna -->
        <div class="user-info">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>

            <div class="user-details">
                <small>Login sebagai</small>
                <strong>
                    <?= esc(session()->get('name') ?? 'Pengguna') ?>
                </strong>
            </div>
        </div>

        <!-- Navigasi -->
        <div class="offcanvas-body">
            <p class="menu-label">MENU UTAMA</p>

            <nav class="sidebar-nav">

                <a href="<?= base_url('dashboard') ?>" class="sidebar-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= base_url('scan') ?>" class="sidebar-link">
                    <i class="bi bi-camera"></i>
                    <span>Scan & Input Paspor</span>
                </a>

                <hr class="sidebar-divider">

                <a
                    href="<?= base_url('logout') ?>"
                    class="sidebar-link logout-link"
                    onclick="return confirm('Yakin ingin keluar?')"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>

            </nav>
        </div>

        <!-- Footer Sidebar -->
        <div class="sidebar-footer">
            <i class="bi bi-shield-check"></i>
            SIPASKANIM &copy; <?= date('Y') ?>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <!-- Header -->
        <header class="topbar">

            <!-- Tombol Buka Sidebar -->
            <button
                class="btn menu-toggle"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu"
                aria-label="Buka menu navigasi"
            >
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-title">
                <strong>Sistem Arsip Paspor</strong>
                <small>Kantor Imigrasi Gorontalo</small>
            </div>

        </header>

        <!-- Isi Halaman -->
        <div class="page-content">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </div>
    </main>

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
