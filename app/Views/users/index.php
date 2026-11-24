<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/users.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$validationErrors = session()->getFlashdata('validation') ?? [];
?>

<!-- HEADER HALAMAN -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div>
        <h4 class="users-title mb-1">Manajemen Petugas</h4>
        <p class="users-description mb-0">
            Kelola akun pengguna dengan hak akses Petugas.
        </p>
    </div>

    <button
        type="button"
        class="btn btn-brand"
        data-bs-toggle="modal"
        data-bs-target="#modalTambahPetugas">

        <i class="bi bi-person-plus me-2"></i>
        Tambah Petugas
    </button>

</div>
    <!-- Pencarian -->
    <!-- PENCARIAN -->
<div class="card users-card border-0 mb-4">
    <div class="card-body p-3 p-md-4">

        <form action="<?= base_url('users') ?>" method="get" class="row g-2">

            <div class="col-12 col-md">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        name="q"
                        class="form-control users-search"
                        placeholder="Cari nama atau username petugas..."
                        value="<?= esc($keyword ?? '') ?>">
                </div>
            </div>

            <div class="col-6 col-md-auto">
                <button type="submit" class="btn btn-brand w-100">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>
            </div>

            <?php if (! empty($keyword)): ?>
                <div class="col-6 col-md-auto">
                    <a href="<?= base_url('users') ?>" class="btn btn-reset w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>
                </div>
            <?php endif; ?>

        </form>

    </div>
</div>

    <!-- Tabel Petugas -->
    <!-- TABEL PETUGAS -->
    <div class="card users-card border-0">

        <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-3">
            <h6 class="users-table-title mb-0">Daftar Akun Petugas</h6>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle table-users mb-0">

                    <thead>
                        <tr>
                            <th class="ps-3">No.</th>
                            <th>Nama Petugas</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (! empty($users)): ?>

                            <?php
                            $nomor = 1 + (($pager->getCurrentPage() - 1) * 10);
                            ?>

                            <?php foreach ($users as $petugas): ?>
                                <tr>
                                    <td class="ps-3">
                                        <?= $nomor++ ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= esc($petugas['nama']) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= esc($petugas['username']) ?>
                                    </td>

                                    <td>
                                        <?php if ((int) $petugas['is_active'] === 1): ?>
                                            <span class="badge bg-success">
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= ! empty($petugas['created_at'])
                                            ? date('d-m-Y', strtotime($petugas['created_at']))
                                            : '-' ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ((int) $petugas['is_active'] === 1): ?>
                                            <form
                                                action="<?= base_url('users/deactivate/' . $petugas['id']) ?>"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menonaktifkan akun Petugas ini?')">

                                                <?= csrf_field() ?>

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-secondary small">
                                                Sudah dinonaktifkan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-secondary">
                                        Belum ada data Petugas.
                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
                </tbody>
            </table>
        </div>

    </div>

    <!-- PAGINATION -->
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="card-footer bg-white border-0 px-3 px-md-4 py-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                <small class="text-secondary">
                    Halaman <?= $pager->getCurrentPage() ?>
                    dari <?= $pager->getPageCount() ?>
                </small>

                <div class="users-pagination">
                    <?= $pager->links() ?>
                </div>

            </div>

        </div>
    <?php endif; ?>

</div>


<!-- Modal Tambah Petugas -->
<div
    class="modal fade"
    id="modalTambahPetugas"
    tabindex="-1"
    aria-labelledby="modalTambahPetugasLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="<?= base_url('users/store') ?>" method="post">

                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahPetugasLabel">
                        Tambah Akun Petugas
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info small">
                        Akun yang dibuat melalui formulir ini otomatis memiliki
                        hak akses Petugas.
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control <?= isset($validationErrors['nama']) ? 'is-invalid' : '' ?>"
                            placeholder="Masukkan nama lengkap"
                            value="<?= esc(old('nama')) ?>"
                            required>

                        <?php if (isset($validationErrors['nama'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($validationErrors['nama']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control <?= isset($validationErrors['username']) ? 'is-invalid' : '' ?>"
                            placeholder="Masukkan username"
                            value="<?= esc(old('username')) ?>"
                            required>

                        <?php if (isset($validationErrors['username'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($validationErrors['username']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control <?= isset($validationErrors['password']) ? 'is-invalid' : '' ?>"
                            placeholder="Minimal 12 karakter"
                            required>

                        <?php if (isset($validationErrors['password'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($validationErrors['password']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirm"
                            class="form-control <?= isset($validationErrors['password_confirm']) ? 'is-invalid' : '' ?>"
                            placeholder="Ulangi password"
                            required>

                        <?php if (isset($validationErrors['password_confirm'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($validationErrors['password_confirm']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                    type="button"
                    class="btn btn-reset"
                    data-bs-dismiss="modal">
                    Batal
                    </button>

                    <button type="submit" class="btn btn-brand">
                    <i class="bi bi-check-circle me-1"></i>
                    Simpan Petugas
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
