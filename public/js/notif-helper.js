/**
 * Helper Notifikasi SIMGANG
 * Menggunakan SweetAlert2 untuk notifikasi yang lebih baik dan konsisten dengan tema SIMGANG (Ungu).
 * 
 * Cara pakai:
 * - showToast('Pesan berhasil', 'success')
 * - showToast('Pesan error', 'error')
 * - showConfirm('Apakah Anda yakin ingin menghapus data [nama]?', function() { ... })
 */

// SIMGANG Brand Colors
const SIMGANG_COLORS = {
    primary: '#5a60ff',
    danger: '#dc3545',
    secondary: '#6b7280',
    success: '#2e7d32',
    warning: '#f9a825',
    info: '#0288d1'
};

// Generic Toast Wrapper
function showToast(message, type = 'success', title = '') {
    if (!title) {
        if (type === 'success') title = 'Berhasil';
        else if (type === 'error') title = 'Gagal';
        else if (type === 'warning') title = 'Peringatan';
        else title = 'Informasi';
    }

    const iconColor = type === 'success' ? SIMGANG_COLORS.success : 
                      type === 'error' ? SIMGANG_COLORS.danger : 
                      type === 'warning' ? SIMGANG_COLORS.warning : SIMGANG_COLORS.info;

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#1f2937',
        iconColor: iconColor,
        customClass: {
            popup: 'simgang-toast-popup animate__animated animate__fadeInRight'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: title,
        text: message
    });
}

// Toast notification untuk sukses (Legacy wrapper)
function showSuccessToast(message, title = 'Berhasil') {
    showToast(message, 'success', title);
}

// Toast notification untuk error (Legacy wrapper)
function showErrorToast(message, title = 'Error') {
    showToast(message, 'error', title);
}

// Toast notification untuk info (Legacy wrapper)
function showInfoToast(message, title = 'Informasi') {
    showToast(message, 'info', title);
}

// Toast notification untuk warning (Legacy wrapper)
function showWarningToast(message, title = 'Peringatan') {
    showToast(message, 'warning', title);
}

// Modal konfirmasi untuk aksi berbahaya (hapus, dll)
function showConfirm(message, callback, title = 'Konfirmasi Tindakan', confirmText = 'Ya, Hapus', cancelText = 'Batal') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: SIMGANG_COLORS.danger,
        cancelButtonColor: SIMGANG_COLORS.secondary,
        confirmButtonText: `<i class="fa fa-trash me-2"></i>${confirmText}`,
        cancelButtonText: cancelText,
        reverseButtons: true,
        background: '#ffffff',
        color: '#1f2937',
        customClass: {
            popup: 'simgang-confirm-popup',
            title: 'simgang-confirm-title',
            htmlContainer: 'simgang-confirm-text',
            confirmButton: 'btn btn-danger px-4 py-2 fw-semibold fs-6',
            cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold fs-6 me-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Modal konfirmasi untuk aksi positif (simpan, update, dll)
function showConfirmPositive(message, callback, title = 'Konfirmasi', confirmText = 'Ya, lanjutkan', cancelText = 'Batal') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: SIMGANG_COLORS.primary,
        cancelButtonColor: SIMGANG_COLORS.secondary,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        background: '#ffffff',
        color: '#1f2937',
        customClass: {
            popup: 'simgang-confirm-popup',
            confirmButton: 'btn btn-primary px-4 py-2 fw-semibold fs-6',
            cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold fs-6 me-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Modal alert untuk sukses (bukan toast)
function showSuccessAlert(message, title = 'Berhasil') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'success',
        confirmButtonColor: SIMGANG_COLORS.primary,
        confirmButtonText: 'Selesai',
        customClass: {
            confirmButton: 'btn btn-primary px-4 py-2 fw-semibold'
        },
        buttonsStyling: false
    });
}

// Modal alert untuk error (bukan toast)
function showErrorAlert(message, title = 'Error') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'error',
        confirmButtonColor: SIMGANG_COLORS.danger,
        confirmButtonText: 'Mengerti',
        customClass: {
            confirmButton: 'btn btn-danger px-4 py-2 fw-semibold'
        },
        buttonsStyling: false
    });
}

// Modal alert untuk info (bukan toast)
function showInfoAlert(message, title = 'Informasi') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'info',
        confirmButtonColor: SIMGANG_COLORS.primary,
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn btn-primary px-4 py-2 fw-semibold'
        },
        buttonsStyling: false
    });
}
