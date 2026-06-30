$(document).ready(function () {
    // ========== DataTable ==========
    if ($('#myTable').length) {
        new DataTable('#myTable', {});
    }

    const addModal = $('#addModal');

    // ========== Summernote + Leaflet (lazy, di modal Ajukan Event) ==========
    let editorsReady = false;
    let map = null;
    let marker = null;

    function initEditors() {
        if (editorsReady || !$('#description').length) return;
        editorsReady = true;
        const toolbar = [
            ['font', ['bold', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'paragraph']],
        ];
        $('#description').summernote({ placeholder: 'Deskripsi event', tabsize: 2, height: 120, toolbar });
        $('#syarat').summernote({ placeholder: 'Syarat dan ketentuan', tabsize: 2, height: 120, toolbar });
    }

    function initMap() {
        if (map) {
            setTimeout(() => map.invalidateSize(), 200);
            return;
        }
        map = L.map('map').setView([-6.21462, 106.84513], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        marker = L.marker([-6.21462, 106.84513]).addTo(map);
        marker.bindPopup('Lokasi Event Anda').openPopup();
        setTimeout(() => map.invalidateSize(), 200);
    }

    // ========== Modal Open/Close ==========
    if ($('#addModal').length) {
        $('#addBtn').click(function () {
            addModal.removeClass('hidden');
            initEditors();
            initMap();
        });
        $('#closeAddModal').click(() => addModal.addClass('hidden'));

        $(window).click((e) => {
            if (e.target === addModal[0]) addModal.addClass('hidden');
        });
        $(document).on('keydown', (e) => {
            if (e.key === 'Escape') addModal.addClass('hidden');
        });

        // ===== Pencarian lokasi =====
        $('#searchBtn').on('click', function () {
            const q = $('#searchLocation').val();
            if (!q) return;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const loc = data[0];
                        map.setView([loc.lat, loc.lon], 15);
                        marker.setLatLng([loc.lat, loc.lon]);
                        $('#location').val(loc.display_name);
                    } else {
                        alert('Lokasi tidak ditemukan');
                    }
                })
                .catch(err => console.error('Error fetching location:', err));
        });

        $('#locateBtn').on('click', function () {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung browser ini.');
                return;
            }
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude, lon = position.coords.longitude;
                map.setView([lat, lon], 15);
                marker.setLatLng([lat, lon]);
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                    .then(r => r.json())
                    .then(data => {
                        $('#location').val(data && data.display_name ? data.display_name : 'Nama lokasi tidak ditemukan');
                    })
                    .catch(() => $('#location').val('Gagal mengambil nama lokasi'));
            }, function (error) {
                alert('Gagal mengambil lokasi: ' + error.message);
            });
        });
    }

    // ========== Delete Confirmation ==========
    $(document).on('click', '.delete-confirm', function () {
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus?',
            text: 'Anda tidak akan dapat mengembalikan ini!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
