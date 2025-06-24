<script>
    // Ensure customDropdown is defined globally for Alpine.js
    window.customDropdown = function customDropdown() {
        return {
            isOpen: false,
            selectedOption: null,
            selectedValue: '',
            searchTerm: '',
            options: [],
            highlightedIndex: -1,
            initialized: false,
            retryCount: 0,
            maxRetries: 5,
            init() {
                // Initialize with empty options first
                this.options = [];

                // Use $nextTick to ensure DOM is ready
                this.$nextTick(() => {
                    // Try to get the parent immediately
                    const parent = this.getParentComponent();
                    if (parent && parent.formData && parent.formData.sales_id) {
                        // Store the ID for when options are loaded
                        this._pendingSelectionId = String(parent.formData.sales_id);
                    }

                    // Create a safer access to the parent component after a short delay
                    setTimeout(() => this.initializeFromParent(), 100);
                });

                // Listen for Alpine initialization event
                window.addEventListener('alpine:initialized', () => {
                    // Use a longer timeout to ensure parent component is fully initialized
                    setTimeout(() => this.initializeFromParent(), 200);
                });

                // Create a custom event to listen for sales users updates
                window.addEventListener('sales-users-updated', (event) => {
                    if (event.detail && Array.isArray(event.detail.users)) {
                        // Check if a selectedId was included in the event
                        if (event.detail.selectedId) {
                            this._pendingSelectionId = String(event.detail.selectedId);
                        }

                        // Update options with the new sales users
                        this.updateOptions(event.detail.users);

                        // After updating options, if we didn't select an option yet and there was no selectedId in the event,
                        // try to set the selected option from parent
                        if (!this.selectedOption && !event.detail.selectedId) {
                            this.trySetSelectionFromParent();
                        }
                    }
                });

                // Listen for the debug event to fix dropdown
                window.addEventListener('sales-debug', (event) => {
                    this.debugAndFixDropdown(event.detail);
                });
            },

            /**
             * Debug and fix the dropdown state
             */
            debugAndFixDropdown(detail) {
                // Get current state
                const state = {
                    selectedOption: this.selectedOption,
                    options: this.options,
                    selectedValue: this.selectedValue,
                    parentSalesId: null,
                    isEdit: detail.isEdit || false
                };

                // Try to get parent sales_id
                try {
                    const parent = this.getParentComponent();
                    if (parent && parent.formData) {
                        state.parentSalesId = parent.formData.sales_id;
                    }
                } catch (e) {}

                // If we're in edit mode, we should definitely have a selection
                if (state.isEdit && state.parentSalesId) {
                    // Directly select the option without going through any event cycles
                    if (this.options.length > 0) {
                        const matchingOption = this.options.find(opt => String(opt.id) === String(state
                            .parentSalesId));

                        if (matchingOption) {
                            this.selectedOption = matchingOption;
                            this.selectedValue = String(state.parentSalesId);

                            // Update parent just to be sure
                            this.updateParentSalesId(String(state.parentSalesId));
                        }
                    } else {
                        // Request a refresh of sales users
                        try {
                            const parent = this.getParentComponent();
                            if (parent && typeof parent.fetchSalesUsers === 'function') {
                                this._pendingSelectionId = String(state.parentSalesId);
                                parent.fetchSalesUsers();
                            }
                        } catch (e) {}
                    }
                }
            },
            /**
             * Try to set selection from parent's sales_id
             */
            trySetSelectionFromParent() {
                try {
                    // Get parent through DOM hierarchy
                    const parent = this.getParentComponent();

                    if (parent && parent.formData) {
                        const salesId = parent.formData.sales_id;

                        if (salesId) {
                            // Check if we already have the correct selection
                            const currentId = this.selectedOption ? String(this.selectedOption.id) : null;
                            if (currentId === String(salesId)) {
                                return;
                            }

                            // If not matching, update the selection
                            this.setSelectedOption(salesId);

                            // For debugging - store globally
                            window._lastSelectedSalesId = salesId;
                        }
                    }
                } catch (error) {}
            },

            /**
             * Get parent component using multiple methods
             */
            getParentComponent() {
                try {
                    // First try to get the parent through DOM hierarchy
                    let parent = this.$el.closest('[x-data="modalPelangganManager()"]');

                    if (parent && parent.__x && parent.__x.$data) {
                        return parent.__x.$data;
                    }

                    // Fallback to querySelector
                    parent = document.querySelector('[x-data="modalPelangganManager()"]');
                    if (parent && parent.__x && parent.__x.$data) {
                        return parent.__x.$data;
                    }

                    // Additional fallback for Alpine.js 3.x
                    if (window.Alpine) {
                        // Try to find it by walking up the DOM
                        let currentEl = this.$el;
                        while (currentEl && currentEl !== document.body) {
                            if (currentEl.__x) {
                                const data = currentEl.__x.getUnobservedData ?
                                    currentEl.__x.getUnobservedData() :
                                    currentEl.__x.$data;

                                // Check if this is the modal component
                                if (data && typeof data.fetchSalesUsers === 'function') {
                                    return data;
                                }
                            }
                            currentEl = currentEl.parentElement;
                        }
                    }

                    return null;
                } catch (error) {
                    return null;
                }
            },

            /**
             * Safely access the parent component
             */
            initializeFromParent() {
                try {
                    // Check if already initialized
                    if (this.initialized && this.options.length > 0) {
                        return;
                    }

                    const parent = this.getParentComponent();

                    if (parent) {
                        this.processParentData(parent);
                        this.initialized = true;
                        this.retryCount = 0;
                    } else {
                        // Not found yet, retry with exponential backoff
                        this.retryCount++;
                        if (this.retryCount <= this.maxRetries) {
                            const delay = Math.min(100 * Math.pow(2, this.retryCount),
                                2000); // Exponential backoff with 2 second max
                            setTimeout(() => this.initializeFromParent(), delay);
                        }
                    }
                } catch (error) {}
            },

            /**
             * Process the parent data once we have it
             */
            processParentData(parentData) {
                // Process sales users if available
                if (parentData.salesUsers && Array.isArray(parentData.salesUsers)) {
                    this.updateOptions(parentData.salesUsers);
                }

                // Set selected option if sales_id is set in formData
                if (parentData.formData && parentData.formData.sales_id) {
                    const selectedId = parentData.formData.sales_id;
                    // Use a small delay to ensure options are loaded
                    setTimeout(() => this.setSelectedOption(selectedId), 50);
                }
            },
            /**
             * Set the selected option based on ID with improved handling
             */
            setSelectedOption(id) {
                if (!id) {
                    this.selectedOption = null;
                    this.selectedValue = '';
                    return;
                }

                // Ensure ID is treated as string for comparison
                const stringId = String(id);

                // Check if dropdown was initialized with enough options
                if (this.options.length === 0) {
                    this._pendingSelectionId = stringId;

                    // Force a fetch of the sales users if this happens in edit mode
                    try {
                        const parent = this.getParentComponent();
                        if (parent && typeof parent.fetchSalesUsers === 'function') {
                            parent.fetchSalesUsers();

                            // Set a flag to force reselection after fetch
                            this._forceReselect = true;
                        }
                    } catch (e) {}
                    return;
                }

                // Find the matching option
                this.selectedOption = this.options.find(opt => String(opt.id) === stringId);

                if (this.selectedOption) {
                    // Set the selected value and update hidden input
                    this.selectedValue = stringId;

                    // Make sure the value is synced to the parent
                    this.updateParentSalesId(stringId);

                    // Manually trigger an event for the hidden input
                    const inputEl = this.$el.querySelector('#sales_id_input');
                    if (inputEl) {
                        inputEl.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                } else {
                    // If we have an ID but no matching option, request a refresh
                    try {
                        const parent = this.getParentComponent();
                        if (parent && typeof parent.fetchSalesUsers === 'function') {
                            parent.fetchSalesUsers();

                            // Store the ID for when the options are refreshed
                            this._pendingSelectionId = stringId;
                        }
                    } catch (e) {}
                }
            },

            /**
             * Update the options array with sales users
             */
            updateOptions(salesUsers) {
                if (!Array.isArray(salesUsers) || salesUsers.length === 0) {
                    return;
                }

                // Remember current selection
                const currentSelectedId = this.selectedOption ? this.selectedOption.id : null;
                const pendingId = this._pendingSelectionId;
                const forceReselect = this._forceReselect;

                // Normalize options format (make a copy to avoid reference issues)
                this.options = salesUsers.map(user => ({
                    id: String(user.id), // Ensure ID is string
                    text: user.name || user.text ||
                        `User ${user.id}` // Support both name and text properties
                }));

                // Try to get parent sales_id for edit mode - this will be our primary source of truth
                let parentSalesId = null;
                try {
                    const parent = this.getParentComponent();
                    if (parent && parent.formData && parent.formData.sales_id) {
                        parentSalesId = String(parent.formData.sales_id);
                    }
                } catch (e) {}

                // If we're in edit mode and have options, we should always try to select the parent's sales_id
                if (parentSalesId && this.options.length > 0) {
                    // Check if the option exists
                    const matchingOption = this.options.find(opt => String(opt.id) === parentSalesId);
                    if (matchingOption) {
                        this.selectedOption = matchingOption;
                        this.selectedValue = parentSalesId;

                        // Clear pending flags since we've made a selection
                        this._pendingSelectionId = null;
                        this._forceReselect = false;

                        return;
                    }
                }

                // If we couldn't select using parent ID, fall back to other methods
                if (pendingId) {
                    setTimeout(() => this.setSelectedOption(pendingId), 10);

                    // Clear pending selection to avoid loops
                    this._pendingSelectionId = null;
                } else if (currentSelectedId) {
                    setTimeout(() => this.setSelectedOption(currentSelectedId), 10);
                }

                // Clear force reselect flag
                this._forceReselect = false;
            },

            get filteredOptions() {
                if (!this.searchTerm) return this.options;
                const searchLower = this.searchTerm.toLowerCase();
                return this.options.filter(option =>
                    option.text && option.text.toLowerCase().includes(searchLower)
                );
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.highlightedIndex = -1;
                    // Focus on search field with a slight delay
                    setTimeout(() => {
                        const searchField = this.$el.querySelector('input[type="text"]');
                        if (searchField) {
                            searchField.focus();
                        }
                    }, 50);
                }
            },

            closeDropdown() {
                this.isOpen = false;
                this.searchTerm = '';
                this.highlightedIndex = -1;
            },

            selectOption(option) {
                if (!option) return;

                this.selectedOption = option;
                this.selectedValue = option.id;
                this.closeDropdown();

                // Update Alpine data in parent component
                this.updateParentSalesId(option.id);

                // Manually trigger an event for the hidden input
                const inputEl = this.$el.querySelector('#sales_id_input');
                if (inputEl) {
                    inputEl.value = option.id;
                    inputEl.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            },

            clearSelection(event) {
                if (event) {
                    event.stopPropagation();
                }
                this.selectedOption = null;
                this.selectedValue = '';

                // Update Alpine data in parent component
                this.updateParentSalesId('');
            },

            /**
             * Safely update the parent's sales_id
             */
            updateParentSalesId(value) {
                try {
                    const parent = this.getParentComponent();

                    if (parent) {
                        // Update the parent formData property
                        if (parent.formData) {
                            parent.formData.sales_id = value;

                            // For debugging
                            window._lastSetSalesId = value;
                        }
                    }
                } catch (error) {}
            },

            navigateOptions(direction) {
                const optionsCount = this.filteredOptions.length;
                if (optionsCount === 0) return;

                if (direction === 'down') {
                    this.highlightedIndex = this.highlightedIndex < optionsCount - 1 ? this.highlightedIndex + 1 :
                        0;
                } else if (direction === 'up') {
                    this.highlightedIndex = this.highlightedIndex > 0 ? this.highlightedIndex - 1 : optionsCount -
                        1;
                }

                // Ensure the highlighted option is visible in the scrollable area
                this.$nextTick(() => {
                    const items = this.$el.querySelectorAll('.custom-dropdown-item');
                    if (items && items.length > this.highlightedIndex && this.highlightedIndex >= 0) {
                        const highlightedEl = items[this.highlightedIndex];
                        if (highlightedEl) {
                            highlightedEl.scrollIntoView({
                                block: 'nearest'
                            });
                        }
                    }
                });
            },

            selectHighlightedOption() {
                if (this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredOptions.length) {
                    this.selectOption(this.filteredOptions[this.highlightedIndex]);
                }
            }
        };
    }
</script>
