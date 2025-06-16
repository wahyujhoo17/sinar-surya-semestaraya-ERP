<script>
    function customDropdown() {
        return {
            isOpen: false,
            selectedOption: null,
            selectedValue: '',
            searchTerm: '',
            options: [],
            highlightedIndex: -1,
            init() {
                // Initialize with empty options first
                this.options = [];

                // Create a safer access to the parent component
                this.initializeFromParent();

                // Also listen for parent data updates
                window.addEventListener('alpine:initialized', () => {
                    this.initializeFromParent();
                });

                // Create a custom event to listen for sales users updates
                window.addEventListener('sales-users-updated', (event) => {
                    if (event.detail && Array.isArray(event.detail.users)) {
                        console.log('Received sales users update event:', event.detail.users);
                        this.updateOptions(event.detail.users);

                        // If parent has selected a sales_id, use it
                        const parent = this.$el.closest('[x-data="modalPelangganManager()"]');
                        if (parent && parent.__x && parent.__x.$data && parent.__x.$data.formData) {
                            const salesId = parent.__x.$data.formData.sales_id;
                            if (salesId) {
                                console.log('Setting selected option from parent sales_id:', salesId);
                                this.setSelectedOption(salesId);
                            }
                        }
                    }
                });
            },

            /**
             * Safely access the parent component using Alpine's global store
             */
            initializeFromParent() {
                // Use a timeout to ensure the parent component is fully initialized
                setTimeout(() => {
                    try {
                        // First try to find the parent using a safer approach
                        // Access the parent through the DOM hierarchy
                        let parent = this.$el.closest('[x-data="modalPelangganManager()"]');

                        if (parent && parent.__x && parent.__x.$data) {
                            // Parent found through DOM traversal
                            this.processParentData(parent.__x.$data);
                            return;
                        }

                        // Fallback method if parent not found through DOM traversal
                        parent = document.querySelector('[x-data="modalPelangganManager()"]');
                        if (parent && parent.__x && parent.__x.$data) {
                            this.processParentData(parent.__x.$data);
                            return;
                        }

                        // If still not found, we'll try again later
                        setTimeout(() => this.initializeFromParent(), 200);
                    } catch (error) {
                        console.error('Error initializing custom dropdown:', error);
                    }
                }, 100);
            },
            /**
             * Process the parent data once we have it
             */
            processParentData(parentData) {
                if (parentData.salesUsers && Array.isArray(parentData.salesUsers)) {
                    this.updateOptions(parentData.salesUsers);

                    // Set selected option if sales_id is set
                    if (parentData.formData && parentData.formData.sales_id) {
                        const selectedId = parentData.formData.sales_id;
                        this.setSelectedOption(selectedId);
                    }
                }
            },

            /**
             * Set the selected option based on ID
             */
            setSelectedOption(id) {
                if (!id) {
                    this.selectedOption = null;
                    this.selectedValue = '';
                    return;
                }

                // Ensure ID is treated as string for comparison
                const stringId = String(id);
                this.selectedOption = this.options.find(opt => String(opt.id) === stringId);

                if (this.selectedOption) {
                    this.selectedValue = id;
                    console.log('Selected option set:', this.selectedOption);
                } else {
                    console.log('No matching option found for ID:', id);
                }
            },

            /**
             * Update the options array with sales users
             */
            updateOptions(salesUsers) {
                const currentSelectedId = this.selectedOption ? this.selectedOption.id : null;

                this.options = salesUsers.map(user => ({
                    id: user.id,
                    text: user.name || user.text // Support both name and text properties
                }));

                // If we had a selection before, try to maintain it
                if (currentSelectedId) {
                    this.setSelectedOption(currentSelectedId);
                }

                // Debug info
                console.log('Updated dropdown options:', this.options);
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
                    // Focus on search field with a slight delay to ensure it's rendered
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
                this.selectedOption = option;
                this.selectedValue = option.id;
                this.closeDropdown();

                // Update Alpine data in parent component using a safer approach
                this.updateParentSalesId(option.id);
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
                    // Try parent from DOM hierarchy first
                    let parent = this.$el.closest('[x-data="modalPelangganManager()"]');

                    if (parent && parent.__x && parent.__x.$data) {
                        parent.__x.$data.formData.sales_id = value;
                        return;
                    }

                    // Fallback to querySelector
                    parent = document.querySelector('[x-data="modalPelangganManager()"]');
                    if (parent && parent.__x && parent.__x.$data) {
                        parent.__x.$data.formData.sales_id = value;
                    }
                } catch (error) {
                    console.error('Error updating parent component:', error);
                }
            },

            navigateOptions(direction) {
                const optionsCount = this.filteredOptions.length;
                if (optionsCount === 0) return;

                if (direction === 'down') {
                    this.highlightedIndex = this.highlightedIndex < optionsCount - 1 ? this.highlightedIndex + 1 : 0;
                } else if (direction === 'up') {
                    this.highlightedIndex = this.highlightedIndex > 0 ? this.highlightedIndex - 1 : optionsCount - 1;
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
