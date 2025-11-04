<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Autentikasi Diperlukan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .error-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 3rem;
            max-width: 600px;
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-number {
            font-size: 8rem;
            font-weight: bold;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <!-- Icon SVG -->
            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="text-blue-400">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>

            <!-- Error Number -->
            <div class="error-number">401</div>

            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Autentikasi Diperlukan
            </h1>

            <!-- Error Description -->
            <p class="text-gray-600 mb-4 text-lg">
                Anda harus login terlebih dahulu untuk mengakses halaman ini.
            </p>

            <p class="text-gray-500 mb-8">
                Silakan login dengan akun yang valid untuk melanjutkan.
            </p>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-left">
                        <p class="text-sm text-blue-700 font-semibold">Login Diperlukan</p>
                        <p class="text-sm text-blue-600 mt-1">
                            Halaman yang Anda akses memerlukan autentikasi. Silakan login untuk melanjutkan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login Sekarang
                </a>

                <button onclick="window.history.back()"
                    class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </button>
            </div>

            <!-- Additional Help -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Belum punya akun? Hubungi
                    <a href="mailto:admin@sinarsurya.com" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Administrator
                    </a> untuk mendaftar.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
