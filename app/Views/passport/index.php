<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    rel="stylesheet"
>

<link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/scan.css') ?>">

<div class="sipascanim-screen">


<div class="scan-app-body">

        <!-- AREA UPLOAD FOTO -->
        <section class="scan-upload-section">

            <input
                type="file"
                id="imageUpload"
                accept="image/*"
                hidden
            >

            <div
                class="scan-dropzone"
                id="dropZone"
                role="button"
                tabindex="0"
                aria-label="Pilih atau jatuhkan gambar resi"
            >
                <div class="dropzone-content" id="dropzoneContent">
                    <div class="upload-icon">
                        <i class="bi bi-upload"></i>
                    </div>

                    <p>Drop gambar di sini atau klik untuk pilih file</p>
                    <small>Format gambar: JPG, JPEG, atau PNG</small>
                </div>

                <img
                    id="uploadPreview"
                    class="upload-preview"
                    alt="Pratinjau resi yang dipilih"
                    hidden
                >

                <video
                    id="webcam"
                    class="scan-webcam"
                    autoplay
                    playsinline
                    style="display: none;"
                ></video>

                <canvas id="canvas" hidden></canvas>
            </div>

            <div id="previewContainer" class="camera-preview-info" style="display:none;">
                <i class="bi bi-check-circle-fill"></i>
                <span>Foto siap diproses</span>
            </div>

            <div class="scan-action-buttons">

                <button
                    type="button"
                    id="btnStartCamera"
                    class="scan-btn scan-btn-camera"
                    onclick="startCamera()"
                >
                    <i class="bi bi-camera"></i>
                    <span>Buka Kamera</span>
                </button>

                <button
                    type="button"
                    id="btnUpload"
                    class="scan-btn scan-btn-upload"
                    onclick="document.getElementById('imageUpload').click()"
                >
                    <i class="bi bi-upload"></i>
                    <span>Upload Foto</span>
                </button>

                <button
                    type="button"
                    id="btnCapture"
                    class="scan-btn scan-btn-capture"
                    onclick="takeSnapshot()"
                    style="display:none;"
                >
                    <i class="bi bi-camera-fill"></i>
                    <span>Ambil Foto</span>
                </button>

                <button
                    type="button"
                    id="btnScan"
                    class="scan-btn scan-btn-ocr"
                    onclick="doOcrScanFromCamera()"
                    disabled
                >
                    <i class="bi bi-image"></i>
                    <span>Proses OCR</span>
                </button>

            </div>

            <div id="loading" class="scan-loading" style="display:none;">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span>Memproses gambar dengan Tesseract OCR...</span>
            </div>

        </section>

        <!-- DATA LAPORAN -->
        <section class="scan-report-card">

            <div class="scan-report-header">
                <i class="bi bi-people"></i>
                <h2>Data Laporan</h2>
            </div>

            <form action="<?= base_url('scan/save') ?>" method="post"
                  class="scan-report-form">
                <?= csrf_field() ?>

                <div class="scan-field">
                    <label for="kode_permohonan">KODE</label>
                    <input
                        type="text"
                        class="form-control scan-input"
                        name="kode_permohonan"
                        id="kode_permohonan"
                        inputmode="numeric"
                        required
                    >
                </div>

                <div class="scan-field">
                    <label for="nama_billing">NAMA</label>
                    <input
                        type="text"
                        class="form-control scan-input"
                        name="nama_billing"
                        id="nama_billing"
                        required
                    >
                </div>

                <div class="scan-form-grid">

                    <div class="scan-field">
                        <label for="tgl_lahir">TANGGAL LAHIR</label>
                        <input
                            type="date"
                            class="form-control scan-input"
                            name="tgl_lahir"
                            id="tgl_lahir"
                            required
                        >
                    </div>

                    <div class="scan-field">
                        <label>JENIS PERMOHONAN</label>

                        <div class="scan-type-buttons">
                            <button
                                type="button"
                                class="scan-type-btn active"
                                data-value="BARU"
                            >
                                BARU
                            </button>

                            <button
                                type="button"
                                class="scan-type-btn"
                                data-value="PENGGANTIAN"
                            >
                                PENGGANTIAN
                            </button>
                        </div>

                        <input
                            type="hidden"
                            name="jenis_permohonan"
                            id="jenis_permohonan"
                            value="BARU"
                            required
                        >
                    </div>

                </div>

                <div class="scan-bottom-grid">

                    <div class="scan-field">
                        <label for="dicetak_tanggal">TANGGAL BERKUNJUNG</label>
                        <input
                            type="date"
                            class="form-control scan-input"
                            name="dicetak_tanggal"
                            id="dicetak_tanggal"
                            required
                        >
                    </div>

                    <button type="submit" class="scan-save-btn">
                        SIMPAN
                    </button>

                </div>

                <!-- Teks mentah OCR tetap tersedia -->
                <div class="scan-raw-section">
                    <label for="raw_text">HASIL TEKS OCR</label>
                    <textarea
                        id="raw_text"
                        class="form-control scan-raw-text"
                        rows="4"
                        placeholder="Hasil OCR akan muncul di sini..."
                        readonly
                    ></textarea>
                </div>

            </form>
        </section>

    </div>
</div>

<script src="<?= base_url('js/scan.js') ?>"></script>

<!-- Interaksi upload dan pilihan jenis permohonan -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('imageUpload');
    const dropZone = document.getElementById('dropZone');
    const dropzoneContent = document.getElementById('dropzoneContent');
    const uploadPreview = document.getElementById('uploadPreview');
    const btnScan = document.getElementById('btnScan');
    const typeInput = document.getElementById('jenis_permohonan');
    const typeButtons = document.querySelectorAll('.scan-type-btn');

    let previewUrl = null;

    function showImage(file) {
        if (!file || !file.type.startsWith('image/')) {
            alert('Silakan pilih file gambar seperti JPG, JPEG, atau PNG.');
            return;
        }

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }

        previewUrl = URL.createObjectURL(file);
        uploadPreview.src = previewUrl;
        uploadPreview.hidden = false;
        dropzoneContent.style.display = 'none';

        // Upload foto sudah dipilih, tetapi fungsi OCR harus
        // mendukung sumber gambar upload agar tombol ini aktif.
        btnScan.disabled = true;

        const info = document.querySelector('.camera-preview-info');
        info.style.display = 'flex';
        info.innerHTML =
            '<i class="bi bi-check-circle-fill"></i>' +
            '<span>Foto dipilih. Siap untuk diproses.</span>';

        if (typeof window.setUploadedImage === 'function') {
            window.setUploadedImage(file);
        }
    }

    fileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            showImage(this.files[0]);
        }
    });

    dropZone.addEventListener('click', function (event) {
        if (event.target.closest('button')) return;
        fileInput.click();
    });

    dropZone.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            fileInput.click();
        }
    });

    ['dragenter', 'dragover'].forEach(function (eventName) {
        dropZone.addEventListener(eventName, function (event) {
            event.preventDefault();
            dropZone.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(function (eventName) {
        dropZone.addEventListener(eventName, function (event) {
            event.preventDefault();
            dropZone.classList.remove('drag-over');
        });
    });

    dropZone.addEventListener('drop', function (event) {
        const files = event.dataTransfer.files;

        if (files && files[0]) {
            showImage(files[0]);
        }
    });

    typeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            typeButtons.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');
            typeInput.value = this.dataset.value;
        });
    });
});
</script>

<?= $this->endSection() ?>