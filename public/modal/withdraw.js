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

    // ========== Summernote (lazy) ==========
    let addEditorReady = false;
    function initAddEditor() {
        if (addEditorReady || !$('#addNote').length) return;
        addEditorReady = true;
        $('#addNote').summernote({
            placeholder: 'Catatan withdraw',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['font', ['bold', 'underline']],
                ['color', ['color']],
                ['para', ['ul', 'paragraph']],
            ],
        });
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
    $(window).click((e) => {
        if (e.target === addModal[0]) addModal.addClass('hidden');
    });
    $(document).on('keydown', (e) => {
        if (e.key === 'Escape') addModal.addClass('hidden');
    });

    // ========== Approve Confirmation ==========
    $(document).on('click', '.approve-confirm', function () {
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Setujui withdraw?',
            text: 'Saldo user akan dikurangi sesuai jumlah penarikan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, setujui!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
