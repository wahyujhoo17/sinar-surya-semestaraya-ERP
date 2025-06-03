/**
 * Permintaan Barang Module JS
 * Contains custom JavaScript for the Permintaan Barang module
 */

$(function () {
    // Initialize Select2 for regular dropdowns
    if ($.fn.select2) {
        $('.select2-search').select2({
            width: '100%',
            allowClear: true,
            placeholder: "Pilih..."
        });
    }

    // Initialize modal select2
    function initModalSelect2() {
        if ($.fn.select2) {
            $('.select2-modal').select2({
                width: '100%',
                dropdownParent: $('#autoCreateModal'),
                allowClear: true,
                placeholder: "Pilih..."
            });
        }
    }

    // Handle quick search
    $('#quickSearch').on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $("#table-container table tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Auto-create button handling with modern modal
    $('#autoCreateBtn').click(function () {
        $('#autoCreateModal').removeClass('hidden');
        initModalSelect2();
    });

    $('#cancelAutoCreate').click(function () {
        $('#autoCreateModal').addClass('hidden');
    });

    // Close modal when clicking outside
    $(document).mouseup(function (e) {
        const modal = $(".inline-block.align-bottom");
        if (!modal.is(e.target) && modal.has(e.target).length === 0) {
            $('#autoCreateModal').addClass('hidden');
        }
    });
});
