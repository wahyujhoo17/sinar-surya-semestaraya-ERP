<div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
    <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-4 md:mb-0 text-sm text-gray-600 dark:text-gray-400">
            <span id="showing-info">
                Menampilkan {{ $jurnals->firstItem() ?? 0 }} hingga
                {{ $jurnals->lastItem() ?? 0 }} dari
                {{ $jurnals->total() }} transaksi
            </span>
        </div>
        @if ($jurnals->hasPages())
            <div>
                {{ $jurnals->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
