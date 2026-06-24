<!DOCTYPE html>
<html lang="en">

<head>
    <title>Attendance</title>
    @include('layout.head')
    <link href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css" rel="stylesheet" />

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
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <i class="fas fa-clipboard-user text-orange-500"></i> Attendance
                </h1>
                <p class="text-sm text-gray-500">Riwayat scan tiket pengunjung</p>
            </div>

            <!-- Table Section -->
            <div class="w-full bg-white rounded-xl shadow-md border border-gray-100">
                <div class="p-5 overflow-auto">
                    <table id="myTable" class="w-full text-left">
                        <thead class="bg-gray-100 text-gray-600 text-sm leading-normal">
                            <tr>
                                <th class="p-4 font-bold" width="5%">No</th>
                                <th class="p-4 font-bold">Tanggal</th>
                                <th class="p-4 font-bold">User</th>
                                <th class="p-4 font-bold">Event</th>
                                <th class="p-4 font-bold">No Ticket</th>
                                <th class="p-4 font-bold">Ticket</th>
                                <th class="p-4 font-bold">Used</th>
                                <th class="p-4 font-bold">Scanned</th>
                                <th class="p-4 font-bold" width="10%">
                                    <div class="flex items-center justify-center">Aksi</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @php $no = 1; @endphp
                            @foreach ($attendances as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="p-4 font-medium">{{ $no++ }}</td>
                                    <td class="p-4 text-xs">{{ $item->created_at }}</td>
                                    <td class="p-4 text-xs">{{ $item->transaction->user->email }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $item->event->event }}</td>
                                    <td class="p-4 font-mono text-xs">{{ $item->tickQr->no_ticket }}</td>
                                    <td class="p-4">
                                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-bold border border-indigo-100">
                                            {{ $item->tickQr->ticket->type }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $used = strtolower($item->tickQr->status);
                                            $usedBadge = str_contains($used, 'used') || str_contains($used, 'true') || $used === '1'
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                                : 'bg-gray-100 text-gray-600 border-gray-200';
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $usedBadge }}">
                                            {{ ucfirst($item->tickQr->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-xs">{{ $item->scanned_at }}</td>
                                    <td class="p-4">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('getticket', ['no_ticket' => $item->tickQr->no_ticket]) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-emerald-500 text-white rounded-lg shadow hover:bg-emerald-600 hover:scale-105 transition"
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
    <script>
        $(document).ready(function() {
            new DataTable('#myTable', {
                columnDefs: [{
                    targets: 1,
                    render: function(data, type) {
                        if (type !== 'display' || !data) return data;
                        const date = new Date(data);
                        return isNaN(date) ? data : date.toLocaleDateString('id-ID');
                    },
                }, {
                    targets: 7,
                    render: function(data, type) {
                        if (type !== 'display' || !data) return data;
                        const date = new Date(data);
                        return isNaN(date) ? data : date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID');
                    },
                }],
            });
        });
    </script>
    @include('sweetalert::alert')
</body>

</html>
