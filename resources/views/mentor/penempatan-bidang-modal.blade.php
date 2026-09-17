<!-- Modal Penempatan Bidang -->
<div class="modal fade" id="penempatanBidangModal" tabindex="-1" role="dialog" aria-labelledby="penempatanBidangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                <h5 class="modal-title" id="penempatanBidangModalLabel">
                    <i class="fas fa-building me-2"></i>Penempatan Bidang Siswa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="placementAlertContainer"></div>

                <!-- Student Info Section -->
                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading">
                        <i class="fas fa-user me-2"></i>Informasi Siswa
                    </h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Nama:</strong> <span id="modalNamaSiswa">-</span></p>
                            <p class="mb-0"><strong>NIM/NIS:</strong> <span id="modalNimSiswa">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Asal Sekolah:</strong> <span id="modalAsalSekolah">-</span></p>
                            <p class="mb-0"><strong>Jurusan:</strong> <span id="modalJurusan">-</span></p>
                        </div>
                    </div>
                </div>

                <!-- Bidang Info Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><strong>Bidang Pilihan Awal</strong></label>
                        <div class="alert alert-warning mb-0">
                            <span id="bidangAsal">-</span>
                            <small class="d-block text-muted mt-1">
                                <i class="fas fa-info-circle"></i> Bidang yang dipilih siswa saat mengajukan
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="bidangDitempatkan" class="form-label"><strong>Bidang Penempatan</strong></label>
                        <select id="bidangDitempatkan" class="form-control form-control-lg" required>
                            <option value="">-- Pilih Bidang Penempatan --</option>
                        </select>
                        <small class="text-danger" id="bidangError" style="display: none;"></small>
                    </div>
                </div>

                <!-- Keterangan Section -->
                <div class="mb-3">
                    <label for="keterangan" class="form-label"><strong>Keterangan (Opsional)</strong></label>
                    <textarea id="keterangan" class="form-control" rows="3" placeholder="Tulis keterangan atau catatan untuk penempatan ini" maxlength="255"></textarea>
                    <small class="text-muted">Maksimal 255 karakter</small>
                </div>

                <!-- Info Box -->
                <div class="alert alert-secondary" role="alert">
                    <i class="fas fa-lightbulb me-2"></i>
                    <small>
                        <strong>Catatan:</strong> Setelah penempatan dilakukan, pihak lain tidak dapat mengubah penempatan ini. 
                        Pastikan bidang penempatan sudah sesuai.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" id="savePenempatanBtn" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                    <i class="fas fa-save me-2"></i>Simpan Penempatan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variable untuk menyimpan ID sertifikat yang sedang diproses
let currentSertifikatId = null;

// Function untuk membuka modal penempatan
function openPenempatanModal(idSertifikat) {
    currentSertifikatId = idSertifikat;
    
    // Reset form
    document.getElementById('placementAlertContainer').innerHTML = '';
    document.getElementById('bidangDitempatkan').value = '';
    document.getElementById('keterangan').value = '';

    // Load data sertifikat
    fetch(`/mentor/penempatan-bidang/${idSertifikat}/show`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showAlert('danger', data.message);
                return;
            }

            // Populate modal dengan data siswa
            document.getElementById('modalNamaSiswa').textContent = data.sertifikat.nama_lengkap;
            document.getElementById('modalNimSiswa').textContent = data.sertifikat.nim_nis;
            document.getElementById('modalAsalSekolah').textContent = data.sertifikat.asal_sekolah;
            document.getElementById('modalJurusan').textContent = data.sertifikat.jurusan;
            document.getElementById('bidangAsal').textContent = data.bidang_asal;

            // Populate bidang options
            const bidangSelect = document.getElementById('bidangDitempatkan');
            bidangSelect.innerHTML = '<option value="">-- Pilih Bidang Penempatan --</option>';
            data.bidang_list.forEach(bidang => {
                const option = document.createElement('option');
                option.value = bidang;
                option.textContent = bidang;
                bidangSelect.appendChild(option);
            });

            // Check if already placed
            if (data.isPlaced) {
                showAlert('warning', `Siswa ini sudah ditempatkan di bidang <strong>${data.placement.bidang_ditempatkan}</strong> oleh <strong>${data.placement.ditempatkan_oleh}</strong>.`);
                document.getElementById('bidangDitempatkan').disabled = true;
                document.getElementById('keterangan').disabled = true;
                document.getElementById('savePenempatanBtn').disabled = true;
            } else {
                document.getElementById('bidangDitempatkan').disabled = false;
                document.getElementById('keterangan').disabled = false;
                document.getElementById('savePenempatanBtn').disabled = false;
            }

            // Show modal
            $('#penempatanBidangModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Gagal memuat data penempatan');
        });
}

// Function untuk menampilkan alert
function showAlert(type, message) {
    const alertContainer = document.getElementById('placementAlertContainer');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
}

// Event listener untuk tombol Simpan Penempatan
document.getElementById('savePenempatanBtn').addEventListener('click', function() {
    const bidangDitempatkan = document.getElementById('bidangDitempatkan').value;
    const keterangan = document.getElementById('keterangan').value;

    if (!bidangDitempatkan) {
        document.getElementById('bidangError').style.display = 'block';
        document.getElementById('bidangError').textContent = 'Bidang penempatan harus dipilih';
        return;
    }

    // Disable button dan tampilkan loading
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

    // Send data to server
    fetch('/mentor/penempatan-bidang/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id_sertifikat: currentSertifikatId,
            bidang_ditempatkan: bidangDitempatkan,
            keterangan: keterangan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', '<i class="fas fa-check-circle me-2"></i>Penempatan bidang berhasil disimpan!');
            setTimeout(() => {
                $('#penempatanBidangModal').modal('hide');
                location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Gagal menyimpan penempatan');
            document.getElementById('savePenempatanBtn').disabled = false;
            document.getElementById('savePenempatanBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan Penempatan';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat menyimpan penempatan');
        document.getElementById('savePenempatanBtn').disabled = false;
        document.getElementById('savePenempatanBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan Penempatan';
    });
});
</script>
