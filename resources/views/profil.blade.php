<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profil</title>
    @include('layout.head')
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user text-orange-500"></i> Profil
                </h1>
                <p class="text-sm text-gray-500">Informasi akun Anda</p>
            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 max-w-2xl">
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-3xl font-bold uppercase shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                        <span class="inline-block mt-1 bg-orange-50 text-orange-700 text-xs px-2.5 py-1 rounded-full font-bold border border-orange-100">
                            {{ auth()->user()->level }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nama</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('sweetalert::alert')
</body>

</html>
