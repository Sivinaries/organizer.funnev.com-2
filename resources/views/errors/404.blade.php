<!DOCTYPE html>
<html lang="en">

<head>
    <title>404 - Page Not Found</title>
    @include('layout.head')
</head>

<body class="font-sans bg-gradient-to-br from-orange-600 to-orange-300 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-10 text-center max-w-md w-full space-y-5">
        <div class="w-20 h-20 mx-auto bg-orange-100 rounded-full flex items-center justify-center text-orange-500">
            <i class="fas fa-triangle-exclamation text-4xl"></i>
        </div>
        <div class="space-y-1">
            <h1 class="text-5xl font-extrabold text-gray-800">404</h1>
            <h2 class="text-xl font-bold text-gray-700">Page Not Found</h2>
            <p class="text-gray-500 text-sm">Oops! Halaman yang Anda cari tidak ditemukan.</p>
        </div>
        <a href="{{ route('signin') }}"
            class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-lg shadow-md transition hover:scale-105">
            <i class="fas fa-house"></i> Return to Home
        </a>
    </div>
</body>

</html>
