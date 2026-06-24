<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tickets</title>
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
            <div class="md:flex justify-between items-center bg-white p-5 rounded-xl shadow-sm border border-gray-100 space-y-3 md:space-y-0">
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-ticket text-orange-500"></i> Tickets
                    </h1>
                    <p class="text-sm text-gray-500">Kelola tiket event</p>
                </div>
                @if (auth()->user()->level === 'Organizer')
                    <x-button id="addBtn" size="lg" icon="plus">Tambah</x-button>
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
                                <th class="p-4 font-bold">Event</th>
                                <th class="p-4 font-bold">Type</th>
                                <th class="p-4 font-bold">Price</th>
                                <th class="p-4 font-bold">Pcs</th>
                                <th class="p-4 font-bold" width="12%">
                                    <div class="flex items-center justify-center">Aksi</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @php $no = 1; @endphp
                            @foreach ($tickets as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="p-4 font-medium">{{ $no++ }}</td>
                                    <td class="p-4 text-xs">{{ $item->created_at }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $item->event->event }}</td>
                                    <td class="p-4">
                                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-bold border border-indigo-100">
                                            {{ $item->type }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center bg-orange-50 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full border border-orange-100">
                                            Rp. {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-medium">{{ $item->pcs }}</td>
                                    <td class="p-4">
                                        <div class="flex justify-center items-center gap-2">
                                            @if (auth()->user()->level === 'Organizer')
                                                <button
                                                    class="editBtn w-10 h-10 flex items-center justify-center bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 hover:scale-105 transition cursor-pointer"
                                                    data-id="{{ $item->id }}" title="Edit">
                                                    <i class="fas fa-edit text-lg"></i>
                                                </button>
                                            @endif
                                            @if (auth()->user()->level === 'Admin')
                                                <form method="post" action="{{ route('delticket', ['id' => $item->id]) }}"
                                                    class="inline">
                                                    @csrf @method('delete')
                                                    <button type="button"
                                                        class="delete-confirm w-10 h-10 flex items-center justify-center bg-red-500 text-white rounded-lg shadow hover:bg-red-600 hover:scale-105 transition cursor-pointer"
                                                        title="Delete">
                                                        <i class="fas fa-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
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
        <script>
            window.ticketsData = {
                @foreach ($tickets as $item)
                    "{{ $item->id }}": {
                        type: @json($item->type),
                        price: @json($item->price),
                        pcs: @json($item->pcs),
                        event_id: @json($item->event_id),
                        desc: @json($item->desc),
                    },
                @endforeach
            };
        </script>
    @endif
    <script src="{{ asset('modal/ticket.js') }}"></script>

    <!-- Modals (Organizer only) -->
    @if (auth()->user()->level === 'Organizer')
        @include('modal.ticketAdd')
        @include('modal.ticketEdit')
    @endif

    @include('layout.errors')
    @include('sweetalert::alert')
</body>

</html>
