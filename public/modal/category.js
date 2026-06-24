$(document).ready(function () {
    // ========== DataTable ==========
    if ($('#myTable').length) {
        new DataTable('#myTable', {
            columnDefs: [{
                targets: 1, // kolom Date
                render: function (data, type) {
                    if (type !== 'display' || !data) return data;
                    const date = new Date(data);
                    return isNaN(date) ? data : date.toLocaleDateString('id-ID');
                },
            }],
        });
    }

    // ========== Modal Open/Close ==========
    const addModal = $('#addModal');
    const editModal = $('#editModal');

    $('#addBtn').click(() => addModal.removeClass('hidden'));
    $('#closeAddModal').click(() => addModal.addClass('hidden'));
    $('#closeEditModal').click(() => editModal.addClass('hidden'));

    // Klik luar modal untuk menutup
    $(window).click((e) => {
        if (e.target === addModal[0]) addModal.addClass('hidden');
        if (e.target === editModal[0]) editModal.addClass('hidden');
    });

    // Tutup dengan Escape
    $(document).on('keydown', (e) => {
        if (e.key === 'Escape') {
            addModal.addClass('hidden');
            editModal.addClass('hidden');
        }
    });

    // ========== Edit ==========
    $(document).on('click', '.editBtn', function () {
        const btn = $(this);
        $('#editName').val(btn.data('name'));
        $('#editForm').attr('action', `/updatecategory/${btn.data('id')}`);
        editModal.removeClass('hidden');
    });

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
