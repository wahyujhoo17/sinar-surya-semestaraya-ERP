<!-- Fix for the Performa Pipeline Section -->

<!-- Conversion Rate Indicators -->
<div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
        <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Keseluruhan (Baru → Customer)</h3>
        <div class="flex justify-between items-center">
            <div class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-500 to-green-500"
                    :style="`width: ${conversionRates.overall}%`"></div>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                x-text="`${conversionRates.overall}%`">0%</span>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
        <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Baru → Tertarik</h3>
        <div class="flex justify-between items-center">
            <div class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                <div class="absolute top-0 left-0 h-full bg-yellow-500"
                    :style="`width: ${conversionRates.prospekToTertarik}%`"></div>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                x-text="`${conversionRates.prospekToTertarik}%`">0%</span>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
        <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Tertarik → Negosiasi</h3>
        <div class="flex justify-between items-center">
            <div class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                <div class="absolute top-0 left-0 h-full bg-blue-500"
                    :style="`width: ${conversionRates.tertarikToNegosiasi}%`"></div>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                x-text="`${conversionRates.tertarikToNegosiasi}%`">0%</span>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 transition hover:shadow-md">
        <h3 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Negosiasi → Customer</h3>
        <div class="flex justify-between items-center">
            <div class="relative w-full h-3 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                <div class="absolute top-0 left-0 h-full bg-indigo-500"
                    :style="`width: ${conversionRates.negosiasiToCustomer}%`"></div>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white"
                x-text="`${conversionRates.negosiasiToCustomer}%`">0%</span>
        </div>
    </div>
</div>

<!-- Pipeline Progress Flow -->
<div class="mb-6 relative hidden md:block">
    <div class="absolute top-4 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700"></div>
    <div class="flex justify-between">
        <!-- Baru Stage -->
        <div class="relative text-center w-1/4">
            <div
                class="w-8 h-8 mx-auto bg-yellow-500 rounded-full text-white flex items-center justify-center z-10 relative">
                <span class="text-xs font-bold" x-text="stats.baru"></span>
            </div>
            <div class="mt-2">
                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Baru</span>
            </div>
        </div>

        <!-- Tertarik Stage -->
        <div class="relative text-center w-1/4">
            <div
                class="w-8 h-8 mx-auto bg-blue-500 rounded-full text-white flex items-center justify-center z-10 relative">
                <span class="text-xs font-bold" x-text="stats.tertarik"></span>
            </div>
            <div class="mt-2">
                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Tertarik</span>
                <span class="block text-xs text-blue-600 dark:text-blue-400"
                    x-text="`${conversionRates.prospekToTertarik}%`"></span>
            </div>
        </div>

        <!-- Negosiasi Stage -->
        <div class="relative text-center w-1/4">
            <div
                class="w-8 h-8 mx-auto bg-indigo-500 rounded-full text-white flex items-center justify-center z-10 relative">
                <span class="text-xs font-bold" x-text="stats.negosiasi"></span>
            </div>
            <div class="mt-2">
                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Negosiasi</span>
                <span class="block text-xs text-indigo-600 dark:text-indigo-400"
                    x-text="`${conversionRates.tertarikToNegosiasi}%`"></span>
            </div>
        </div>

        <!-- Customer Stage -->
        <div class="relative text-center w-1/4">
            <div
                class="w-8 h-8 mx-auto bg-green-500 rounded-full text-white flex items-center justify-center z-10 relative">
                <span class="text-xs font-bold" x-text="stats.menjadi_customer"></span>
            </div>
            <div class="mt-2">
                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Customer</span>
                <span class="block text-xs text-green-600 dark:text-green-400"
                    x-text="`${conversionRates.negosiasiToCustomer}%`"></span>
            </div>
        </div>
    </div>
</div>

<!-- Chart Canvas -->
<div class="h-60 w-full">
    <canvas id="pipelineChart"></canvas>
</div>
