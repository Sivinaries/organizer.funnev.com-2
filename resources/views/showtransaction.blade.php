<!DOCTYPE html>
<html lang="en">

<head>
    <title>Detail Transaction</title>
    @include('layout.head')
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="flex items-center gap-3 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <a href="{{ route('transactions') }}"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-receipt text-orange-500"></i> Detail Transaction
                    </h1>
                    <p class="text-sm text-gray-500 font-mono">#{{ $transaction->no_order }}</p>
                </div>
            </div>

            <!-- Receipt Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 max-w-2xl">
                <!-- Event -->
                <img src="{{ asset('storage/' . $transaction->event->img) }}" alt=""
                    class="rounded-lg object-cover w-full h-40 mb-4">
                <h2 class="font-bold text-xl text-gray-800">{{ $transaction->event->event }}</h2>
                <div class="text-sm text-gray-500 mb-6">
                    {{ $transaction->user->name }} &middot; {{ $transaction->user->email }}
                </div>

                <!-- Items -->
                <div class="space-y-3 border-t border-dashed border-gray-200 pt-4">
                    @foreach ($transaction->tickets as $item)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">{{ $item->pivot->qty }} x {{ $item->type }}</span>
                            <span class="font-bold text-gray-800">Rp. {{ number_format($item->pivot->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Service</span>
                        <span>Rp. {{ number_format($transaction->total_service, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="flex justify-between items-center border-t border-gray-200 mt-4 pt-4">
                    <span class="font-bold text-lg text-gray-800">Total</span>
                    <span class="font-extrabold text-xl text-orange-600">Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>

                <!-- Status -->
                @php
                    $st = strtolower($transaction->status);
                    $badge = 'bg-gray-100 text-gray-600 border-gray-200';
                    if ($st === 'settlement') {
                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    } elseif ($st === 'pending') {
                        $badge = 'bg-amber-50 text-amber-700 border-amber-200';
                    } elseif (in_array($st, ['expire', 'progress'])) {
                        $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                    }
                @endphp
                <div class="mt-6 flex items-center gap-2">
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">{{ ucfirst($transaction->status) }}</span>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
