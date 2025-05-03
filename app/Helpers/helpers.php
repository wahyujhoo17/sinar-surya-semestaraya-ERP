<?php
// filepath: app/Helpers/helpers.php


if (!function_exists('isMenuActive')) {
    /**
     * Check if the current route matches any of the given patterns.
     *
     * @param array|string $patterns Route name patterns (e.g., 'users.*' or ['users.*', 'profile'])
     * @param string $activeClass CSS class to return if active (optional)
     * @param string $inactiveClass CSS class to return if inactive (optional)
     * @return bool|string Returns boolean if no classes provided, otherwise the corresponding class string.
     */
    function isMenuActive($patterns, $activeClass = null, $inactiveClass = null)
    {
        $patterns = (array) $patterns;
        $isActive = false;

        // Use the fully qualified namespace for Route facade
        foreach ($patterns as $pattern) {
            if (\Illuminate\Support\Facades\Route::is($pattern)) {
                $isActive = true;
                break;
            }
        }

        if ($activeClass === null && $inactiveClass === null) {
            return $isActive;
        }

        return $isActive ? $activeClass : $inactiveClass;
    }
}

// Add any other custom helper functions here...