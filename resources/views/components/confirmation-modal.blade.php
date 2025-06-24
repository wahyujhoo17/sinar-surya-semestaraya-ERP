@props(['id' => 'confirmation-modal'])

<!-- Simple Global Confirmation Modal (Vanilla JS) -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300"
        onclick="hideConfirmationModal()"></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all duration-300 max-w-md w-full mx-auto opacity-0 scale-95"
            id="modal-content">

            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div id="modal-icon" class="flex-shrink-0 mr-4">
                        <!-- Icon will be dynamically set -->
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                            Konfirmasi Tindakan
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p id="modal-message" class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Apakah Anda yakin ingin melanjutkan?
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button id="modal-cancel" type="button" onclick="hideConfirmationModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    Batal
                </button>
                <button id="modal-confirm" type="button" onclick="confirmAction()"
                    class="px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:scale-105 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:ring-orange-500">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Global confirmation modal namespace
        window.ConfirmationModal = window.ConfirmationModal || {
            confirmationCallback: null,
            cancelCallback: null
        };

        // Show confirmation modal
        window.showConfirmation = function(options) {
            const modal = document.getElementById('confirmation-modal');
            const modalContent = document.getElementById('modal-content');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const modalIcon = document.getElementById('modal-icon');
            const confirmBtn = document.getElementById('modal-confirm');

            // Set content
            modalTitle.textContent = options.title || 'Konfirmasi Tindakan';
            modalMessage.innerHTML = options.message || 'Apakah Anda yakin ingin melanjutkan?';

            // Set callbacks
            window.ConfirmationModal.confirmationCallback = options.onConfirm || null;
            window.ConfirmationModal.cancelCallback = options.onCancel || null;

            // Set icon and button style based on type
            const type = options.type || 'warning';

            if (type === 'danger') {
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>`;
                confirmBtn.className =
                    "px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:scale-105 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-red-500";
            } else if (type === 'info') {
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>`;
                confirmBtn.className =
                    "px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:scale-105 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-blue-500";
            } else if (type === 'success') {
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>`;
                confirmBtn.className =
                    "px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:scale-105 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:ring-green-500";
            } else {
                // warning (default)
                modalIcon.innerHTML = `
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>`;
                confirmBtn.className =
                    "px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:scale-105 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:ring-orange-500";
            }

            // Show modal with animation
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                modalContent.classList.remove('opacity-0', 'scale-95');
                modalContent.classList.add('opacity-100', 'scale-100');
            }, 10);
        };

        // Hide confirmation modal
        window.hideConfirmationModal = function() {
            const modal = document.getElementById('confirmation-modal');
            const modalContent = document.getElementById('modal-content');

            modalContent.classList.remove('opacity-100', 'scale-100');
            modalContent.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                window.ConfirmationModal.confirmationCallback = null;
                window.ConfirmationModal.cancelCallback = null;
            }, 300);
        };

        // Confirm action
        window.confirmAction = function() {
            if (window.ConfirmationModal.confirmationCallback) {
                window.ConfirmationModal.confirmationCallback();
            }
            hideConfirmationModal();
        };

        // Helper for delete confirmation
        window.confirmDelete = function(message, callback) {
            showConfirmation({
                title: 'Konfirmasi Hapus',
                message: message ||
                    'Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.',
                type: 'danger',
                onConfirm: callback
            });
        };

        // Helper for cleanup confirmation
        window.confirmCleanup = function(message, callback) {
            showConfirmation({
                title: 'Konfirmasi Pembersihan',
                message: message || 'Apakah Anda yakin ingin membersihkan data ini?',
                type: 'warning',
                onConfirm: callback
            });
        };

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('confirmation-modal').classList.contains('hidden')) {
                hideConfirmationModal();
            }
        });
    </script>
@endpush
