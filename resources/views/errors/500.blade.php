<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan Server</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
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
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
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
                    class="text-red-400">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Error Number -->
            <div class="error-number">500</div>

            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Terjadi Kesalahan Server
            </h1>

            <!-- Error Description -->
            <p class="text-gray-600 mb-4 text-lg">
                Maaf, terjadi kesalahan pada server kami.
            </p>

            <p class="text-gray-500 mb-8">
                Tim kami telah diberitahu dan sedang menangani masalah ini. Silakan coba lagi dalam beberapa saat.
            </p>

            <!-- Error Details (only in development) -->
            @if (config('app.debug') && isset($exception))
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-8 text-left">
                    <p class="text-xs font-mono text-gray-700 break-all">
                        <strong>Error:</strong> {{ $exception->getMessage() }}
                    </p>
                    @if (method_exists($exception, 'getFile'))
                        <p class="text-xs font-mono text-gray-600 mt-2">
                            <strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}
                        </p>
                    @endif
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center flex-wrap">
                <button onclick="location.reload()"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Lagi
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

            <!-- Additional Help -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Masalah berlanjut? Hubungi
                    <a href="mailto:support@sinarsurya.com" class="text-red-600 hover:text-red-800 font-semibold">
                        Tim Support
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
