<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Select2 Test</title>

    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Select2 Styling with Dark Mode Support */
        .select2-container--default .select2-selection--single {
            width: 100%;
            border-radius: 0.375rem;
            border-color: #d1d5db;
            background-color: white;
            color: #1f2937;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            height: 38px;
            padding-top: 4px;
        }

        .dark .select2-container--default .select2-selection--single {
            border-color: #4B5563;
            background-color: #374151;
            color: white;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937;
            line-height: 30px;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
        }

        .select2-dropdown {
            background-color: white;
            border-color: #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .dark .select2-dropdown {
            background-color: #374151;
            border-color: #4B5563;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #d1d5db;
        }

        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #4B5563;
            background-color: #1F2937;
            color: white;
        }

        .select2-container--default .select2-results__option {
            padding: 6px 12px;
            color: #1f2937;
        }

        .dark .select2-container--default .select2-results__option {
            color: #D1D5DB;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
            color: white;
        }

        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3B82F6;
            color: white;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #dbeafe;
            color: #1e3a8a;
        }

        .dark .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(59, 130, 246, 0.5);
            color: white;
        }

        /* Make sure Select2 dropdowns are on top of modals */
        .select2-container {
            z-index: 9999999 !important;
        }

        /* Fix dark mode dropdown styling */
        .select2-dropdown {
            z-index: 20000 !important;
            border-radius: 0.375rem !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
        }

        .dark .select2-dropdown {
            background-color: #374151 !important;
            color: #fff !important;
            border-color: #4B5563 !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #1F2937 !important;
            color: #fff !important;
            border-color: #4B5563 !important;
        }

        .dark .select2-results__option {
            color: #D1D5DB !important;
        }

        .dark .select2-results__option[aria-selected="true"] {
            background-color: rgba(59, 130, 246, 0.5) !important;
            color: #fff !important;
        }

        .dark .select2-results__option--highlighted[aria-selected] {
            background-color: #3B82F6 !important;
            color: #fff !important;
        }

        /* Fix the dropdown appearance in all modes */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            padding-top: 4px !important;
        }

        /* Fix for dropdown container visibility */
        .select2-container--open .select2-dropdown {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        [x-cloak] {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 p-8" x-data="testForm()">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Select2 Dropdown Test</h1>

        <div class="space-y-4">
            <!-- Test Select Outside Modal -->
            <div>
                <label for="outside_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Dropdown Outside Modal
                </label>
                <select id="outside_select" class="w-full">
                    <option value="">Select an option</option>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                    <option value="5">Option 5</option>
                </select>
            </div>

            <!-- Button to Open Modal -->
            <div class="mt-6">
                <button @click="showModal = true" type="button"
                    class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Open Modal with Dropdown
                </button>
            </div>

            <!-- Toggle Dark Mode -->
            <div class="mt-4">
                <button @click="toggleDarkMode" type="button"
                    class="w-full px-4 py-2 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <span x-text="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 overflow-y-auto z-50"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="showModal = false"></div>

            <!-- Modal Content -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full p-6 z-10">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Modal with Select2</h2>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Select Inside Modal -->
                <div>
                    <label for="modal_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dropdown Inside Modal
                    </label>
                    <select id="modal_select" class="w-full">
                        <option value="">Select an option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                        <option value="5">Option 5</option>
                        <option value="6">Option 6</option>
                        <option value="7">Option 7</option>
                        <option value="8">Option 8</option>
                        <option value="9">Option 9</option>
                        <option value="10">Option 10</option>
                    </select>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="showModal = false" class="px-4 py-2 bg-gray-600 text-white rounded-md">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testForm() {
            return {
                showModal: false,
                darkMode: localStorage.getItem('darkMode') === 'true',

                init() {
                    // Apply dark mode if saved
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }

                    // Initialize outside select
                    this.$nextTick(() => {
                        $('#outside_select').select2({
                            placeholder: 'Select an option',
                            width: '100%',
                            dropdownParent: $('body')
                        });
                    });

                    // Initialize modal select when modal opens
                    this.$watch('showModal', (isOpen) => {
                        if (isOpen) {
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    // Destroy previous instance if it exists
                                    if ($('#modal_select').hasClass('select2-hidden-accessible')) {
                                        $('#modal_select').select2('destroy');
                                    }

                                    $('#modal_select').select2({
                                        placeholder: 'Select an option',
                                        width: '100%',
                                        dropdownParent: $('body')
                                    });
                                }, 100);
                            });
                        }
                    });
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);

                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }
    </script>
</body>

</html>
