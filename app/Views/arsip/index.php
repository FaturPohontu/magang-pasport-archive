<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/arsip.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <!-- HEADER HALAMAN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h4 class="arsip-title mb-1">Arsip Data Paspor</h4>
            <p class="arsip-description mb-0">
                Daftar seluruh data arsip paspor yang tersimpan
                dalam database SIPASKANIM.
            </p>
        </div>

        <a href="<?= base_url('scan') ?>" class="btn btn-brand">
            <i class="bi bi-camera me-2"></i>
            Scan Paspor
        </a>

    </div>

    <!-- KARTU ARSIP -->
    <div class="card arsip-card border-0">
        <div class="card-body p-3 p-md-4">

            <!-- PENCARIAN -->
            <form action="<?= base_url('arsip') ?>" method="get"
                  class="row g-2 mb-4">

                <div class="col-12 col-md">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="q"
                            class="form-control arsip-search"
                            placeholder="Cari kode permohonan atau nama billing..."
                            value="<?= esc($keyword) ?>"
                        >
                    </div>
                </div>

                <div class="col-6 col-md-auto">
                    <button type="submit" class="btn btn-brand w-100">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>
                </div>

                <?php if ($keyword !== ''): ?>
                    <div class="col-6 col-md-auto">
                        <a href="<?= base_url('arsip') ?>"
                           class="btn btn-reset w-100">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                <?php endif; ?>

            </form>

            <!-- TABEL ARSIP -->
            <div class="table-responsive">
                <table class="table table-hover align-middle table-arsip mb-0">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Permohonan</th>
                            <th>Nama Billing</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Permohonan</th>
                            <th>Tanggal Cetak</th>
                            <th>Tanggal Input</th>
                            <th>Google Sheets</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (! empty($dataArsip)): ?>

                            <?php
                            $nomor = 1 + (($pager->getCurrentPage('arsip') - 1) * 10);
                            ?>

                            <?php foreach ($dataArsip as $arsip): ?>
                                <tr>

                                    <td><?= $nomor++ ?></td>

                                    <td>
                                        <?php if ($arsip['kode_permohonan'] === 'TIDAK ADA DATA'): ?>
                                            <span class="badge bg-danger">
                                                TIDAK ADA DATA
                                            </span>
                                        <?php else: ?>
                                            <strong class="text-dark">
                                                <?= esc($arsip['kode_permohonan']) ?>
                                            </strong>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($arsip['nama_billing'] === 'TIDAK ADA DATA'): ?>
                                            <span class="badge bg-danger">
                                                TIDAK ADA DATA
                                            </span>
                                        <?php else: ?>
                                            <?= esc($arsip['nama_billing']) ?>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= ! empty($arsip['tgl_lahir'])
                                            ? date('d-m-Y', strtotime($arsip['tgl_lahir']))
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <?= esc($arsip['jenis_permohonan']) ?>
                                    </td>

                                    <td>
                                        <?= ! empty($arsip['dicetak_tanggal'])
                                            ? date('d-m-Y', strtotime($arsip['dicetak_tanggal']))
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <?= ! empty($arsip['created_at'])
                                            ? date('d-m-Y H:i', strtotime($arsip['created_at']))
                                            : '-' ?>
                                    </td>

                                    <td>
                                        <?php if ((int) ($arsip['sheets_synced'] ?? 0) === 1): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Tersinkron
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>
                                                Belum tersinkron
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center py-5 text-secondary">
                                    <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>

                                    <?php if ($keyword !== ''): ?>
                                        Data dengan kata kunci
                                        "<strong><?= esc($keyword) ?></strong>"
                                        tidak ditemukan.
                                    <?php else: ?>
                                        Belum ada data arsip paspor.
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($pager->getPageCount('arsip') > 1): ?>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">

                    <small class="text-secondary">
                        Halaman <?= $pager->getCurrentPage('arsip') ?>
                        dari <?= $pager->getPageCount('arsip') ?>
                    </small>

                    <div class="arsip-pagination">
                        <?= $pager->links('arsip', 'default_full') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
