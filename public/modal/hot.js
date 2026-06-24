$(document).ready(function () {
    // ========== DataTable ==========
    if ($('#myTable').length) {
        new DataTable('#myTable', {
            columnDefs: [{
                targets: 1,
                render: function (data, type) {
                    if (type !== 'display' || !data) return data;
                    const date = new Date(data);
                    return isNaN(date) ? data : date.toLocaleDateString('id-ID');
                },
            }],
        });
    }

    const addModal = $('#addModal');
    $('#addBtn').click(() => addModal.removeClass('hidden'));
    $('#closeAddModal').click(() => addModal.addClass('hidden'));

    $(window).click((e) => {
        if (e.target === addModal[0]) addModal.addClass('hidden');
    });
    $(document).on('keydown', (e) => {
        if (e.key === 'Escape') addModal.addClass('hidden');
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
