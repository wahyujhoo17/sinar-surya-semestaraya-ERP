<style>
    /* Custom styles for Select2 dropdowns in modals */
    .select2-dropdown-modal {
        z-index: 9999 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        border-radius: 0.375rem !important;
    }

    .select2-container--open .select2-dropdown {
        z-index: 9999 !important;
    }

    /* Improve Select2 default appearance */
    .select2-container--classic .select2-selection--single {
        height: 38px !important;
        padding: 5px 10px !important;
        border-radius: 0.375rem !important;
        border: 1px solid #d1d5db !important;
        background-color: #fff !important;
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }

    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        color: #374151 !important;
        line-height: 26px !important;
        padding-left: 0 !important;
    }

    .select2-container--classic .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        border-left: 1px solid #d1d5db !important;
    }

    .select2-container--classic .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        overflow: hidden;
    }

    .select2-container--classic .select2-results__option {
        padding: 8px 12px !important;
        font-size: 0.875rem !important;
    }

    .select2-container--classic .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5 !important;
        color: white !important;
    }

    .select2-container--classic .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.25rem !important;
        padding: 8px 10px !important;
        font-size: 0.875rem !important;
    }

    /* Dark mode styles for Select2 */
    .dark .select2-container--classic .select2-selection--single,
    .dark .select2-container--classic .select2-selection--multiple {
        background-color: #374151 !important;
        border-color: #4B5563 !important;
        color: #F9FAFB !important;
    }

    .dark .select2-container--classic .select2-selection__rendered {
        color: #F9FAFB !important;
    }

    .dark .select2-container--classic .select2-selection__arrow {
        background-color: #374151 !important;
        border-left-color: #4B5563 !important;
    }

    .dark .select2-container--classic .select2-dropdown {
        background-color: #1F2937 !important;
        border-color: #4B5563 !important;
    }

    .dark .select2-container--classic .select2-results__option {
        color: #F9FAFB !important;
    }

    .dark .select2-container--classic .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5 !important;
    }

    .dark .select2-container--classic .select2-search--dropdown .select2-search__field {
        background-color: #374151 !important;
        border-color: #4B5563 !important;
        color: #F9FAFB !important;
    }

    /* Improve the search box appearance */
    .select2-search__field:focus {
        outline: 2px solid #4f46e5 !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2) !important;
    }

    /* Ensure dropdown stays above modal */
    .modal-pelanggan-container .select2-container {
        z-index: 10000 !important;
    }

    /* Fix for Select2 positioning in modals */
    .select2-container--open .select2-dropdown--below {
        margin-top: 2px;
    }

    /* Beautify dropdown options */
    .sales-option {
        padding: 8px 12px !important;
        border-radius: 0.25rem !important;
        transition: all 0.2s ease;
    }

    .sales-option:hover {
        background-color: #f3f4f6 !important;
    }

    .dark .sales-option:hover {
        background-color: #374151 !important;
    }

    /* Clear button styling */
    .select2-container--classic .select2-selection__clear {
        margin-right: 10px !important;
        color: #6b7280 !important;
        font-size: 16px !important;
    }

    .dark .select2-container--classic .select2-selection__clear {
        color: #9ca3af !important;
    }
</style>
