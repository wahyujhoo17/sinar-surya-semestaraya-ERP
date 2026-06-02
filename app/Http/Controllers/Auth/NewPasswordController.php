<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Support\Facades\Http;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // Get client security details (highly accurate client IP detection)
                $ip = null;
                if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
                } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                    $ip = trim($ips[0]);
                } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
                    $ip = $_SERVER['HTTP_X_REAL_IP'];
                } else {
                    $ip = $request->ip();
                }

                $userAgent = $request->userAgent();
                $location = 'Lokasi Tidak Diketahui';

                // Detect if IP is local/private loopback
                $isLocal = ($ip === '127.0.0.1' || $ip === '::1' || empty($ip) || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0);
                
                $queryIp = $ip;
                if ($isLocal) {
                    try {
                        // Dynamically fetch the Macbook's real external public IP from internet!
                        $ipResponse = Http::timeout(3)->get("https://api.ipify.org");
                        if ($ipResponse->successful()) {
                            $resolvedIp = trim($ipResponse->body());
                            if (filter_var($resolvedIp, FILTER_VALIDATE_IP)) {
                                $queryIp = $resolvedIp;
                                $ip = $resolvedIp; // Display the real public IP!
                            }
                        }
                    } catch (\Exception $e) {
                        // Fallback to demo Indonesian IP if offline/error
                        $queryIp = '103.136.25.1';
                    }
                }

                try {
                    // Try ip-api.com first (highly robust, no rate-limiting issues on free tier)
                    $response = Http::timeout(3)->get("http://ip-api.com/json/{$queryIp}");
                    if ($response->successful()) {
                        $data = $response->json();
                        if (($data['status'] ?? '') === 'success') {
                            $location = ($data['city'] ?? '') . ', ' . ($data['regionName'] ?? '') . ', ' . ($data['country'] ?? '');
                        }
                    }
                } catch (\Exception $e) {
                    // Fallback to ipapi.co
                    try {
                        $response = Http::timeout(3)->get("https://ipapi.co/{$queryIp}/json/");
                        if ($response->successful()) {
                            $data = $response->json();
                            if (!empty($data['city'])) {
                                $location = $data['city'] . ', ' . ($data['region'] ?? '') . ', ' . ($data['country_name'] ?? '');
                            }
                        }
                    } catch (\Exception $ex) {
                        // Keep fallback
                    }
                }

                // Send security notification
                $user->notify(new PasswordChangedNotification($ip, $location, $userAgent));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
