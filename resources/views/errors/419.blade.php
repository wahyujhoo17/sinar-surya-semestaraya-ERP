<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Telah Berakhir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
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
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
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
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
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
                    class="text-yellow-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Error Number -->
            <div class="error-number">419</div>

            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Sesi Telah Berakhir
            </h1>

            <!-- Error Description -->
            <p class="text-gray-600 mb-4 text-lg">
                Maaf, sesi Anda telah berakhir karena tidak ada aktivitas dalam waktu yang lama.
            </p>

            <p class="text-gray-500 mb-8">
                Untuk keamanan akun Anda, silakan muat ulang halaman dan login kembali.
            </p>

            <!-- Info Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-left">
                        <p class="text-sm text-yellow-700 font-semibold">Token CSRF Kedaluwarsa</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Halaman ini telah dibuka terlalu lama. Muat ulang halaman untuk melanjutkan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center flex-wrap">
                <button onclick="location.reload()"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Muat Ulang Halaman
                </button>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Security Tips -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">
                    <strong>Tips Keamanan:</strong>
                </p>
                <p class="text-xs text-gray-400">
                    Sesi akan berakhir otomatis setelah periode tidak aktif untuk melindungi data Anda.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
