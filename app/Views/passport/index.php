<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2>Scan Resi Paspor & Input Data</h2>

<!-- 1. Form Upload & Scan OCR -->
<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
    <h3>1. Scan Resi Paspor</h3>
    <form id="ocrForm" enctype="multipart/form-data">
        <input type="file" name="passport_image" accept="image/*" required>
        <button type="button" onclick="processOCR()">Proses OCR</button>
    </form>
</div>

<!-- 2. Form Validasi Data / Input Manual -->
<div style="border: 1px solid #ccc; padding: 15px;">
    <h3>2. Validasi & Isi Data Paspor</h3>
    <form action="<?= base_url('scan/save') ?>" method="post">
        <?= csrf_field() ?>
        
        <div>
            <label>No. Paspor / Resi:</label><br>
            <input type="text" name="nik_paspor" id="nik_paspor" required>
        </div><br>

        <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="nama" id="nama" required>
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

        <button type="submit">Simpan ke Spreadsheet</button>
    </form>
</div>

<script>
function processOCR() {
    let formData = new FormData(document.getElementById('ocrForm'));
    
    fetch('<?= base_url('scan/process-ocr') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            // Otomatis isi form validasi dengan hasil scan
            document.getElementById('nik_paspor').value = result.data.nik_paspor;
            document.getElementById('nama').value = result.data.nama;
            document.getElementById('kewarganegaraan').value = result.data.kewarganegaraan;
            document.getElementById('tgl_lahir').value = result.data.tgl_lahir;
            alert('Proses OCR Selesai! Silakan periksa kembali data.');
        } else {
            alert(result.message);
        }
    })
    .catch(error => alert('Terjadi kesalahan proses OCR.'));
}
</script>
<?= $this->endSection() ?>