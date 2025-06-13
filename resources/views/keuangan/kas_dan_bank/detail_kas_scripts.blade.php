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

            // Additional table enhancements
            // Add row highlight effects on hover for better UX
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.classList.add('bg-gray-50', 'dark:bg-gray-700/40', 'shadow-sm');
                });

                row.addEventListener('mouseleave', function() {
                    this.classList.remove('bg-gray-50', 'dark:bg-gray-700/40', 'shadow-sm');
                });

                // Add click handler to show transaction detail modal
                row.addEventListener('click', function(event) {
                    // Don't handle clicks on links or other interactive elements inside the row
                    if (event.target.closest('a') || event.target.closest('button')) {
                        return;
                    }

                    const id = this.getAttribute('data-id');
                    const noBukti = this.getAttribute('data-no-bukti');
                    const tanggal = this.getAttribute('data-tanggal');
                    const keterangan = this.getAttribute('data-keterangan');
                    const jenis = this.getAttribute('data-jenis');
                    const jumlah = this.getAttribute('data-jumlah');
                    const createdAt = this.getAttribute('data-created-at');
                    const updatedAt = this.getAttribute('data-updated-at');
                    const relatedId = this.getAttribute('data-related-id');
                    const relatedType = this.getAttribute('data-related-type');

                    // Make sure the modal function exists before calling it
                    if (typeof window.showSimpleModal === 'function') {
                        window.showSimpleModal(id, noBukti, tanggal, keterangan, jenis, jumlah,
                            createdAt, updatedAt, relatedId, relatedType);
                    } else {
                        console.error('showSimpleModal function not found!');
                        alert(
                            'Error: Modal function not available. Please refresh the page and try again.'
                            );
                    }
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

        // Transaction Detail Modal Functions
        window.showTransactionDetail = function(id, noBukti, tanggal, keterangan, jenis, jumlah, createdAt, updatedAt,
            relatedId, relatedType) {
            const modal = document.getElementById('transactionDetailModal');
            if (!modal) {
                return;
            }

            const backdrop = document.getElementById('modal-backdrop');
            if (!backdrop) {
                return;
            }

            const iconContainer = document.getElementById('transaction-icon-container');
            const title = document.getElementById('transaction-title');
            const subtitle = document.getElementById('transaction-subtitle');
            const detailReference = document.getElementById('detail-reference');
            const detailDate = document.getElementById('detail-date');
            const detailType = document.getElementById('detail-type');
            const detailAmount = document.getElementById('detail-amount');
            const detailDescription = document.getElementById('detail-description');
            const detailRelated = document.getElementById('detail-related');
            const detailCreated = document.getElementById('detail-created');
            const relatedDocumentContainer = document.getElementById('related-document-container');

            // Format the date
            let formattedDate;
            try {
                formattedDate = new Date(tanggal).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            } catch (error) {
                formattedDate = tanggal;
            }

            // Format the created date
            let formattedCreatedDate;
            try {
                formattedCreatedDate = new Date(createdAt).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (error) {
                formattedCreatedDate = createdAt;
            }

            // Format the amount
            const formattedAmount = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(jumlah);

            // Set the icon based on transaction type
            if (jenis === 'masuk') {
                iconContainer.innerHTML = `
                    <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" 
                            class="w-6 h-6 text-green-600 dark:text-green-400">
                            <path fill-rule="evenodd" d="M1.22 5.222a.75.75 0 011.06 0L7 9.942l3.768-3.769a.75.75 0 011.113.058 20.908 20.908 0 013.813 7.254l1.574-2.727a.75.75 0 011.3.75l-2.475 4.286a.75.75 0 01-1.025.275l-4.287-2.475a.75.75 0 01.75-1.3l2.71 1.565a19.422 19.422 0 00-3.013-6.024L7.53 11.533a.75.75 0 01-1.06 0l-5.25-5.25a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                    </div>
                `;
                title.innerHTML = 'Detail Transaksi Masuk';
                detailType.innerHTML = `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-1">
                            <path fill-rule="evenodd" d="M1.22 5.222a.75.75 0 011.06 0L7 9.942l3.768-3.769a.75.75 0 011.113.058 20.908 20.908 0 013.813 7.254l1.574-2.727a.75.75 0 011.3.75l-2.475 4.286a.75.75 0 01-1.025.275l-4.287-2.475a.75.75 0 01.75-1.3l2.71 1.565a19.422 19.422 0 00-3.013-6.024L7.53 11.533a.75.75 0 01-1.06 0l-5.25-5.25a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                        Transaksi Masuk
                    </span>
                `;
                detailAmount.className = 'text-sm font-semibold text-green-600 dark:text-green-400 col-span-2';
                detailAmount.textContent = `+ ${formattedAmount}`;
            } else {
                iconContainer.innerHTML = `
                    <div class="bg-red-100 dark:bg-red-900/30 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" 
                            class="w-6 h-6 text-red-600 dark:text-red-400">
                            <path fill-rule="evenodd" d="M12.577 4.878a.75.75 0 01.919-.53l4.78 1.281a.75.75 0 01.531.919l-1.281 4.78a.75.75 0 01-1.449-.387l.81-3.022a19.407 19.407 0 00-5.594 5.203.75.75 0 01-1.139.093L7 10.06l-4.72 4.72a.75.75 0 01-1.06-1.061l5.25-5.25a.75.75 0 011.06 0l3.074 3.073a20.923 20.923 0 015.545-4.931l-3.042-.815a.75.75 0 01-.53-.919z" clip-rule="evenodd" />
                        </svg>
                    </div>
                `;
                title.innerHTML = 'Detail Transaksi Keluar';
                detailType.innerHTML = `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-1">
                            <path fill-rule="evenodd" d="M12.577 4.878a.75.75 0 01.919-.53l4.78 1.281a.75.75 0 01.531.919l-1.281 4.78a.75.75 0 01-1.449-.387l.81-3.022a19.407 19.407 0 00-5.594 5.203.75.75 0 01-1.139.093L7 10.06l-4.72 4.72a.75.75 0 01-1.06-1.061l5.25-5.25a.75.75 0 011.06 0l3.074 3.073a20.923 20.923 0 015.545-4.931l-3.042-.815a.75.75 0 01-.53-.919z" clip-rule="evenodd" />
                        </svg>
                        Transaksi Keluar
                    </span>
                `;
                detailAmount.className = 'text-sm font-semibold text-red-600 dark:text-red-400 col-span-2';
                detailAmount.textContent = `- ${formattedAmount}`;
            }

            // Set the details
            subtitle.textContent = `No. Bukti: ${noBukti}`;
            detailReference.textContent = noBukti;
            detailDate.textContent = formattedDate;
            detailDescription.textContent = keterangan || '-';
            detailCreated.textContent = formattedCreatedDate;

            // Handle related document
            if (relatedId && relatedType) {
                relatedDocumentContainer.classList.remove('hidden');

                let relatedLink = '#';
                let relatedText = relatedType;

                if (relatedType.includes('Piutang')) {
                    relatedLink = `/keuangan/piutang-usaha/${relatedId}`;
                } else if (relatedType.includes('Hutang')) {
                    relatedLink = `/keuangan/hutang-usaha/${relatedId}`;
                }

                detailRelated.innerHTML = `
                    <a href="${relatedLink}" class="inline-flex items-center text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 hover:underline transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                        </svg>
                        ${relatedText}
                    </a>
                `;
            } else {
                relatedDocumentContainer.classList.remove('hidden');
                detailRelated.innerHTML = '<span class="text-gray-400 dark:text-gray-500">-</span>';
            }

            // Show the modal with animation
            modal.classList.remove('hidden');

            // Add fade in animation
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');

                // Add a slight scale animation to the modal
                const modalPanel = document.querySelector('#transactionDetailModal .inline-block');
                if (!modalPanel) {
                    return;
                }

                modalPanel.style.transform = 'scale(0.95) translateY(10px)';
                modalPanel.style.opacity = '0';

                // Force reflow
                void modalPanel.offsetWidth;

                // Apply animation
                modalPanel.style.transition = 'transform 0.3s ease-out, opacity 0.3s ease-out';
                modalPanel.style.transform = 'scale(1) translateY(0)';
                modalPanel.style.opacity = '1';

                // Focus on the modal for accessibility
                setTimeout(() => {
                    modalPanel.focus();
                }, 100);
            }, 10);

            // Add keyboard event listener for Escape key
            document.addEventListener('keydown', closeOnEscape);

            // Prevent body scrolling
            document.body.style.overflow = 'hidden';
        };

        window.closeTransactionDetail = function() {
            const modal = document.getElementById('transactionDetailModal');
            const backdrop = document.getElementById('modal-backdrop');
            const modalPanel = modal.querySelector('.inline-block');

            // Add fade out animation
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');

            // Animate modal panel
            modalPanel.style.transition = 'transform 0.2s ease-in, opacity 0.2s ease-in';
            modalPanel.style.transform = 'scale(0.95) translateY(10px)';
            modalPanel.style.opacity = '0';

            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset styles for next opening
                modalPanel.style.transform = '';
                modalPanel.style.opacity = '';
                // Remove keyboard event listener
                document.removeEventListener('keydown', closeOnEscape);
                // Restore body scrolling
                document.body.style.overflow = '';
            }, 300);
        };

        function closeOnEscape(e) {
            if (e.key === 'Escape') {
                window.closeTransactionDetail();
            }
        } // Click outside to close
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('transactionDetailModal');
            const modalContent = modal.querySelector('.inline-block');

            if (!modal.classList.contains('hidden') && !modalContent.contains(event.target) && !event.target
                .closest('a')) {
                window.closeTransactionDetail();
            }
        });
    </script>
@endpush
