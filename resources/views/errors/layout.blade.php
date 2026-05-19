<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12 text-center">
            <div class="mb-6">
                @yield('icon')
            </div>

            <h1 class="text-7xl font-black text-gray-900 dark:text-white mb-2">
                @yield('code')
            </h1>

            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">
                @yield('title')
            </h2>

            <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                @yield('message')
            </p>

            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <p class="text-center text-sm text-gray-400 dark:text-gray-500 mt-6">
            &copy; {{ date('Y') }} Presensi. All rights reserved.
        </p>
    </div>
</body>
</html>
