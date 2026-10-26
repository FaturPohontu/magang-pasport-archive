let videoStream = null;
let capturedBlob = null;

// ========================================
// 1. MEMBUKA KAMERA
// ========================================
async function startCamera() {
    const video = document.getElementById('webcam');
    const btnStart = document.getElementById('btnStartCamera');
    const btnCapture = document.getElementById('btnCapture');
    const placeholder = document.getElementById('cameraPlaceholder');

    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment',
                width: { ideal: 1920 },
                height: { ideal: 1080 }
            },
            audio: false
        });

        video.srcObject = videoStream;
        video.style.display = 'block';
        video.style.objectFit = 'contain';

        await video.play();

        if (placeholder) {
            placeholder.style.display = 'none';
        }

        btnStart.innerHTML =
            '<i class="bi bi-camera-video-off me-2"></i>Matikan Kamera';

        btnStart.onclick = stopCamera;

        // Tampilkan tombol bulat di area kamera
        btnCapture.style.display = 'flex';

    } catch (error) {
        console.error('Gagal membuka kamera:', error);

        if (videoStream) {
            videoStream.getTracks().forEach(function (track) {
                track.stop();
            });

            videoStream = null;
        }

        alert(
            'Kamera gagal dibuka. Periksa izin kamera dan pastikan situs menggunakan HTTPS atau localhost.'
        );
    }
}


// ========================================
// 2. MEMATIKAN KAMERA
// ========================================
function stopCamera() {
    const video = document.getElementById('webcam');
    const btnStart = document.getElementById('btnStartCamera');
    const btnCapture = document.getElementById('btnCapture');
    const placeholder = document.getElementById('cameraPlaceholder');

    if (videoStream) {
        videoStream.getTracks().forEach(function (track) {
            track.stop();
        });

        videoStream = null;
    }

    video.srcObject = null;
    video.style.display = 'none';

    if (placeholder) {
        placeholder.style.display = 'flex';
    }

    btnStart.innerHTML =
        '<i class="bi bi-camera-video me-2"></i>Buka Kamera';

    btnStart.onclick = startCamera;

    btnCapture.style.display = 'none';
}


// ========================================
// 3. MENGAMBIL FOTO
// ========================================
function takeSnapshot() {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photoPreview');
    const previewContainer = document.getElementById('previewContainer');
    const btnScan = document.getElementById('btnScan');

    if (!video.videoWidth || !video.videoHeight) {
        alert('Kamera belum siap. Silakan tunggu sebentar.');
        return;
    }

    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    context.imageSmoothingEnabled = true;
    context.imageSmoothingQuality = 'high';

    context.drawImage(
        video,
        0,
        0,
        canvas.width,
        canvas.height
    );

    canvas.toBlob(function (blob) {
        if (!blob) {
            alert('Gagal mengambil foto. Silakan coba kembali.');
            return;
        }

        capturedBlob = blob;

        photoPreview.src = URL.createObjectURL(blob);
        previewContainer.style.display = 'block';
        btnScan.style.display = 'inline-block';

    }, 'image/jpeg', 0.95);
}


// ========================================
// 4. MEMPROSES OCR
// ========================================
function doOcrScanFromCamera() {
    if (!capturedBlob) {
        alert('Silakan ambil foto terlebih dahulu!');
        return;
    }

    const formData = new FormData();

    formData.append(
        'passport_image',
        capturedBlob,
        'captured_receipt.jpg'
    );

    const loading = document.getElementById('loading');
    const btnScan = document.getElementById('btnScan');

    loading.style.display = 'block';
    btnScan.disabled = true;

    fetch('scan/process-ocr', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function (response) {
        if (!response.ok) {
            throw new Error('HTTP error: ' + response.status);
        }

        return response.json();
    })
    .then(function (res) {
        if (res.status === 'success') {
            document.getElementById('kode_permohonan').value =
                res.data.kode_permohonan || '';

            document.getElementById('nama_billing').value =
                res.data.nama_billing || '';

            document.getElementById('tgl_lahir').value =
                res.data.tgl_lahir || '';

            document.getElementById('jenis_permohonan').value =
                res.data.jenis_permohonan || '';

            document.getElementById('dicetak_tanggal').value =
                res.data.dicetak_tanggal || '';

            document.getElementById('raw_text').value =
                res.data.raw_text || '';

            alert('Scan kamera selesai! Silakan periksa data.');

        } else {
            alert(res.message || 'Proses OCR gagal.');
        }
    })
    .catch(function (error) {
        console.error('Kesalahan OCR:', error);

        alert(
            'Terjadi kesalahan saat mengirim foto atau memproses OCR.'
        );
    })
    .finally(function () {
        loading.style.display = 'none';
        btnScan.disabled = false;
    });
}