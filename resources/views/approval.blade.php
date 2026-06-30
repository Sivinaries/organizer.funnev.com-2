<!DOCTYPE html>
<html lang="en">

<head>
    <title>Approvals</title>
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
                    <i class="fas fa-clipboard-check text-amber-600"></i> Approvals
                </h1>
                <p class="text-sm text-gray-500">Tinjau pengajuan event yang masuk</p>
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
                                <th class="p-4 font-bold">Organizer</th>
                                <th class="p-4 font-bold" width="12%">
                                    <div class="flex items-center justify-center">Aksi</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @php $no = 1; @endphp
                            @foreach ($approvals as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="p-4 font-medium">{{ $no++ }}</td>
                                    <td class="p-4 text-xs">{{ $item->created_at }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $item->name }}</td>
                                    <td class="p-4">{{ $item->event }}</td>
                                    <td class="p-4">{{ $item->organizer }}</td>
                                    <td class="p-4">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('showapproval', ['id' => $item->id, 'name' => $item->name]) }}"
                                                class="inline-flex items-center gap-2 px-4 h-10 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 hover:scale-105 transition font-bold text-sm"
                                                title="Check">
                                                <i class="fas fa-magnifying-glass"></i> Check
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
                }],
            });
        });
    </script>
    @include('sweetalert::alert')
</body>

</html>
