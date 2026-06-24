@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var errors = @json($errors->all());
            var html = '<ul style="text-align:left;margin:0;padding-left:1.1rem">' +
                errors.map(function (e) { return '<li>' + e + '</li>'; }).join('') + '</ul>';
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi gagal',
                    html: html,
                    confirmButtonColor: '#f97316',
                    confirmButtonText: 'Mengerti',
                });
            } else {
                alert(errors.join('\n'));
            }
        });
    </script>
@endif
