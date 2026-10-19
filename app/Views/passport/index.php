<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2>Scan Resi Paspor & Verifikasi Data</h2>

<!-- Section 1: Upload Image -->
<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
    <h3>1. Upload & Scan Gambar</h3>
    <form id="formOcr" enctype="multipart/form-data">
        <input type="file" name="passport_image" accept="image/*" required>
        <button type="button" id="btnScan" onclick="doOcrScan()">Mulai Scan OCR</button>
    </form>
    <div id="loading" style="display:none; color: blue; margin-top: 10px;">
        Sedang memproses Tesseract OCR, mohon tunggu...
    </div>
</div>

<!-- Section 2: Form Verifikasi & Input Manual -->
<div style="border: 1px solid #ccc; padding: 15px;">
    <h3>2. Verifikasi / Isian Manual Data Paspor</h3>
    <p><small>*Periksa kembali data berikut. Lengkapi secara manual jika terdapat informasi resi yang kurang jelas.</small></p>[cite: 1]

    <form action="<?= base_url('scan/save') ?>" method="post">
        <?= csrf_field() ?>

        <div>
            <label>No. Paspor / Resi:</label><br>
            <input type="text" name="nik_paspor" id="nik_paspor" required style="width: 300px;">
        </div><br>

        <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="nama" id="nama" required style="width: 300px;">
        </div><br>

        <div>
            <label>Kewarganegaraan:</label><br>
            <select name="kewarganegaraan" id="kewarganegaraan" required>
                <option value="WNI">WNI</option>
                <option value="WNA">WNA</option>
            </select>
        </div><br>

        <div>
            <label>Tanggal Lahir:</label><br>
            <input type="date" name="tgl_lahir" id="tgl_lahir">
        </div><br>

        <div>
            <label>Mentahan Teks OCR (Untuk Referensi):</label><br>
            <textarea id="raw_text" rows="4" style="width: 100%;" readonly></textarea>
        </div><br>

        <button type="submit">Simpan Data Ke Spreadsheet</button>
    </form>
</div>

<script>
function doOcrScan() {
    let form = document.getElementById('formOcr');
    let formData = new FormData(form);
    let loading = document.getElementById('loading');
    let btnScan = document.getElementById('btnScan');

    loading.style.display = 'block';
    btnScan.disabled = true;

    fetch('<?= base_url('scan/process-ocr') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(res => {
        loading.style.display = 'none';
        btnScan.disabled = false;

        if (res.status === 'success') {
            // Otomatis inject data hasil scan Tesseract ke input form verifikasi
            document.getElementById('nik_paspor').value = res.data.nik_paspor || '';
            document.getElementById('nama').value = res.data.nama || '';
            document.getElementById('kewarganegaraan').value = res.data.kewarganegaraan || 'WNI';
            document.getElementById('tgl_lahir').value = res.data.tgl_lahir || '';
            document.getElementById('raw_text').value = res.data.raw_text || '';
            
            alert('Scan OCR Berhasil. Silakan verifikasi dan lengkapi data jika ada yang kurang.');
        } else {
            alert(res.message);
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        btnScan.disabled = false;
        alert('Terjadi kesalahan koneksi atau server OCR.');
    });
}
</script>
<?= $this->endSection() ?>