document.addEventListener('alpine:init', () => {
    // Initialize static Select2 dropdowns after the page loads but before Alpine initializes
    $(document).ready(function () {
        // Initialize static dropdowns
        $('#customer_id').select2({
            placeholder: "Pilih Customer",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#customer_id').parent()
        });

        $('#status').select2({
            minimumResultsForSearch: -1, // Disable search for short lists
            width: '100%',
            dropdownParent: $('#status').parent()
        });

        // Handle the sync between Select2 and Alpine.js for static elements
        $('#customer_id').on('select2:select', function (e) {
            $(this).trigger('change');
        });

        $('#status').on('select2:select', function (e) {
            $(this).trigger('change');
        });
    });
});

// This function extends the quotationForm Alpine component
function extendQuotationForm(quotationForm) {
    const originalInit = quotationForm.init;

    quotationForm.init = function () {
        // Call the original init method first
        originalInit.call(this);

        // Watch for changes in the items array to initialize dynamic Select2
        this.$watch('items', () => {
            this.$nextTick(() => {
                this.initDynamicSelect2();
            });
        });

        // Initialize Select2 on initial items after a short delay
        setTimeout(() => {
            this.initDynamicSelect2();
        }, 100);
    };

    quotationForm.initDynamicSelect2 = function () {
        // Destroy any existing Select2 instances to avoid duplicates
        $('.select2-dropdown-dynamic').each(function () {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
        });

        // Initialize Select2 for each dynamic dropdown
        this.items.forEach((item, index) => {
            // Product dropdown
            const produkSelect = $(`select[name="items[${index}][produk_id]"]`);
            if (produkSelect.length) {
                produkSelect.select2({
                    placeholder: "Pilih Produk",
                    width: '100%',
                    dropdownParent: produkSelect.parent()
                }).on('select2:select', (e) => {
                    // Update Alpine.js model and trigger change event
                    produkSelect.trigger('change');
                    // Call updateItemDetails with a small delay to ensure model updates first
                    setTimeout(() => {
                        this.updateItemDetails(index);
                    }, 50);
                });
            }

            // Unit dropdown
            const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
            if (satuanSelect.length) {
                satuanSelect.select2({
                    placeholder: "Pilih",
                    width: '100%',
                    dropdownParent: satuanSelect.parent()
                }).on('select2:select', (e) => {
                    satuanSelect.trigger('change');
                });
            }
        });
    };

    const originalAddItem = quotationForm.addItem;
    quotationForm.addItem = function () {
        // Call the original addItem method
        originalAddItem.call(this);

        // Initialize Select2 for the new item after a short delay
        this.$nextTick(() => {
            this.initDynamicSelect2();
        });
    };

    const originalRemoveItem = quotationForm.removeItem;
    quotationForm.removeItem = function (index) {
        // Call the original removeItem method
        originalRemoveItem.call(this, index);

        // Reinitialize all Select2 elements after removal
        this.$nextTick(() => {
            this.initDynamicSelect2();
        });
    };

    return quotationForm;
}
