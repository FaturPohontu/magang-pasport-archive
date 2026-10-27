```php
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="scan-page">

    <!-- =========================================
         HEADER HALAMAN
    ========================================== -->
    <div class="scan-page-header mb-4">

        <div class="scan-page-heading">

            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="scan-header-icon">
                    <i class="bi bi-upc-scan"></i>
                </span>

                <span class="text-uppercase small fw-semibold scan-eyebrow">
                    SIPASKANIM
                </span>
            </div>

            <h2 class="scan-page-title mb-2">
                Scan Resi Permohonan Paspor
            </h2>

            <p class="scan-page-description mb-0">
                Ambil foto resi, ekstrak informasi menggunakan OCR,
                kemudian periksa dan simpan data hasil pemindaian.
            </p>

        </div>

        <div class="scan-status-badge">
            <i class="bi bi-shield-check me-1"></i>
            Arsip Digital
        </div>

    </div>


    <!-- =========================================
         INDIKATOR TAHAP PROSES
    ========================================== -->
    <section class="scan-progress-section mb-4">

        <h5 class="scan-progress-title">
            Alur Proses Scan Resi
        </h5>

        <div class="scan-progress">

            <div class="scan-progress-step active" id="step1">
                <div class="scan-progress-number">1</div>
                <span>Ambil Foto</span>
            </div>

            <div class="scan-progress-line" id="line1"></div>

            <div class="scan-progress-step" id="step2">
                <div class="scan-progress-number">2</div>
                <span>Proses OCR</span>
            </div>

            <div class="scan-progress-line" id="line2"></div>

            <div class="scan-progress-step" id="step3">
                <div class="scan-progress-number">3</div>
                <span>Periksa Data</span>
            </div>

            <div class="scan-progress-line" id="line3"></div>

            <div class="scan-progress-step" id="step4">
                <div class="scan-progress-number">4</div>
                <span>Simpan Data</span>
            </div>

        </div>

    </section>


    <!-- =========================================
         SECTION 1: KAMERA DAN OCR
    ========================================== -->
    <section class="card scan-card mb-4">

        <div class="card-header scan-card-header">

            <div class="d-flex align-items-center gap-3">

                <div class="scan-card-icon">
                    <i class="bi bi-camera-fill"></i>
                </div>

                <div>
                    <h5 class="mb-1">
                        1. Ambil Foto Resi via Kamera
                    </h5>

                    <p class="mb-0">
                        Aktifkan kamera untuk mengambil gambar
                        resi permohonan paspor.
                    </p>
                </div>

            </div>

        </div>


        <div class="card-body p-3 p-md-4">

            <!-- AREA KAMERA -->
            <div class="scan-camera-wrapper mb-4">

                <!-- Placeholder sebelum kamera aktif -->
                <div
                    class="scan-camera-placeholder"
                    id="cameraPlaceholder"
                >

                    <div class="scan-placeholder-icon">
                        <i class="bi bi-camera-video"></i>
                    </div>

                    <h6>Kamera Belum Diaktifkan</h6>

                    <p class="mb-0">
                        Tekan tombol Buka Kamera untuk memulai
                        pengambilan foto.
                    </p>

                </div>


                <!-- Video kamera -->
                <video
                    id="webcam"
                    autoplay
                    playsinline
                    class="scan-webcam"
                    style="display: none;"
                ></video>


                <!-- Canvas untuk mengambil foto -->
                <canvas
                    id="canvas"
                    style="display: none;"
                ></canvas>


                <!-- Tombol ambil foto di dalam area kamera -->
                <button
                    type="button"
                    id="btnCapture"
                    class="scan-capture-button"
                    onclick="takeSnapshot()"
                    aria-label="Ambil Foto"
                    title="Ambil Foto"
                    style="display: none;"
                >
                    <i class="bi bi-camera-fill"></i>
                </button>

            </div>


            <!-- =====================================
                 PREVIEW HASIL FOTO
            ====================================== -->
            <div
                id="previewContainer"
                class="scan-preview-container mb-4"
                style="display: none;"
            >

                <div class="d-flex align-items-center gap-2 mb-3">

                    <i class="bi bi-image text-primary"></i>

                    <h6 class="mb-0 fw-semibold">
                        Hasil Foto
                    </h6>

                </div>

                <div class="scan-preview-image-wrapper">

                    <img
                        id="photoPreview"
                        class="scan-photo-preview"
                        alt="Hasil foto resi permohonan paspor"
                    >

                </div>

            </div>


            <!-- =====================================
                 TOMBOL AKSI KAMERA DAN OCR
            ====================================== -->
            <div class="scan-camera-actions d-flex flex-wrap gap-2">

                <!-- Buka atau matikan kamera -->
                <button
                    type="button"
                    id="btnStartCamera"
                    class="btn btn-primary scan-btn"
                    onclick="startCamera()"
                >
                    <i class="bi bi-camera-video me-2"></i>
                    Buka Kamera
                </button>


                <!-- Memulai OCR -->
                <button
                    type="button"
                    id="btnScan"
                    class="btn btn-warning scan-btn"
                    onclick="doOcrScanFromCamera()"
                    style="display: none;"
                >
                    <i class="bi bi-upc-scan me-2"></i>
                    Mulai Scan OCR
                </button>

            </div>


            <!-- =====================================
                 STATUS PEMROSESAN OCR
            ====================================== -->
            <div
                id="loading"
                class="scan-loading mt-4"
                style="display: none;"
            >

                <div
                    class="spinner-border spinner-border-sm text-primary"
                    role="status"
                >
                    <span class="visually-hidden">
                        Memproses...
                    </span>
                </div>

                <div>

                    <div class="fw-semibold">
                        Sedang memproses gambar
                    </div>

                    <small>
                        Memproses foto resi dengan Tesseract OCR...
                    </small>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================
         SECTION 2: VERIFIKASI DATA
    ========================================== -->
    <section class="card scan-card mb-4">

        <div class="card-header scan-card-header">

            <div class="d-flex align-items-center gap-3">

                <div class="scan-card-icon scan-card-icon-gold">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <div>

                    <h5 class="mb-1">
                        2. Verifikasi Data Hasil Scan
                    </h5>

                    <p class="mb-0">
                        Pastikan seluruh informasi sesuai dengan resi asli.
                    </p>

                </div>

            </div>

        </div>


        <div class="card-body p-3 p-md-4">

            <!-- Informasi verifikasi -->
            <div
                class="alert alert-primary scan-info-alert d-flex gap-3 align-items-start"
            >

                <i class="bi bi-info-circle-fill mt-1"></i>

                <div>

                    <div class="fw-semibold mb-1">
                        Periksa kembali hasil pemindaian
                    </div>

                    <small>
                        Periksa data di bawah ini. Lengkapi atau koreksi
                        jika pembacaan OCR kurang tepat.
                    </small>

                </div>

            </div>


            <!-- FORM VERIFIKASI DAN PENYIMPANAN -->
            <form
                action="<?= base_url('scan/save') ?>"
                method="post"
                id="scanForm"
            >

                <?= csrf_field() ?>


                <!-- BARIS DATA -->
                <div class="row g-4 mb-4">


                    <!-- KODE PERMOHONAN -->
                    <div class="col-12 col-md-6">

                        <label
                            for="kode_permohonan"
                            class="form-label scan-form-label"
                        >
                            1. Kode Permohonan
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="kode_permohonan"
                            id="kode_permohonan"
                            class="form-control scan-input"
                            inputmode="numeric"
                            placeholder="Masukkan kode permohonan"
                            required
                        >

                        <div class="form-text">
                            Masukkan kode permohonan sesuai resi.
                        </div>

                    </div>


                    <!-- NAMA BILLING -->
                    <div class="col-12 col-md-6">

                        <label
                            for="nama_billing"
                            class="form-label scan-form-label"
                        >
                            2. Nama Billing
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_billing"
                            id="nama_billing"
                            class="form-control scan-input"
                            placeholder="Masukkan nama billing"
                            required
                        >

                    </div>


                    <!-- TANGGAL LAHIR -->
                    <div class="col-12 col-md-6">

                        <label
                            for="tgl_lahir"
                            class="form-label scan-form-label"
                        >
                            3. Tanggal Lahir
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="tgl_lahir"
                            id="tgl_lahir"
                            class="form-control scan-input"
                            required
                        >

                    </div>


                    <!-- JENIS PERMOHONAN -->
                    <div class="col-12 col-md-6">

                        <label
                            for="jenis_permohonan"
                            class="form-label scan-form-label"
                        >
                            4. Jenis Permohonan
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="jenis_permohonan"
                            id="jenis_permohonan"
                            class="form-control scan-input"
                            placeholder="Contoh: BARU"
                            required
                        >

                    </div>


                    <!-- TANGGAL CETAK -->
                    <div class="col-12 col-md-6">

                        <label
                            for="dicetak_tanggal"
                            class="form-label scan-form-label"
                        >
                            5. Dicetak Tanggal
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="dicetak_tanggal"
                            id="dicetak_tanggal"
                            class="form-control scan-input"
                            required
                        >

                    </div>

                </div>


                <!-- =================================
                     HASIL TEKS MENTAH OCR
                ================================== -->
                <div class="scan-ocr-section mb-4">

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"
                    >

                        <div>

                            <label
                                for="raw_text"
                                class="form-label scan-form-label mb-1"
                            >
                                Hasil Teks Mentah OCR
                            </label>

                            <p class="text-muted small mb-0">
                                Teks yang dibaca oleh sistem dari gambar resi.
                            </p>

                        </div>

                        <span class="badge text-bg-secondary">
                            <i class="bi bi-file-text me-1"></i>
                            Read-only
                        </span>

                    </div>

                    <textarea
                        id="raw_text"
                        class="form-control scan-raw-text"
                        rows="6"
                        readonly
                        placeholder="Hasil pembacaan OCR akan ditampilkan di sini..."
                    ></textarea>

                </div>


                <!-- =================================
                     TOMBOL SIMPAN DATA
                ================================== -->
                <div class="scan-form-footer">

                    <div class="scan-required-note">
                        <span class="text-danger">*</span>
                        Wajib diisi
                    </div>

                    <button
                        type="submit"
                        class="btn btn-success scan-btn scan-save-btn"
                    >
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                        Simpan ke Spreadsheet
                    </button>

                </div>

            </form>

        </div>

    </section>


    <!-- =========================================
         FOOTER HALAMAN
    ========================================== -->
    <footer class="scan-page-footer text-center">

        <small>
            <i class="bi bi-shield-lock me-1"></i>
            SIPASKANIM — Sistem Arsip Paspor
        </small>

    </footer>

</div>


<!-- =============================================
     CSS HALAMAN SCAN
============================================== -->
<link
    rel="stylesheet"
    href="<?= base_url('css/scan.css') ?>?v=2"
>


<!-- =============================================
     JAVASCRIPT HALAMAN SCAN
============================================== -->
<script src="<?= base_url('js/scan.js') ?>?v=2"></script>


<?= $this->endSection() ?>