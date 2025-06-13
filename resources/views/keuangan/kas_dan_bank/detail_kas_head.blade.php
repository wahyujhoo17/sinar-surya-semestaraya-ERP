@push('scripts')
    <script src="{{ asset('js/simple-modal.js') }}?v={{ time() }}"></script>
    <script>
        // Initialize modal system when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Set up global aliases for compatibility
            window.showTransactionDetail = window.showSimpleModal;
            window.closeTransactionDetail = window.closeSimpleModal;

            // Add event listeners for close buttons if they exist
            document.querySelectorAll('button[onclick="closeTransactionDetail()"]').forEach(button => {
                button.addEventListener('click', closeSimpleModal);
            });

            document.querySelectorAll('button[onclick="closeSimpleModal()"]').forEach(button => {
                button.addEventListener('click', closeSimpleModal);
            });

            // Set up backdrop click handling
            const backdrop = document.getElementById('modal-backdrop');
            if (backdrop) {
                backdrop.addEventListener('click', closeSimpleModal);
            }
        });
    </script>
@endpush
