
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/arsip.css') ?>">

<div class="arsip-wrapper">

    <!-- HEADER -->
    <div class="arsip-header">
        <div>
            <h2>Arsip Data Paspor</h2>
            <p>
                Daftar seluruh data arsip paspor yang tersimpan
                dalam database SIPASKANIM.
            </p>
        </div>

        <a href="<?= base_url('scan') ?>" class="btn btn-primary">
            + Scan Paspor
        </a>
    </div>

    <!-- TABEL ARSIP -->
    <div class="arsip-card">

        <!-- PENCARIAN -->
        <form action="<?= base_url('arsip') ?>"
              method="get"
              class="search-form">

            <div class="search-input">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Cari kode permohonan atau nama billing..."
                    value="<?= esc($keyword) ?>"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Cari
            </button>

            <?php if ($keyword !== ''): ?>
                <a href="<?= base_url('arsip') ?>"
                   class="btn btn-outline-secondary">
                    Reset
                </a>
            <?php endif; ?>

        </form>

        <!-- TABEL -->
        <div class="table-responsive">

            <table class="table table-hover align-middle">

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
                                    <strong>
                                        <?= esc($arsip['kode_permohonan']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= esc($arsip['nama_billing']) ?>
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
                                            Tersinkron
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">
                                            Belum tersinkron
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center empty-data">
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
            <div class="pagination-wrapper">
                <?= $pager->links('arsip', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
