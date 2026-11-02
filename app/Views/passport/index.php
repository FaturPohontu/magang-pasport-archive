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
                PREVIEW FOTO SEMENTARA
            ====================================== -->
            <div
                id="pendingPreviewContainer"
                class="border rounded-3 p-3 mb-4"
                style="display: none;"
            >
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-eye text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Periksa Foto Sebelum Konfirmasi</h6>
                </div>

                <div class="text-center mb-3">
                    <img
                        id="pendingPhotoPreview"
                        src=""
                        alt="Preview foto resi"
                        class="img-fluid rounded border"
                        style="
                            max-height: 350px;
                            width: 100%;
                            object-fit: contain;
                            background: #f8f9fa;
                        "
                    >
                </div>

                <p class="text-muted small mb-3">
                    Pastikan tulisan terlihat jelas, gambar tidak buram,
                    dan seluruh bagian resi masuk ke dalam foto.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="btn btn-success"
                        onclick="confirmCapturedPhoto()"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Konfirmasi Foto
                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        onclick="retakePhoto()"
                    >
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Retake / Ambil Ulang
                    </button>
                </div>
            </div>

            <div class="mt-3">
                <span id="photoCounter" class="badge bg-info text-dark p-2">Foto Terkumpul: 0/5</span>
            </div>

            <!-- =====================================
                 PREVIEW HASIL FOTO
            ====================================== -->
            <div id="previewContainer" class="scan-preview-container mb-4" style="display: none;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-image text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Daftar Foto Resi (Maksimal 5)</h6>
                </div>
            <!-- Bungkus thumbnail foto -->
                <div id="thumbnailList" class="d-flex flex-wrap gap-2"></div>
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
     SECTION 2: VERIFIKASI DATA (TABEL INTERAKTIF)
     ========================================= -->
<section class="card scan-card mb-4">

    <div class="card-header scan-card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="scan-card-icon scan-card-icon-gold">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
                <h5 class="mb-1">
                    2. Verifikasi Data Hasil Scan (Tabel Editable)
                </h5>
                <p class="mb-0">
                    Periksa dan edit langsung data di bawah ini sebelum disimpan ke Spreadsheet.
                </p>
            </div>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">

        <!-- Informasi verifikasi -->
        <div class="alert alert-primary scan-info-alert d-flex gap-3 align-items-start mb-3">
            <i class="bi bi-info-circle-fill mt-1"></i>
            <div>
                <div class="fw-semibold mb-1">
                    Tabel Validasi Batch OCR
                </div>
                <small>
                    Hasil OCR dari foto-foto yang Anda ambil akan muncul di sini dalam bentuk tabel. Anda dapat mengoreksi langsung sel teks yang kurang sesuai.
                </small>
            </div>
        </div>

        <!-- CONTAINER KOSONG TEMPAT TABEL HASIL OCR AKAN MUNCUL -->
        <div id="container-tabel-validasi">
            <p class="text-muted text-center py-4 fst-italic">
                Belum ada data. Silakan ambil foto dan jalankan proses OCR terlebih dahulu.
            </p>
        </div>

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
<script src="<?= base_url('js/scan.js') ?>?v=3"></script>


<?= $this->endSection() ?>