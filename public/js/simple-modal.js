// Simple Modal Implementation - FIXED VERSION
// Define the functions directly in the global scope (not inside DOMContentLoaded)
// This ensures they're available immediately to any onclick handlers
window.showSimpleModal = function (id, noBukti, tanggal, keterangan, jenis, jumlah, createdAt, updatedAt, relatedId, relatedType, namaPenerima) {
    // Check if the modal element exists
    const modal = document.getElementById('transactionDetailModal');
    if (!modal) {
        alert('Error: Modal element not found! This is a technical issue, please contact support.');
        return;
    }

    // Make sure backdrop exists
    const backdrop = document.getElementById('modal-backdrop');
    if (!backdrop) {
        // Create backdrop if missing
        const newBackdrop = document.createElement('div');
        newBackdrop.id = 'modal-backdrop';
        newBackdrop.className = 'fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity backdrop-blur-sm';
        newBackdrop.onclick = window.closeSimpleModal;
        modal.insertBefore(newBackdrop, modal.firstChild);
    }

    // Set up icon container based on transaction type
    const iconContainer = document.getElementById('transaction-icon-container');

    // Check all required modal elements
    const title = document.getElementById('transaction-title');
    const subtitle = document.getElementById('transaction-subtitle');
    const detailReference = document.getElementById('detail-reference');
    const detailDate = document.getElementById('detail-date');
    const detailType = document.getElementById('detail-type');
    const detailAmount = document.getElementById('detail-amount');
    const detailDescription = document.getElementById('detail-description');
    const detailRelated = document.getElementById('detail-related');
    const detailCreated = document.getElementById('detail-created');
    const detailNamaPenerima = document.getElementById('detail-nama-penerima');
    const relatedDocumentContainer = document.getElementById('related-document-container');

    // Log missing elements but don't stop execution
    const requiredElements = {
        'iconContainer': iconContainer,
        'title': title,
        'subtitle': subtitle,
        'detailReference': detailReference,
        'detailDate': detailDate,
        'detailType': detailType,
        'detailAmount': detailAmount,
        'detailDescription': detailDescription,
        'detailNamaPenerima': detailNamaPenerima,
        'detailRelated': detailRelated,
        'detailCreated': detailCreated,
        'relatedDocumentContainer': relatedDocumentContainer
    }; const missingElements = Object.entries(requiredElements)
        .filter(([name, element]) => !element)
        .map(([name]) => name);

    if (missingElements.length > 0) {
        alert('Warning: Some modal elements are missing. Modal may not display correctly.');
    }

    // Format amount properly
    const formattedAmount = new Intl.NumberFormat('id-ID').format(jumlah);

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
        detailAmount.textContent = `+ Rp ${formattedAmount}`;
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
        detailAmount.textContent = `- Rp ${formattedAmount}`;
    }

    // Set the values for other fields
    subtitle.textContent = `No. Bukti: ${noBukti}`;
    detailReference.textContent = noBukti;
    detailDate.textContent = tanggal;
    detailDescription.textContent = keterangan || '-';
    detailNamaPenerima.textContent = namaPenerima || '-';
    detailCreated.textContent = createdAt;

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
    if (backdrop) {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
    }

    // Add a slight scale animation to the modal panel if it exists
    const modalPanel = modal.querySelector('.inline-block');
    if (modalPanel) {
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
    }

    // Add keyboard event listener for Escape key
    document.addEventListener('keydown', closeOnEscape);    // Prevent body scrolling
    document.body.style.overflow = 'hidden';
};

// Function to close the modal
window.closeSimpleModal = function () {
    const modal = document.getElementById('transactionDetailModal');
    if (!modal) {
        return;
    }

    const backdrop = document.getElementById('modal-backdrop');
    const modalPanel = modal.querySelector('.inline-block');

    // Add fade out animation if backdrop exists
    if (backdrop) {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
    }

    // Animate modal panel if it exists
    if (modalPanel) {
        modalPanel.style.transition = 'transform 0.2s ease-in, opacity 0.2s ease-in';
        modalPanel.style.transform = 'scale(0.95) translateY(10px)';
        modalPanel.style.opacity = '0';
    } setTimeout(() => {
        modal.classList.add('hidden');

        // Reset styles for next opening
        if (modalPanel) {
            modalPanel.style.transform = '';
            modalPanel.style.opacity = '';
        }        // Remove keyboard event listener
        document.removeEventListener('keydown', closeOnEscape);

        // Restore body scrolling
        document.body.style.overflow = '';
    }, 300);
};

// For backward compatibility with any existing code
window.closeTransactionDetail = window.closeSimpleModal;
window.showTransactionDetail = window.showSimpleModal;

function closeOnEscape(e) {
    if (e.key === 'Escape') {
        closeSimpleModal();
    }
}

// Add a document ready function to ensure event listeners are added properly
document.addEventListener('DOMContentLoaded', function () {
    // Add event listeners for close buttons
    document.querySelectorAll('button[onclick="closeTransactionDetail()"]').forEach(button => {
        button.addEventListener('click', closeSimpleModal);
    });

    document.querySelectorAll('button[onclick="closeSimpleModal()"]').forEach(button => {
        button.addEventListener('click', closeSimpleModal);
    });    // Close when clicking the backdrop
    const backdrop = document.getElementById('modal-backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', closeSimpleModal);
    }
});
