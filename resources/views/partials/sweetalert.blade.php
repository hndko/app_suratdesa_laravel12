<style>
    .simades-toast {
        border-radius: 12px !important;
        box-shadow: 0 16px 40px rgba(15, 23, 42, .16) !important;
        padding: .85rem 1rem !important;
    }

    .simades-toast .swal2-title {
        line-height: 1.45 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal === 'undefined') {
            return;
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            customClass: {
                popup: 'simades-toast'
            },
            didOpen: function (toast) {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        const escapeHtml = function (value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        window.showToast = function (icon, message) {
            if (!message) {
                return;
            }

            Toast.fire({
                icon: icon,
                html: escapeHtml(message).replace(/\n/g, '<br>')
            });
        };

        const flashMessages = @json([
            ['icon' => 'success', 'message' => session('success')],
            ['icon' => 'error', 'message' => session('error')],
            ['icon' => 'warning', 'message' => session('warning')],
            ['icon' => 'info', 'message' => session('info')],
        ]);

        flashMessages.forEach(function (item) {
            if (item.message) {
                window.showToast(item.icon, item.message);
            }
        });

        const validationErrors = @json($errors->any() ? $errors->all() : []);
        if (validationErrors.length > 0) {
            Toast.fire({
                icon: 'error',
                html: validationErrors.map(function (message) {
                    return '<div class="text-start">' + escapeHtml(message) + '</div>';
                }).join('')
            });
        }

        document.querySelectorAll('form.js-confirm-submit').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: form.dataset.confirmTitle || 'Konfirmasi',
                    text: form.dataset.confirmText || 'Apakah Anda yakin ingin melanjutkan?',
                    icon: form.dataset.confirmIcon || 'warning',
                    showCancelButton: true,
                    confirmButtonText: form.dataset.confirmButton || 'Ya, lanjutkan',
                    cancelButtonText: form.dataset.cancelButton || 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger mx-1',
                        cancelButton: 'btn btn-secondary mx-1'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
