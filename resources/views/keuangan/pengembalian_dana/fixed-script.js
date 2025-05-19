document.addEventListener('alpine:init', () => {
    // Dropdown search component with proper initialization
    Alpine.data('dropdownSearch', (fieldName, options, selected = '') => ({
        search: '',
        open: false,
        options: options || [],
        selectedOption: selected || { value: '', label: '' },

        filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(option =>
                option.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        select(option) {
            this.selectedOption = option;
            this.open = false;
            this.search = '';
            this.$dispatch('option-selected', {
                fieldName,
                value: option.value
            });
        },

        init() {
            // If we have a selected value but no full object, find it in options
            if (this.selectedOption && typeof this.selectedOption === 'string' && this.selectedOption !== '') {
                const foundOption = this.options.find(opt => opt.value === this.selectedOption);
                if (foundOption) {
                    this.selectedOption = foundOption;
                }
            }

            // If selectedOption is a primitive value, convert it to object
            if (this.selectedOption && typeof this.selectedOption.value === 'undefined') {
                this.selectedOption = { value: this.selectedOption, label: '' };
            }
        }
    }));

    // Form handler component with proper initialization and DOM-safe element references
    Alpine.data('formHandler', () => ({
        kelebihanBayar: '{{ number_format($kelebihanBayar, 0, ', ', '.') }}',
        metodePenerimaan: '{{ old('metode_penerimaan', '') }}',
        kasVisible: {{ old('metode_penerimaan') == 'kas' ? 'true' : 'false' }},
    bankVisible: {{ old('metode_penerimaan') == 'bank' ? 'true' : 'false' }},
    selectedPo: '{{ old('purchase_order_id', $po->id ?? '') }}',
    selectedSupplier: '{{ old('supplier_id', $po->supplier_id ?? '') }}',
    selectedKas: '{{ old('kas_id', '') }}',
    selectedRekening: '{{ old('rekening_id', '') }}',
    jumlahDisplay: '{{ old('jumlah_display', number_format($kelebihanBayar, 0, ', ', '.')) }}',
    jumlahValue: '{{ old('jumlah', $kelebihanBayar) }}',

    init() {
        // Initialize form state
        setTimeout(() => {
    this.updateVisibility();
}, 100);
        },

handleMetodePenerimaan() {
    this.metodePenerimaan = this.$event.target.value;
    this.updateVisibility();
},

updateVisibility() {
    this.kasVisible = this.metodePenerimaan === 'kas';
    this.bankVisible = this.metodePenerimaan === 'bank';

    // Update required attributes - using safe selectors instead of direct IDs
    if (this.metodePenerimaan === 'kas') {
        this.selectedRekening = '';
        const kasInput = document.querySelector('input[name="kas_id"]');
        const rekeningInput = document.querySelector('input[name="rekening_id"]');
        if (kasInput) kasInput.setAttribute('required', '');
        if (rekeningInput) rekeningInput.removeAttribute('required');
    } else if (this.metodePenerimaan === 'bank') {
        this.selectedKas = '';
        const kasInput = document.querySelector('input[name="kas_id"]');
        const rekeningInput = document.querySelector('input[name="rekening_id"]');
        if (kasInput) kasInput.removeAttribute('required');
        if (rekeningInput) rekeningInput.setAttribute('required', '');
    } else {
        this.selectedKas = '';
        this.selectedRekening = '';
        const kasInput = document.querySelector('input[name="kas_id"]');
        const rekeningInput = document.querySelector('input[name="rekening_id"]');
        if (kasInput) kasInput.removeAttribute('required');
        if (rekeningInput) rekeningInput.removeAttribute('required');
    }
},

formatCurrency(event) {
    let value = event.target.value.replace(/[^\d]/g, '');
    if (value === '') value = '0';
    this.jumlahValue = value;
    this.jumlahDisplay = new Intl.NumberFormat('id-ID').format(value);
},

        async handlePurchaseOrderChange() {
    const poId = this.selectedPo;
    if (!poId) return;

    try {
        const response = await fetch(
            `{{ route('keuangan.pengembalian-dana.get-po-data') }}?po_id=${poId}`, {
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            // Update nilai kelebihan bayar
            const kelebihanBayar = data.data.kelebihan_bayar;
            this.kelebihanBayar = new Intl.NumberFormat('id-ID').format(
                kelebihanBayar);
            this.jumlahDisplay = new Intl.NumberFormat('id-ID').format(
                kelebihanBayar);
            this.jumlahValue = kelebihanBayar;

            // Update supplier
            if (data.data.supplier_id) {
                this.selectedSupplier = data.data.supplier_id;

                // Broadcast change to supplier dropdown
                this.$dispatch('option-selected', {
                    fieldName: 'supplier_id',
                    value: data.data.supplier_id
                });
            }
        }
    } catch (error) {
        console.error('Error fetching PO data:', error);
    }
},

handleOptionSelected(event) {
    const { fieldName, value } = event.detail;

    if (fieldName === 'purchase_order_id') {
        this.selectedPo = value;
        this.handlePurchaseOrderChange();
    } else if (fieldName === 'supplier_id') {
        this.selectedSupplier = value;
    } else if (fieldName === 'kas_id') {
        this.selectedKas = value;
    } else if (fieldName === 'rekening_id') {
        this.selectedRekening = value;
    }
}
    }));
});
