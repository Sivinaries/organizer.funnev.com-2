<!DOCTYPE html>
<html lang="en">

<head>
    <title>Get Ticket</title>
    @include('layout.head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="flex items-center gap-3 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <a href="{{ route('scanner') }}"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-ticket text-orange-500"></i> Detail Tiket
                    </h1>
                    <p class="text-sm text-gray-500">Verifikasi & check-in pengunjung</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded-xl">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ticket Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 max-w-2xl">
                <form id="storeForm" method="post" action="{{ route('postattendance') }}">
                    @csrf @method('post')
                    <input type="text" name="tick_qr_id" value="{{ $tickQr->id }}" hidden />

                    <div class="flex items-center justify-between border-b border-dashed border-gray-200 pb-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">No Order</p>
                            <p class="font-mono font-bold text-gray-800">#{{ $tickQr->transaction->no_order }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-bold">No Ticket</p>
                            <p class="font-mono font-bold text-gray-800">#{{ $tickQr->no_ticket }}</p>
                        </div>
                    </div>

                    <h2 class="font-bold text-xl text-gray-800">{{ $tickQr->transaction->event->event }}</h2>
                    <p class="text-sm text-gray-500 mb-6">
                        {{ $tickQr->transaction->user->name }} &middot; {{ $tickQr->transaction->user->email }}
                    </p>

                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                        <span class="bg-indigo-50 text-indigo-700 text-sm px-3 py-1 rounded-full font-bold border border-indigo-100">
                            {{ $tickQr->ticket->type }}
                        </span>
                        <span class="font-extrabold text-lg text-orange-600">Rp. {{ number_format($tickQr->ticket->price, 0, ',', '.') }}</span>
                    </div>

                    @php
                        $used = strtolower($tickQr->status);
                        $usedBadge = $used === 'pending'
                            ? 'bg-amber-50 text-amber-700 border-amber-200'
                            : 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    @endphp
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-sm text-gray-500">Status:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $usedBadge }}">{{ ucfirst($tickQr->status) }}</span>
                    </div>

                    @if ($tickQr->status == 'pending')
                        <x-button type="submit" variant="primary" icon="circle-check"
                            class="w-full justify-center">Check In</x-button>
                    @endif
                </form>
            </div>
        </div>
    </main>
    @include('layout.errors')
    @include('sweetalert::alert')
</body>

</html>
