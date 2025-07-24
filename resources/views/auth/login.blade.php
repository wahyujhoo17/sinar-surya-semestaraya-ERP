<x-guest-layout>
    <div
        class="flex min-h-screen bg-gradient-to-br from-primary-50 via-blue-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Left side - Logo and company image -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-500 to-blue-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="p-12 flex flex-col justify-between relative z-10 h-full">
                <div>
                    <div class="flex items-center">
                        <!-- Logo di sebelah kiri, ukuran disesuaikan -->
                        <img src="{{ asset('img/Logo.png') }}" alt="Logo"
                            class="h-12 w-12 object-contain flex-shrink-0">
                        <div class="ml-4">
                            <!-- Nama perusahaan -->
                            <h1 class="text-white text-2xl font-bold leading-tight">Sinar Surya Semestaraya</h1>
                            <!-- ERP System text di bawah nama perusahaan -->
                            <p class="text-white/80 text-sm">Enterprise Resource Planning System</p>
                        </div>
                    </div>
                </div>

                <div class="text-white space-y-6">
                    <!-- Diganti dengan visi/misi -->
                    <h2 class="text-3xl font-bold">Menjadi Perusahaan Terdepan</h2>
                    <p class="text-white/80 text-lg">Kami berkomitmen untuk memberikan solusi berkualitas tinggi dan
                        inovasi berkelanjutan untuk kepuasan pelanggan.</p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white mb-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="text-white font-semibold">Integritas & Kualitas</h3>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white mb-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-white font-semibold">Efisiensi & Inovasi</h3>
                        </div>
                    </div>
                </div>

                <div class="text-white/70 text-sm">
                    &copy; {{ date('Y') }} PT Sinar Surya Semestaraya.<br>All rights reserved
                </div>
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-8">
            <div class="w-full max-w-md">
                <!-- Logo di tengah atas untuk mobile, ukuran disesuaikan -->
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Masuk ke Sistem
                    </h2>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        Silakan masukkan kredensial Anda untuk melanjutkan
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                    <form class="space-y-6" method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                        @csrf

                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm transition duration-200"
                                    placeholder="nama@perusahaan.com" value="{{ old('email') }}">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                    autocomplete="current-password" required
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm transition duration-200"
                                    placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Ingat saya
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <div>
                            <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-primary-600 to-blue-600 hover:from-primary-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-primary-300 group-hover:text-primary-200"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                Masuk ke Sistem
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span
                                    class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">atau</span>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Butuh bantuan? <a href="#"
                                    class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">Kontak
                                    Support</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center lg:text-left text-xs text-gray-500 dark:text-gray-400">
                    <p>PT Sinar Surya Semestaraya - Memimpin dengan integritas dan inovasi</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }

        /* Pastikan logo proporsional */
        img[alt="Logo"] {
            object-fit: contain;
            width: 120px;
            /* Biarkan width auto agar mengikuti height */
            height: auto;
            /* Biarkan height auto agar mengikuti class */
        }
    </style>
</x-guest-layout>
