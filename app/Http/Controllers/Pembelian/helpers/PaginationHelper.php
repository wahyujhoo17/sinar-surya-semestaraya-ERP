<?php

namespace App\Http\Controllers\Pembelian\helpers;

/**
 * Helper class for pagination functionality
 */
class PaginationHelper
{
    /**
     * Generate a simple HTML pagination as a fallback when view rendering fails
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @return string
     */
    public static function generateSimplePagination($paginator)
    {
        if (!$paginator->hasPages()) {
            return '';
        }

        $current = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $urlPrefix = $paginator->path() . '?page=';

        // Preserve existing query parameters
        $existingQuery = request()->query();
        unset($existingQuery['page']);
        $queryString = http_build_query($existingQuery);
        $separator = empty($queryString) ? '' : '&';

        // Construct URLs with all parameters
        $prevUrl = $urlPrefix . ($current - 1) . $separator . $queryString;
        $nextUrl = $urlPrefix . ($current + 1) . $separator . $queryString;

        $html = '<div class="mt-4 flex justify-between items-center">';
        $html .= '<div class="text-sm text-gray-600">Showing page ' . $current . ' of ' . $lastPage . ' (' . $paginator->total() . ' records)</div>';
        $html .= '<div class="flex space-x-2">';

        // Previous button
        if ($current > 1) {
            $html .= '<a href="' . $prevUrl . '" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Previous</a>';
        } else {
            $html .= '<span class="px-3 py-1 bg-gray-100 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">Previous</span>';
        }

        // Next button
        if ($current < $lastPage) {
            $html .= '<a href="' . $nextUrl . '" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Next</a>';
        } else {
            $html .= '<span class="px-3 py-1 bg-gray-100 border border-gray-300 rounded text-sm text-gray-400 cursor-not-allowed">Next</span>';
        }

        $html .= '</div></div>';

        return $html;
    }
}
