
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= esc($title ?? 'SIPASKANIM') ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Custom Layout -->
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">

    <!-- CSS Khusus Halaman -->
    <?= $this->renderSection('styles') ?>
</head>

<body class="bg-light text-dark">

    <!-- SIDEBAR -->
    <div class="offcanvas offcanvas-start text-white sidebar-panel"
         tabindex="-1"
         id="sidebarMenu"
         aria-labelledby="sidebarMenuLabel">

        <!-- HEADER SIDEBAR -->
        <div class="offcanvas-header border-bottom border-light border-opacity-25 p-4">

            <div>
                <h5 class="offcanvas-title fw-bold mb-1" id="sidebarMenuLabel">
                    SIPASKANIM
                </h5>
                <small class="text-white-50">
                    Sistem Arsip Paspor
                </small>
            </div>

            <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="offcanvas"
                    aria-label="Tutup menu">
            </button>

        </div>

        <!-- INFORMASI PENGGUNA -->
        <div class="m-3 p-3 rounded-3 bg-white bg-opacity-10">
            <small class="text-white-50 d-block mb-1">
                Login sebagai
            </small>

            <strong class="d-block text-break">
                <?= esc(session()->get('nama') ?? 'Pengguna') ?>
            </strong>

            <small class="text-white-50">
                <?= esc(ucfirst(session()->get('role') ?? '')) ?>
            </small>
        </div>

        <!-- MENU SIDEBAR -->
        <div class="offcanvas-body pt-2">

            <nav class="nav flex-column gap-2">

                <a href="<?= base_url('dashboard') ?>"
                   class="nav-link sidebar-link">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>

                <?php if (session()->get('role') === 'petugas'): ?>
                    <a href="<?= base_url('scan') ?>"
                       class="nav-link sidebar-link">
                        <i class="bi bi-camera me-2"></i>
                        Scan & Input Paspor
                    </a>
                <?php endif; ?>

                <a href="<?= base_url('arsip') ?>"
                   class="nav-link sidebar-link">
                    <i class="bi bi-folder2-open me-2"></i>
                    Arsip Data Paspor
                </a>

                <?php if (session()->get('role') === 'admin'): ?>
                    <a href="<?= base_url('users') ?>"
                       class="nav-link sidebar-link">
                        <i class="bi bi-people me-2"></i>
                        Manajemen Petugas
                    </a>
                <?php endif; ?>

                <hr class="border-light opacity-25">

                <a href="<?= base_url('logout') ?>"
                   class="nav-link sidebar-link"
                   onclick="return confirm('Yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </a>

            </nav>

        </div>

        <!-- FOOTER SIDEBAR -->
        <div class="p-3 border-top border-light border-opacity-25">
            <small class="text-white-50">
                SIPASKANIM © <?= date('Y') ?>
            </small>
        </div>

    </div>

    <!-- KONTEN UTAMA -->
    <main class="min-vh-100">

        <!-- TOPBAR -->
        <header class="sticky-top bg-white border-bottom px-3 px-md-4 py-3">

            <div class="d-flex align-items-center gap-3">

                <button class="btn btn-outline-secondary"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-label="Buka menu navigasi">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <div>
                    <strong class="text-dark">
                        Sistem Arsip Paspor
                    </strong>

                    <small class="d-block text-secondary">
                        Kantor Imigrasi Gorontalo
                    </small>
                </div>

            </div>

        </header>

        <!-- PAGE CONTENT -->
        <div class="container-fluid page-content">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('success')) ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('error')) ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </div>

    </main>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
