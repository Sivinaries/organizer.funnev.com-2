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

    const ticketsData = window.ticketsData || {};
    const addModal = $('#addModal');
    const editModal = $('#editModal');

    // ========== Summernote (lazy) ==========
    const toolbar = [
        ['font', ['bold', 'underline']],
        ['color', ['color']],
        ['para', ['ul', 'paragraph']],
    ];
    let addEditorReady = false;
    let editEditorReady = false;

    function initAddEditor() {
        if (addEditorReady || !$('#addDesc').length) return;
        addEditorReady = true;
        $('#addDesc').summernote({ placeholder: 'Deskripsi tiket', tabsize: 2, height: 120, toolbar });
    }
    function initEditEditor() {
        if (editEditorReady || !$('#editDesc').length) return;
        editEditorReady = true;
        $('#editDesc').summernote({ placeholder: 'Deskripsi tiket', tabsize: 2, height: 120, toolbar });
    }

    // ========== Currency formatting (pemisah ribuan di dalam input) ==========
    function formatCurrency(value) {
        const raw = String(value).replace(/\D/g, '');
        return raw === '' ? '' : parseInt(raw, 10).toLocaleString('id-ID');
    }
    $('.currency').on('input', function () {
        $(this).val(formatCurrency($(this).val()));
    });
    $('.currency').each(function () {
        $(this).val(formatCurrency($(this).val()));
    });

    // ========== Modal Open/Close ==========
    $('#addBtn').click(() => {
        addModal.removeClass('hidden');
        initAddEditor();
    });
    $('#closeAddModal').click(() => addModal.addClass('hidden'));
    $('#closeEditModal').click(() => editModal.addClass('hidden'));

    $(window).click((e) => {
        if (e.target === addModal[0]) addModal.addClass('hidden');
        if (e.target === editModal[0]) editModal.addClass('hidden');
    });
    $(document).on('keydown', (e) => {
        if (e.key === 'Escape') {
            addModal.addClass('hidden');
            editModal.addClass('hidden');
        }
    });

    // ========== Edit ==========
    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        const t = ticketsData[id];
        if (!t) return;

        initEditEditor();
        $('#editType').val(t.type);
        $('#editPrice').val(formatCurrency(parseInt(t.price, 10) || 0));
        $('#editPcs').val(t.pcs);
        $('#editEvent').val(t.event_id);
        $('#editDesc').summernote('code', t.desc || '');
        $('#editForm').attr('action', `/updateticket/${id}`);
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
