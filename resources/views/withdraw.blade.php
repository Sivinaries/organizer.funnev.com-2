<!DOCTYPE html>
<html lang="en">

<head>
    <title>Withdraws</title>
    @include('layout.head')
    <link href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        .dataTables_wrapper .dataTables_length select {
            padding-right: 2rem;
            border-radius: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header Section -->
            <div
                class="md:flex justify-between items-center bg-white p-5 rounded-xl shadow-sm border border-gray-100 space-y-3 md:space-y-0">
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-wallet text-purple-500 text-4xl"></i> Withdraws
                    </h1>
                    @if (auth()->user()->level === 'Organizer')
                        <p class="text-sm text-gray-500 mt-1">
                            Your current balance is:
                            <span class="font-bold text-emerald-600">Rp.
                                {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                        </p>
                    @else
                        <p class="text-sm text-gray-500">Kelola permintaan penarikan dana</p>
                    @endif
                </div>
                @if (auth()->user()->level === 'Organizer')
                    <x-button id="addBtn" size="lg" icon="plus" variant="purple">Add</x-button>
                @endif
            </div>

            <!-- Table Section -->
            <div class="w-full bg-white rounded-xl shadow-md border border-gray-100">
                <div class="p-5 overflow-auto">
                    <table id="myTable" class="w-full text-left">
                        <thead class="bg-gray-100 text-gray-600 text-sm leading-normal">
                            <tr>
                                <th class="p-4 font-bold" width="5%">No</th>
                                <th class="p-4 font-bold">Tanggal</th>
                                <th class="p-4 font-bold">Nama</th>
                                <th class="p-4 font-bold">No Rek</th>
                                <th class="p-4 font-bold">Payment</th>
                                <th class="p-4 font-bold">Amount</th>
                                <th class="p-4 font-bold">
                                    <div class="flex items-center justify-center">Status</div>
                                </th>
                                <th class="p-4 font-bold" width="12%">
                                    <div class="flex items-center justify-center">Aksi</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @php $no = 1; @endphp
                            @foreach ($withdraws as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="p-4 font-medium">{{ $no++ }}</td>
                                    <td class="p-4 text-xs">{{ $item->created_at }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $item->user->name }}</td>
                                    <td class="p-4">{{ $item->no_rek }}</td>
                                    <td class="p-4">
                                        <span
                                            class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-bold border border-indigo-100">
                                            {{ $item->payment_type }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-bold">Rp. {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        @php
                                            $st = strtolower($item->status);
                                            $badge = $st === 'approved'
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                                : ($st === 'pending'
                                                    ? 'bg-amber-50 text-amber-700 border-amber-200'
                                                    : 'bg-rose-50 text-rose-700 border-rose-200');
                                        @endphp
                                        <div class="flex justify-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('showwithdraw', ['id' => $item->id, 'no_rek' => $item->no_rek]) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 hover:scale-105 transition"
                                                title="Detail">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    @if (auth()->user()->level === 'Organizer')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    @endif
    <script src="{{ asset('modal/withdraw.js') }}"></script>

    <!-- Modal (Organizer only) -->
    @if (auth()->user()->level === 'Organizer')
        @include('modal.withdrawAdd')
    @endif

    @include('sweetalert::alert')
</body>

</html>