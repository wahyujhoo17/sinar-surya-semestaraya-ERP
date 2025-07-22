{{-- Toast Notification Component --}}
<div x-data="{
    toasts: [],
    nextId: 1,

    addToast(message, type = 'success', duration = 5000) {
        const id = this.nextId++;
        const toast = {
            id: id,
            message: message,
            type: type,
            show: false
        };

        this.toasts.push(toast);

        // Trigger show animation after a small delay
        setTimeout(() => {
            const toastElement = this.toasts.find(t => t.id === id);
            if (toastElement) {
                toastElement.show = true;
            }
        }, 100);

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                this.removeToast(id);
            }, duration);
        }

        return id;
    },

    removeToast(id) {
        const toastIndex = this.toasts.findIndex(t => t.id === id);
        if (toastIndex > -1) {
            this.toasts[toastIndex].show = false;
            // Remove from array after animation
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 300);
        }
    },

    getToastClass(type) {
        const baseClass = 'flex items-center w-full max-w-sm p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800';
        switch (type) {
            case 'success':
                return baseClass + ' border-l-4 border-green-500';
            case 'error':
                return baseClass + ' border-l-4 border-red-500';
            case 'warning':
                return baseClass + ' border-l-4 border-yellow-500';
            case 'info':
                return baseClass + ' border-l-4 border-blue-500';
            default:
                return baseClass;
        }
    },

    getIconClass(type) {
        switch (type) {
            case 'success':
                return 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200';
            case 'error':
                return 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200';
            case 'warning':
                return 'text-yellow-500 bg-yellow-100 dark:bg-yellow-800 dark:text-yellow-200';
            case 'info':
                return 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200';
            default:
                return 'text-gray-500 bg-gray-100 dark:bg-gray-800 dark:text-gray-200';
        }
    },

    getIconPath(type) {
        switch (type) {
            case 'success':
                return 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z';
            case 'error':
                return 'M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z';
            case 'warning':
                return 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z';
            case 'info':
                return 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z';
            default:
                return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        }
    }
}"
    @show-toast.window="addToast($event.detail.message, $event.detail.type || 'success', $event.detail.duration || 5000)"
    class="fixed top-4 right-4 z-50 space-y-2">

    <template x-for="toast in toasts" :key="toast.id">
        <div :class="getToastClass(toast.type)" x-show="toast.show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" role="alert">

            <div
                :class="'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg ' + getIconClass(toast.type)">
                <svg :class="'w-5 h-5'" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path :d="getIconPath(toast.type)" />
                </svg>
                <span class="sr-only" x-text="toast.type + ' icon'"></span>
            </div>

            <div class="ml-3 text-sm font-normal" x-text="toast.message"></div>

            <button type="button" @click="removeToast(toast.id)"
                class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </template>
</div>

{{-- Global Toast Helper Functions --}}
<script>
    // Global function to show toast notifications
    window.showToast = function(message, type = 'success', duration = 5000) {
        window.dispatchEvent(new CustomEvent('show-toast', {
            detail: {
                message,
                type,
                duration
            }
        }));
    };

    // Shorthand functions for different types
    window.showSuccess = function(message, duration = 5000) {
        window.showToast(message, 'success', duration);
    };

    window.showError = function(message, duration = 7000) {
        window.showToast(message, 'error', duration);
    };

    window.showWarning = function(message, duration = 6000) {
        window.showToast(message, 'warning', duration);
    };

    window.showInfo = function(message, duration = 5000) {
        window.showToast(message, 'info', duration);
    };
</script>
