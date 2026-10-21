<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2>Scan Resi Permohonan Paspor</h2>

<!-- Form Scan Gambar -->
<!-- Section 1: Capture Gambar dari Kamera -->
<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
    <h3>1. Ambil Foto Resi via Kamera</h3>
    
    <!-- Tampilan Stream Kamera -->
    <div style="margin-bottom: 10px;">
        <video id="webcam" autoplay playsinline style="width: 100%; max-width: 400px; border: 1px solid #999; display: none;"></video>
        <canvas id="canvas" style="display: none;"></canvas>
    </div>

    <!-- Tampilan Preview Foto yang Diambil -->
    <div id="previewContainer" style="display: none; margin-bottom: 10px;">
        <p><strong>Hasil Foto:</strong></p>
        <img id="photoPreview" style="max-width: 400px; border: 1px solid #333;">
    </div>

    <!-- Tombol Aksi Kamera -->
    <div>
        <button type="button" id="btnStartCamera" onclick="startCamera()">Buka Kamera</button>
        <button type="button" id="btnCapture" onclick="takeSnapshot()" style="display: none;">Ambil Foto</button>
        <button type="button" id="btnScan" onclick="doOcrScanFromCamera()" style="display: none;">Mulai Scan OCR</button>
    </div>

    <div id="loading" style="display:none; color: blue; margin-top: 10px;">
        Memproses foto resi dengan Tesseract OCR...
    </div>
</div>

<!-- Form Verifikasi (Sesuai Urutan Kolom Spreadsheet) -->
<div style="border: 1px solid #ccc; padding: 15px;">
    <h3>2. Verifikasi Data Hasil Scan</h3>
    <p><small>*Periksa data di bawah ini. Lengkapi atau koreksi jika pembacaan OCR kurang tepat.</small></p>

    <form action="<?= base_url('scan/save') ?>" method="post">
        <?= csrf_field() ?>

        <!-- 1. Kode Permohonan -->
        <div>
            <label>1. Kode Permohonan (Angka):</label><br>
            <input type="text" name="kode_permohonan" id="kode_permohonan" required style="width: 350px;">
        </div><br>

        <!-- 2. Nama Billing -->
        <div>
            <label>2. Nama Billing:</label><br>
            <input type="text" name="nama_billing" id="nama_billing" required style="width: 350px;">
        </div><br>

        <!-- 3. Tanggal Lahir -->
        <div>
            <label>3. Tanggal Lahir:</label><br>
            <input type="date" name="tgl_lahir" id="tgl_lahir" required>
        </div><br>

        <!-- 4. Jenis Permohonan -->
        <div>
            <label>4. Jenis Permohonan:</label><br>
            <input type="text" name="jenis_permohonan" id="jenis_permohonan" placeholder="Contoh: BARU" required style="width: 350px;">
        </div><br>

        <!-- 5. Dicetak Tanggal -->
        <div>
            <label>5. Dicetak Tanggal:</label><br>
            <input type="date" name="dicetak_tanggal" id="dicetak_tanggal" required>
        </div><br>

        <!-- Mentahan Teks OCR -->
        <div>
            <label>Hasil Teks Mentah OCR:</label><br>
            <textarea id="raw_text" rows="5" style="width: 100%;" readonly></textarea>
        </div><br>

        <button type="submit">Simpan ke Spreadsheet</button>
    </form>
</div>

<script src="<?= base_url('js/scan.js') ?>"></script>

<?= $this->endSection() ?>


