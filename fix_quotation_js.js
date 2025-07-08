calculateSubtotal(index, changedDiscountType = null) {
    try {
        // Safely access items array
        const items = this.$data && this.$data.items ? this.$data.items : this.items;
        if (!Array.isArray(items) || !items[index]) {
            return;
        }

        const item = items[index];
        const quantity = parseFloat(item.quantity) || 0;
        const harga = parseFloat(item.harga) || 0;
        const diskonPersen = parseFloat(item.diskon_persen) || 0;

        const subtotalBeforeDiscount = quantity * harga;
        const diskonNominal = (subtotalBeforeDiscount * diskonPersen) / 100;
        const subtotalAfterDiscount = subtotalBeforeDiscount - diskonNominal;

        // Update item values
        item.diskon_nominal = diskonNominal;
        item.subtotal = subtotalAfterDiscount;

        // Recalculate totals
        this.calculateTotals();
    } catch (error) {
        console.error('Error calculating subtotal:', error);
    }
},

calculateGlobalDiscount(type) {
    try {
        if (type === 'persen') {
            // Calculate nominal from percentage
            const subtotal = this.summary ? this.summary.total_sebelum_diskon_global : 0;
            this.diskon_global_nominal = (subtotal * this.diskon_global_persen) / 100;
        } else if (type === 'nominal') {
            // Calculate percentage from nominal
            const subtotal = this.summary ? this.summary.total_sebelum_diskon_global : 0;
            if (subtotal > 0) {
                this.diskon_global_persen = (this.diskon_global_nominal / subtotal) * 100;
            } else {
                this.diskon_global_persen = 0;
            }
        }

        this.calculateTotals();
    } catch (error) {
        console.error('Error calculating global discount:', error);
    }
},

calculateTotals() {
    try {
        if (!Array.isArray(this.items)) {
            this.items = [];
        }

        let total_sebelum_diskon_global = 0;

        // Calculate subtotal from all items
        this.items.forEach(item => {
            if (item && typeof item.subtotal === 'number') {
                total_sebelum_diskon_global += item.subtotal;
            }
        });

        // Apply global discount
        const diskon_global_nominal = parseFloat(this.diskon_global_nominal) || 0;
        const total_setelah_diskon_global = total_sebelum_diskon_global - diskon_global_nominal;

        // Calculate PPN
        const ppn_persen = this.includePPN ? {{ setting('tax_percentage', 11)
    }} : 0;
const ppn_nominal = (total_setelah_diskon_global * ppn_persen) / 100;

// Calculate grand total
const grand_total = total_setelah_diskon_global + ppn_nominal;

// Update summary
this.summary = {
    total_sebelum_diskon_global: total_sebelum_diskon_global,
    total_setelah_diskon_global: total_setelah_diskon_global,
    ppn_nominal: ppn_nominal,
    grand_total: grand_total
};
                        } catch (error) {
    console.error('Error calculating totals:', error);
}
                    },

formatCurrency(value) {
    try {
        const num = parseFloat(value) || 0;
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(num);
    } catch (error) {
        return 'Rp 0';
    }
},

validateAndSubmitForm(event) {
    try {
        this.formErrors = [];

        // Validate items
        if (!Array.isArray(this.items) || this.items.length === 0) {
            this.formErrors.push('Minimal harus ada 1 item dalam quotation');
        }

        // Validate each item
        this.items.forEach((item, index) => {
            if (!item.produk_id) {
                this.formErrors.push(`Item ${index + 1}: Produk harus dipilih`);
            }
            if (!item.quantity || item.quantity <= 0) {
                this.formErrors.push(`Item ${index + 1}: Kuantitas harus lebih dari 0`);
            }
            if (!item.satuan_id) {
                this.formErrors.push(`Item ${index + 1}: Satuan harus dipilih`);
            }
            if (!item.harga || item.harga <= 0) {
                this.formErrors.push(`Item ${index + 1}: Harga harus lebih dari 0`);
            }
        });

        if (this.formErrors.length > 0) {
            event.preventDefault();
            return false;
        }

        // If validation passes, allow form submission
        return true;
    } catch (error) {
        console.error('Error validating form:', error);
        this.formErrors = ['Terjadi kesalahan validasi form'];
        event.preventDefault();
        return false;
    }
}

// Additional missing methods
