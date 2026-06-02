<x-guest-layout>
    <div
        class="flex min-h-screen bg-gradient-to-br from-primary-50 via-blue-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Left side - Logo and company image -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-500 to-blue-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="p-12 flex flex-col justify-between relative z-10 h-full">
                <div>
                    <div class="flex items-center">
                        <!-- Card putih di belakang logo -->
                        <div class="bg-white rounded-xl shadow-lg p-2 flex items-center justify-center h-16 w-16">
                            <img src="{{ asset('img/SemestaPro.PNG') }}" alt="Logo"
                                class="h-12 w-12 object-contain flex-shrink-0">
                        </div>
                        <div class="ml-4">
                            <!-- Nama perusahaan -->
                            <h1 class="text-white text-2xl font-bold leading-tight">Sinar Surya Semestaraya</h1>
                            <!-- ERP System text di bawah nama perusahaan -->
                            <p class="text-white/80 text-sm">Enterprise Resource Planning System</p>
                        </div>
                    </div>
                </div>

                <div class="text-white space-y-6">
                    <!-- Visi/misi -->
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

        <!-- Right side - Forgot password form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-8">
            <div class="w-full max-w-md animate-fade-in">
                <!-- Logo di tengah atas untuk mobile, ukuran disesuaikan -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-2 flex items-center justify-center h-16 w-16">
                        <img src="{{ asset('img/SemestaPro.PNG') }}" alt="Logo"
                            class="h-12 w-12 object-contain flex-shrink-0">
                    </div>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Lupa Password?
                    </h2>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        Jangan khawatir. Cukup masukkan email terdaftar Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                    </p>
                </div>

                <!-- Session Status (Customized alert style) -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-300 text-sm flex items-start gap-3 shadow-sm animate-fade-in">
                        <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <span class="font-semibold block mb-0.5">Tautan Terkirim!</span>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                    <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
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
                                <input id="email" name="email" type="email" autocomplete="email" required autofocus
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm transition duration-200"
                                    placeholder="nama@perusahaan.com" value="{{ old('email') }}">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-primary-600 to-blue-600 hover:from-primary-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-primary-300 group-hover:text-primary-200"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.24a.75.75 0 00-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                Kirim Tautan Reset Password
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200 gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </div>

                <div class="mt-6 text-center lg:text-left text-xs text-gray-500 dark:text-gray-400">
                    <p>PT Sinar Surya Semestaraya - Memimpin dengan integritas dan inovasi</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

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

        /* Pastikan logo proporsional */
        img[alt="Logo"] {
            object-fit: contain;
            width: 120px;
            height: auto;
        }
    </style>
</x-guest-layout>
