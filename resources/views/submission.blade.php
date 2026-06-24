<!DOCTYPE html>
<html lang="en">

<head>
    <title>Submissions</title>
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
            <div
                class="md:flex justify-between items-center bg-white p-5 rounded-xl shadow-sm border border-gray-100 space-y-2 md:space-y-0">
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-green-500 text-4xl"></i> Submissions
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Manage and track all your form submissions in one place</p>
                </div>
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
                                <th class="p-4 font-bold">Event</th>
                                <th class="p-4 font-bold">Lokasi</th>
                                <th class="p-4 font-bold">
                                    <div class="flex items-center justify-center">Status</div>
                                </th>
                                <th class="p-4 font-bold" width="10%">
                                    <div class="flex items-center justify-center">Aksi</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @php $no = 1; @endphp
                            @foreach ($submissions as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="p-4 font-medium">{{ $no++ }}</td>
                                    <td class="p-4 text-xs">{{ $item->created_at }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $item->name }}</td>
                                    <td class="p-4">{{ $item->event }}</td>
                                    <td class="p-4 text-xs max-w-xs truncate" title="{{ $item->location }}">
                                        <i class="fas fa-location-dot text-gray-400"></i> {{ $item->location }}
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $st = strtolower($item->status);
                                            $badge = 'bg-gray-100 text-gray-600 border-gray-200';
                                            if (str_contains($st, 'approve') || str_contains($st, 'active')) {
                                                $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                            } elseif (str_contains($st, 'check') || str_contains($st, 'pending')) {
                                                $badge = 'bg-amber-50 text-amber-700 border-amber-200';
                                            } elseif (str_contains($st, 'reject') || str_contains($st, 'cancel')) {
                                                $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                                            }
                                        @endphp
                                        <div class="flex justify-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('showsubmission', ['id' => $item->id]) }}"
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
    <script>
        $(document).ready(function () {
            new DataTable('#myTable', {
                columnDefs: [{
                    targets: 1,
                    render: function (data, type) {
                        if (type !== 'display' || !data) return data;
                        const date = new Date(data);
                        return isNaN(date) ? data : date.toLocaleDateString('id-ID');
                    },
                }],
            });
        });
    </script>
    @include('sweetalert::alert')
</body>

</html>