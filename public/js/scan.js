
let videoStream = null;
let capturedImages = []; // Hanya foto yang sudah dikonfirmasi

// Foto yang baru diambil, belum dikonfirmasi
let pendingPhoto = null;
let pendingPhotoURL = null;


// ========================================
// 1. MEMBUKA KAMERA
// ========================================

async function startCamera(deviceId = null) {
    const video = document.getElementById('webcam');
    const btnStart = document.getElementById('btnStartCamera');
    const btnCapture = document.getElementById('btnCapture');
    const btnScan = document.getElementById('btnScan');
    const placeholder = document.getElementById('cameraPlaceholder');
    const cameraSelect = document.getElementById('cameraSelect');
    const cameraDebug = document.getElementById('cameraDebug');

    if (!deviceId) {
        deviceId = localStorage.getItem('kamera_utama');
    }

    try {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }

        const videoConstraints = deviceId
            ? {
                deviceId: { exact: deviceId },
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
            : {
                facingMode: { ideal: "environment" },
                width: { ideal: 1280 },
                height: { ideal: 720 }
            };

        videoStream = await navigator.mediaDevices.getUserMedia({
            video: videoConstraints,
            audio: false
        });

        const track = videoStream.getVideoTracks()[0];
        const settings = track.getSettings();

        console.log("Kamera aktif:", track.label);
        console.log("Device ID:", settings.deviceId);
        console.log("Resolusi:", settings.width, settings.height);

        if (cameraDebug) {
            cameraDebug.textContent =
                `Kamera aktif: ${track.label}\n` +
                `Resolusi: ${settings.width} x ${settings.height}\n` +
                `Device ID: ${settings.deviceId}`;
        }

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

        // Tombol ambil foto hanya aktif jika tidak ada foto sementara
        btnCapture.style.display =
            (!pendingPhoto && capturedImages.length < 5) ? 'flex' : 'none';

        btnScan.style.display =
            (capturedImages.length > 0 && !pendingPhoto) ? 'inline-block' : 'none';

        const devices = await navigator.mediaDevices.enumerateDevices();
        const cameras = devices.filter(device => device.kind === 'videoinput');

        if (cameraSelect) {
            const currentDeviceId = settings.deviceId;
            cameraSelect.innerHTML = '';

            cameras.forEach((camera, index) => {
                const option = document.createElement('option');
                option.value = camera.deviceId;
                option.textContent = camera.label || `Kamera ${index + 1}`;

                if (camera.deviceId === currentDeviceId) {
                    option.selected = true;
                }

                cameraSelect.appendChild(option);
            });

            cameraSelect.onchange = function () {
                if (this.value) {
                    localStorage.setItem('kamera_utama', this.value);
                    startCamera(this.value);
                }
            };
        }

    } catch (error) {
        console.error('Gagal membuka kamera:', error);

        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }

        alert('Gagal membuka kamera: ' + error.message);
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
        videoStream.getTracks().forEach(track => track.stop());
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

    if (!video.videoWidth || !video.videoHeight) {
        alert('Kamera belum siap. Silakan tunggu sebentar.');
        return;
    }

    if (pendingPhoto) {
        alert('Konfirmasi atau retake foto sebelumnya terlebih dahulu.');
        return;
    }

    if (capturedImages.length >= 5) {
        alert('Maksimal 5 foto telah tercapai.');
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

        // Simpan sementara, belum masuk capturedImages
        pendingPhoto = blob;
        pendingPhotoURL = URL.createObjectURL(blob);

        const previewContainer = document.getElementById('pendingPreviewContainer');
        const previewImage = document.getElementById('pendingPhotoPreview');
        const btnCapture = document.getElementById('btnCapture');
        const btnScan = document.getElementById('btnScan');

        previewImage.src = pendingPhotoURL;
        previewContainer.style.display = 'block';

        // Sembunyikan tombol ambil foto sampai foto diputuskan
        btnCapture.style.display = 'none';

        // OCR tidak boleh dimulai sebelum foto dikonfirmasi
        btnScan.style.display = 'none';

    }, 'image/jpeg', 0.95);
}


// ========================================
// 4. KONFIRMASI FOTO
// ========================================

function confirmCapturedPhoto() {
    if (!pendingPhoto) {
        alert('Tidak ada foto yang menunggu konfirmasi.');
        return;
    }

    if (capturedImages.length >= 5) {
        alert('Maksimal 5 foto telah tercapai.');
        discardPendingPhoto();
        return;
    }

    // Pindahkan foto sementara ke daftar foto siap OCR
    capturedImages.push(pendingPhoto);

    const confirmedBlob = pendingPhoto;
    clearPendingPhotoPreview();

    // Tampilkan thumbnail foto yang sudah dikonfirmasi
    addConfirmedThumbnail(confirmedBlob, capturedImages.length);

    updatePhotoCounterUI();

    const btnCapture = document.getElementById('btnCapture');
    const btnScan = document.getElementById('btnScan');

    if (capturedImages.length < 5 && videoStream) {
        btnCapture.style.display = 'flex';
    } else {
        btnCapture.style.display = 'none';
    }

    btnScan.style.display = 'inline-block';

    alert(`Foto berhasil dikonfirmasi. Total foto: ${capturedImages.length}/5`);
}


// ========================================
// 5. RETAKE FOTO
// ========================================

function retakePhoto() {
    if (!pendingPhoto) {
        return;
    }

    // Buang foto sementara
    discardPendingPhoto();

    const btnCapture = document.getElementById('btnCapture');
    const btnScan = document.getElementById('btnScan');

    if (videoStream && capturedImages.length < 5) {
        btnCapture.style.display = 'flex';
    }

    btnScan.style.display =
        capturedImages.length > 0 ? 'inline-block' : 'none';
}


// Menghapus foto sementara dan URL preview
function discardPendingPhoto() {
    clearPendingPhotoPreview();
}


// Membersihkan preview sementara
function clearPendingPhotoPreview() {
    const previewContainer = document.getElementById('pendingPreviewContainer');
    const previewImage = document.getElementById('pendingPhotoPreview');

    if (pendingPhotoURL) {
        URL.revokeObjectURL(pendingPhotoURL);
        pendingPhotoURL = null;
    }

    pendingPhoto = null;

    if (previewImage) {
        previewImage.removeAttribute('src');
    }

    if (previewContainer) {
        previewContainer.style.display = 'none';
    }
}


// ========================================
// 6. MENAMPILKAN THUMBNAIL FOTO TERKONFIRMASI
// ========================================

function addConfirmedThumbnail(blob, number) {
    const previewContainer = document.getElementById('previewContainer');
    const thumbnailList = document.getElementById('thumbnailList');

    if (!previewContainer || !thumbnailList) {
        return;
    }

    const imageURL = URL.createObjectURL(blob);

    const card = document.createElement('div');
    card.className = 'border rounded p-2 text-center';
    card.style.width = '140px';

    const image = document.createElement('img');
    image.src = imageURL;
    image.alt = `Foto resi ${number}`;
    image.className = 'img-fluid rounded mb-2';
    image.style.width = '100%';
    image.style.height = '90px';
    image.style.objectFit = 'contain';

    const caption = document.createElement('small');
    caption.className = 'd-block fw-semibold text-success';
    caption.textContent = `Foto ${number} - Terkonfirmasi`;

    card.appendChild(image);
    card.appendChild(caption);

    thumbnailList.appendChild(card);
    previewContainer.style.display = 'block';
}


// ========================================
// 7. COUNTER FOTO
// ========================================

function updatePhotoCounterUI() {
    const counterEl = document.getElementById('photoCounter');

    if (counterEl) {
        counterEl.textContent =
            `Foto Terkumpul: ${capturedImages.length}/5`;
    }
}


// ========================================
// 8. MEMPROSES OCR
// ========================================

function doOcrScanFromCamera() {
    if (pendingPhoto) {
        alert('Silakan konfirmasi atau retake foto terlebih dahulu.');
        return;
    }

    if (capturedImages.length === 0) {
        alert('Silakan ambil dan konfirmasi minimal 1 foto terlebih dahulu!');
        return;
    }

    const formData = new FormData();

    capturedImages.forEach((blob, index) => {
        formData.append(
            'passport_images[]',
            blob,
            `receipt_${index + 1}.jpg`
        );
    });

    const loading = document.getElementById('loading');
    const btnScan = document.getElementById('btnScan');
    const btnCapture = document.getElementById('btnCapture');

    loading.style.display = 'block';
    btnScan.disabled = true;
    btnCapture.disabled = true;

    fetch('scan/process-batch', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error: ' + response.status);
        }

        return response.json();
    })
    .then(res => {
        if (res.status === 'success') {
            renderEditableTable(res.data);
            alert('Scan selesai! Silakan periksa dan koreksi data hasil OCR.');
        } else {
            alert(res.message || 'Proses OCR gagal.');
        }
    })
    .catch(error => {
        console.error('Kesalahan OCR:', error);
        alert('Terjadi kesalahan saat mengirim foto atau memproses OCR.');
    })
    .finally(() => {
        loading.style.display = 'none';
        btnScan.disabled = false;
        btnCapture.disabled = false;
    });
}


// ========================================
// 9. ESCAPE HTML UNTUK NILAI OCR
// ========================================

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, character => {
        const entities = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return entities[character];
    });
}


// ========================================
// 10. TABEL EDITABLE HASIL OCR
// ========================================

function renderEditableTable(dataList) {
    const container = document.getElementById('container-tabel-validasi');

    if (!container) return;

    let html = `
        <div class="table-responsive mt-4">
            <h5 class="mb-3">Validasi Data Hasil OCR (Dapat Diedit Langsung)</h5>

            <table class="table table-bordered table-striped" id="tableValidasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Permohonan</th>
                        <th>Nama Billing</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Permohonan</th>
                        <th>Dicetak Tanggal</th>
                    </tr>
                </thead>
                <tbody>
    `;

    dataList.forEach((item, index) => {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td><input type="text" class="form-control form-control-sm val-kode" value="${escapeHtml(item.kode_permohonan)}"></td>
                <td><input type="text" class="form-control form-control-sm val-nama" value="${escapeHtml(item.nama_billing)}"></td>
                <td><input type="text" class="form-control form-control-sm val-tgl" value="${escapeHtml(item.tgl_lahir)}"></td>
                <td><input type="text" class="form-control form-control-sm val-jenis" value="${escapeHtml(item.jenis_permohonan)}"></td>
                <td><input type="text" class="form-control form-control-sm val-cetak" value="${escapeHtml(item.dicetak_tanggal)}"></td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>

            <button type="button" class="btn btn-success mt-2"
                onclick="saveAllToSheetsFromTable()">
                Simpan Data
            </button>
        </div>
    `;

    container.innerHTML = html;
}


// ========================================
// 11. MENYIMPAN DATA HASIL VERIFIKASI
// ========================================

function saveAllToSheetsFromTable() {
    const rows = document.querySelectorAll('#tableValidasi tbody tr');
    const updatedDataRows = [];

    rows.forEach(row => {
        updatedDataRows.push({
            kode_permohonan: row.querySelector('.val-kode').value,
            nama_billing: row.querySelector('.val-nama').value,
            tgl_lahir: row.querySelector('.val-tgl').value,
            jenis_permohonan: row.querySelector('.val-jenis').value,
            dicetak_tanggal: row.querySelector('.val-cetak').value
        });
    });

    fetch('scan/save-batch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ rows: updatedDataRows })
    })
    .then(response => response.json())
    .then(res => {
        alert(res.message || 'Proses penyimpanan selesai.');

        if (res.status === 'success' || res.status === 'partial') {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error save batch:', error);
        alert('Terjadi kesalahan saat menyimpan data.');
    });
}
