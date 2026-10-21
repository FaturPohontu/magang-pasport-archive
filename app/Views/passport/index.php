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

<script>
let videoStream = null;
let capturedBlob = null;

async function startCamera() {
    const video = document.getElementById('webcam');
    const btnStart = document.getElementById('btnStartCamera');
    
    // Konfigurasi kamera agar menggunakan kamera utama (bukan ultra-wide) dengan resolusi HD
    const constraints = {
        video: {
            facingMode: { exact: "environment" }, // Pakai kamera belakang utama
            width: { ideal: 400 },
            height: { ideal: 400 },
            advanced: [{ focusMode: "continuous" }] // Pakai auto-focus kontinu jika didukung
        },
        audio: false
    };

    try {
        videoStream = await navigator.mediaDevices.getUserMedia(constraints);
    } catch (err) {
        // Fallback jika HP tidak mendukung aturan 'exact: environment'
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: { 
                    facingMode: 'environment',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            });
        } catch (fallbackErr) {
            console.error("Gagal mengakses kamera:", fallbackErr);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan dan server menggunakan HTTPS.');
            return;
        }
    }

    video.srcObject = videoStream;
    
    // Terapkan styling agar tampilan tidak distorsi/tertarik
    video.style.display = 'block';
    video.style.objectFit = 'contain'; 

    btnStart.innerText = 'Matikan Kamera';
    btnStart.onclick = stopCamera;
    document.getElementById('btnCapture').style.display = 'inline-block';
}

function stopCamera() {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    
    const video = document.getElementById('webcam');
    video.style.display = 'none';
    
    const btnStart = document.getElementById('btnStartCamera');
    btnStart.innerText = 'Buka Kamera';
    btnStart.onclick = startCamera;
    
    document.getElementById('btnCapture').style.display = 'none';
}

function takeSnapshot() {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const photoPreview = document.getElementById('photoPreview');
    const previewContainer = document.getElementById('previewContainer');
    const btnScan = document.getElementById('btnScan');

    // Ambil resolusi asli dari video stream
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Gambar frame video dengan kualitas tinggi
    context.imageSmoothingEnabled = true;
    context.imageSmoothingQuality = 'high';
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Simpan dalam format JPEG dengan kualitas 95%
    canvas.toBlob(function(blob) {
        capturedBlob = blob;
        
        photoPreview.src = URL.createObjectURL(blob);
        previewContainer.style.display = 'block';
        btnScan.style.display = 'inline-block';
    }, 'image/jpeg', 0.95);
}

function doOcrScanFromCamera() {
    if (!capturedBlob) {
        alert('Silakan ambil foto terlebih dahulu!');
        return;
    }

    let formData = new FormData();
    formData.append('passport_image', capturedBlob, 'captured_receipt.jpg');

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
            document.getElementById('kode_permohonan').value = res.data.kode_permohonan || '';
            document.getElementById('nama_billing').value    = res.data.nama_billing || '';
            document.getElementById('tgl_lahir').value       = res.data.tgl_lahir || '';
            document.getElementById('jenis_permohonan').value= res.data.jenis_permohonan || '';
            document.getElementById('dicetak_tanggal').value = res.data.dicetak_tanggal || '';
            document.getElementById('raw_text').value        = res.data.raw_text || '';
            
            alert('Scan kamera selesai! Silakan periksa data.');
        } else {
            alert(res.message);
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        btnScan.disabled = false;
        alert('Terjadi kesalahan koneksi saat mengirim foto kamera.');
    });
}
</script>
<?= $this->endSection() ?>