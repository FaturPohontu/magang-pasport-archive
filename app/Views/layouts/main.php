<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Paspor OCR' ?></title>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; }
        .sidebar { width: 220px; background: #2c3e50; color: white; min-height: 100vh; padding: 20px; }
        .sidebar a { color: #ecf0f1; display: block; margin: 12px 0; text-decoration: none; }
        .sidebar a:hover { text-decoration: underline; }
        .content { flex: 1; padding: 25px; }
        .user-info { background: #34495e; padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { color: green; font-weight: bold; }
        .alert-error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Sidebar / Navigasi Menu -->
    <div class="sidebar">
        <h3>App Paspor OCR</h3>
        
        <div class="user-info">
            <small>Login sebagai:</small><br>
            <strong><?= session()->get('name') ?></strong>
        </div>

        <nav>
            <a href="<?= base_url('dashboard') ?>">📊 Dashboard</a>
            <a href="<?= base_url('scan') ?>">📷 Scan & Input Paspor</a>
            <hr>
            <a href="<?= base_url('logout') ?>" onclick="return confirm('Yakin ingin keluar?')">🚪 Logout</a>
        </nav>
    </div>

    <!-- Area Konten Utama -->
    <div class="content">
        <?php if (session()->getFlashdata('success')): ?>
            <p class="alert-success"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <p class="alert-error"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

</body>
</html>