@props(['id' => 'alert-modal'])

<div
    x-data="{ 
        open: false,
        title: '',
        message: '',
        type: 'info',
        confirmText: 'OK',
        cancelText: 'Batal',
        onConfirm: () => {},
        onCancel: () => {},
        showConfirm: true,
        showCancel: false
    }"
    @alert-modal.window="
        open = true;
        title = $event.detail.title || '';
        message = $event.detail.message || '';
        type = $event.detail.type || 'info';
        confirmText = $event.detail.confirmText || 'OK';
        cancelText = $event.detail.cancelText || 'Batal';
        onConfirm = $event.detail.onConfirm || function(){};
        onCancel = $event.detail.onCancel || function(){};
        showConfirm = $event.detail.showConfirm !== undefined ? $event.detail.showConfirm : true;
        showCancel = $event.detail.showCancel !== undefined ? $event.detail.showCancel : false;
    "
    id="{{ $id }}"
    class="fixed z-50 inset-0 overflow-y-auto" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
    x-show="open"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            @click="if(!$event.target.closest('.modal-content')) { open = false; onCancel(); }"
            aria-hidden="true"
            x-cloak
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div 
            x-show="open" 
            x-cloak
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="modal-content inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
        >
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                        :class="{
                            'bg-red-100 dark:bg-red-900/30': type === 'error',
                            'bg-green-100 dark:bg-green-900/30': type === 'success',
                            'bg-blue-100 dark:bg-blue-900/30': type === 'info',
                            'bg-yellow-100 dark:bg-yellow-900/30': type === 'warning'
                        }">
                        <!-- Success Icon -->
                        <template x-if="type === 'success'">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        
                        <!-- Error Icon -->
                        <template x-if="type === 'error'">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </template>
                        
                        <!-- Info Icon -->
                        <template x-if="type === 'info'">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        
                        <!-- Warning Icon -->
                        <template x-if="type === 'warning'">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </template>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" x-text="title"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-html="message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 border-t dark:border-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <template x-if="showConfirm">
                    <button @click="open = false; onConfirm();" type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                        :class="{
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'error',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500': type === 'success',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': type === 'info',
                            'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': type === 'warning'
                        }"
                        x-text="confirmText">
                    </button>
                </template>
                <template x-if="showCancel">
                    <button @click="open = false; onCancel();" type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                        x-text="cancelText">
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>