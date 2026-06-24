<!DOCTYPE html>
<html lang="en">

<head>
    <title>Detail Withdraw</title>
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
                <a href="{{ route('withdraws') }}"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-wallet text-orange-500"></i> Detail Withdraw
                    </h1>
                    <p class="text-sm text-gray-500">Informasi permintaan penarikan dana</p>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 max-w-3xl">
                @php
                    $st = strtolower($withdraw->status);
                    $badge = $st === 'approved'
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : ($st === 'pending'
                            ? 'bg-amber-50 text-amber-700 border-amber-200'
                            : 'bg-rose-50 text-rose-700 border-rose-200');
                @endphp

                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Jumlah Penarikan</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">Rp. {{ number_format($withdraw->amount, 0, ',', '.') }}</p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $badge }}">
                        {{ ucfirst($withdraw->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Atas Nama</p>
                        <p class="font-semibold text-gray-800">{{ $withdraw->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">No Rekening</p>
                        <p class="font-semibold text-gray-800">{{ $withdraw->no_rek }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Payment Type</p>
                        <p class="font-semibold text-gray-800">{{ $withdraw->payment_type }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-800">{{ $withdraw->created_at }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Note</p>
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg text-gray-700 prose max-w-none">
                        {!! $withdraw->note !!}
                    </div>
                </div>

                @if (strtolower($withdraw->status) === 'pending')
                    <form action="{{ route('approvewithdraw', ['id' => $withdraw->id, 'no_rek' => $withdraw->no_rek]) }}"
                        method="post">
                        @csrf
                        @method('patch')
                        <x-button type="button" variant="success" icon="check"
                            class="approve-confirm w-full justify-center">Approve</x-button>
                    </form>
                @endif
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.approve-confirm').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const form = btn.closest('form');
                    Swal.fire({
                        title: 'Setujui withdraw?',
                        text: 'Saldo user akan dikurangi sesuai jumlah penarikan.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, setujui!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>

    @include('sweetalert::alert')
</body>

</html>
