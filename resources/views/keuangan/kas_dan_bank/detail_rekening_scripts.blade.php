@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle date range picker visibility
            const periodeSelect = document.querySelector('select[name="periode"]');
            const dateRangePicker = document.getElementById('dateRangePicker');
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalAkhir = document.getElementById('tanggal_akhir');

            // Immediately initialize the date picker visibility - fixed to ensure it shows on page load when needed
            if (periodeSelect.value === 'custom' ||
                (tanggalMulai.value && tanggalAkhir.value)) {
                dateRangePicker.classList.remove('hidden');
                periodeSelect.value = 'custom';
            } else {
                dateRangePicker.classList.add('hidden');
            }

            // Show/hide date range based on selection - improved to ensure it shows immediately
            periodeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    // Immediately show the date range picker
                    dateRangePicker.classList.remove('hidden');
                    // Focus on the start date input when switching to custom mode
                    setTimeout(() => tanggalMulai.focus(), 100);
                } else {
                    dateRangePicker.classList.add('hidden');
                    // Clear date inputs when not using custom range
                    tanggalMulai.value = '';
                    tanggalAkhir.value = '';
                }
            });

            // Date range validation
            tanggalMulai.addEventListener('change', function() {
                if (tanggalAkhir.value && tanggalMulai.value > tanggalAkhir.value) {
                    tanggalAkhir.value = tanggalMulai.value;
                }
                periodeSelect.value = 'custom';
                // Make date range picker visible if it wasn't
                dateRangePicker.classList.remove('hidden');
            });

            tanggalAkhir.addEventListener('change', function() {
                if (tanggalMulai.value && tanggalAkhir.value < tanggalMulai.value) {
                    tanggalMulai.value = tanggalAkhir.value;
                }
                periodeSelect.value = 'custom';
                // Make date range picker visible if it wasn't
                dateRangePicker.classList.remove('hidden');
            });

            // Print Button
            const printButton = document.getElementById('printButton');
            printButton.addEventListener('click', function() {
                window.print();
            });
        });
    </script>

    <script>
        // Additional table enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add row highlight effects on hover for better UX
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.classList.add('bg-gray-50', 'dark:bg-gray-700/40', 'shadow-sm');
                });

                row.addEventListener('mouseleave', function() {
                    this.classList.remove('bg-gray-50', 'dark:bg-gray-700/40', 'shadow-sm');
                });
            });

            // Add subtle animation to action buttons
            const actionButtons = document.querySelectorAll('tbody tr a');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.classList.add('transform', 'scale-110');
                    setTimeout(() => {
                        this.classList.remove('transform', 'scale-110');
                    }, 200);
                });
            });

            // Enhance toast notifications
            const toast = document.getElementById('toast-notification');
            if (toast) {
                // Add click anywhere to dismiss
                document.addEventListener('click', function(event) {
                    if (toast.classList.contains('translate-x-0') &&
                        !toast.contains(event.target)) {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => {
                            toast.classList.add('hidden');
                        }, 300);
                    }
                });
            }

            // Enhanced form controls - select highlight on focus
            const formInputs = document.querySelectorAll('input, select');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.relative').classList.add('ring-1', 'ring-primary-300',
                        'dark:ring-primary-700');
                });

                input.addEventListener('blur', function() {
                    this.closest('.relative').classList.remove('ring-1', 'ring-primary-300',
                        'dark:ring-primary-700');
                });
            });
        });

        // Modern filter animation
        const filterButton = document.getElementById('filterButton');
        if (filterButton) {
            filterButton.addEventListener('click', function() {
                // Add apply animation
                this.classList.add('animate-pulse');
                setTimeout(() => {
                    this.classList.remove('animate-pulse');
                }, 500);
            });
        }
    </script>
@endpush
