<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2>Dashboard Statistik</h2>

<p>Selamat datang, <strong><?= session()->get('name') ?></strong>!</p>

<div style="display: flex; gap: 15px; margin-top: 20px;">
    <div style="border: 1px solid #ccc; padding: 15px; width: 30%;">
        <h4>Paspor Hari Ini</h4>
        <p style="font-size: 24px; font-weight: bold;">0</p>
    </div>
    <div style="border: 1px solid #ccc; padding: 15px; width: 30%;">
        <h4>WNI / WNA Hari Ini</h4>
        <p>WNI: 0 | WNA: 0</p>
    </div>
    <div style="border: 1px solid #ccc; padding: 15px; width: 30%;">
        <h4>Total Tahun 2026</h4>
        <p style="font-size: 24px; font-weight: bold;">0</p>
    </div>
</div>
<?= $this->endSection() ?>