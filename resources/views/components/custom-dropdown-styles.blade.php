<style>
    /* Custom dropdown styling */
    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .custom-dropdown-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border-width: 1px;
        font-size: 0.875rem;
        line-height: 1.25rem;
        transition: all 0.15s ease-in-out;
        min-height: 38px;
        /* Ensure consistent height */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .custom-dropdown-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.25);
    }

    .custom-dropdown-menu {
        position: absolute;
        z-index: 9999;
        /* Ensure higher z-index to appear above other modal elements */
        margin-top: 0.25rem;
        width: 100%;
        max-height: 15rem;
        overflow-y: auto;
        border-radius: 0.375rem;
        border-width: 1px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .custom-dropdown-item {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .custom-dropdown-item:hover {
        background-color: #f3f4f6;
    }

    .custom-dropdown-item.selected {
        background-color: #e5e7eb;
        font-weight: 500;
    }

    /* Dark mode overrides */
    .dark .custom-dropdown-input {
        background-color: #374151;
        border-color: #4B5563;
        color: #F9FAFB;
    }

    .dark .custom-dropdown-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.4);
    }

    .dark .custom-dropdown-menu {
        background-color: #1F2937;
        border-color: #4B5563;
    }

    .dark .custom-dropdown-item {
        color: #F9FAFB;
    }

    .dark .custom-dropdown-item:hover {
        background-color: #374151;
    }

    .dark .custom-dropdown-item.selected {
        background-color: #4B5563;
    }

    /* Placeholder styling */
    .placeholder {
        color: #9CA3AF;
    }

    .dark .placeholder {
        color: #6B7280;
    }

    /* Make sure the dropdown appears above other modal content */
    .modal-pelanggan-container .custom-dropdown-menu {
        z-index: 9999;
    }
</style>
