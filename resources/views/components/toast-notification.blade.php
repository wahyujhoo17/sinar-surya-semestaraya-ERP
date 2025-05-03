@props(['id' => 'toast-notification'])

<div
    x-data="{
        notifications: [],
        add(notification) {
            notification.id = Date.now();
            this.notifications.push(notification);
            setTimeout(() => this.remove(notification.id), notification.timeout || 5000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(notification => notification.id !== id);
        },
        sanitizeHtml(html) {
            // Fungsi sederhana untuk memformat tag strong saja
            return html ? html.replace(/&lt;strong&gt;/g, '<strong>').replace(/&lt;\/strong&gt;/g, '</strong>') : '';
        }
    }"
    @notify.window="add($event.detail)"
    class="notification-toast fixed inset-0 flex flex-col items-end justify-start px-4 py-6 pointer-events-none sm:p-6 z-50 space-y-4"
    id="{{ $id }}"
    style="top: 60px"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto border-l-4 border-transparent"
            :class="{
                'border-green-500': notification.type === 'success',
                'border-red-500': notification.type === 'error',
                'border-blue-500': notification.type === 'info',
                'border-yellow-500': notification.type === 'warning'
            }"
        >
            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <!-- Success Icon -->
                    <template x-if="notification.type === 'success'">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>
                    
                    <!-- Error Icon -->
                    <template x-if="notification.type === 'error'">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>
                    
                    <!-- Info Icon -->
                    <template x-if="notification.type === 'info'">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6" />
                            </svg>
                        </div>
                    </template>
                    
                    <!-- Warning Icon -->
                    <template x-if="notification.type === 'warning'">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </template>
                </div>
                
                <div class="ml-3 w-0 flex-1">
                    <div class="text-sm text-gray-900 dark:text-gray-100 font-medium" x-text="notification.title || notification.type.charAt(0).toUpperCase() + notification.type.slice(1)"></div>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span x-html="notification.message"></span>
                    </div>
                </div>
                
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="remove(notification.id)" class="inline-flex text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div 
                class="h-1 bg-transparent"
                :class="{
                    'bg-green-100 dark:bg-green-900/50': notification.type === 'success',
                    'bg-red-100 dark:bg-red-900/50': notification.type === 'error',
                    'bg-blue-100 dark:bg-blue-900/50': notification.type === 'info',
                    'bg-yellow-100 dark:bg-yellow-900/50': notification.type === 'warning'
                }"
            >
                <div
                    class="h-full transition-all duration-300 ease-linear"
                    :class="{
                        'bg-green-500 dark:bg-green-400': notification.type === 'success',
                        'bg-red-500 dark:bg-red-400': notification.type === 'error',
                        'bg-blue-500 dark:bg-blue-400': notification.type === 'info',
                        'bg-yellow-500 dark:bg-yellow-400': notification.type === 'warning'
                    }"
                    :style="`width: 100%; animation: progress-bar ${notification.timeout || 5000}ms linear forwards;`"
                ></div>
            </div>
        </div>
    </template>
</div>

<style>
@keyframes progress-bar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}
</style>