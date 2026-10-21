
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

    fetch('scan/process-ocr', {
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
