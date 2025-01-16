document.addEventListener('DOMContentLoaded', function () {
    // Handle the form submission with AJAX
    document.querySelectorAll('.update-status-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Get the form data
            let formData = new FormData(form);

            // Send AJAX request to update the status
            fetch(form.action, {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Display SweetAlert on success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(function() {
                        // Redirect back to the index page
                        window.location.href = "{{ route('pengajuankendala.index') }}";
                    });
                } else {
                    // Display error message from server
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Terjadi kesalahan pada server.',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });
    });
});