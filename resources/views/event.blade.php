<!DOCTYPE html>
<html lang="en">

<head>
    <title>Events</title>
    @include('layout.head')
    <link href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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
                        <i class="fas fa-calendar-days text-purple-600 text-4xl"></i> Events Management
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Organize and manage your events</p>
                </div>
                @if (auth()->user()->level === 'Organizer')

                    <x-button id="addBtn" size="lg" variant="purple" icon="plus">Add</x-button>
                @endif
            </div>

        
            <!-- Table Section -->
            <div class="w-full bg-white rounded-xl shadow-md border border-gray-100">
                <div class="p-5 overflow-auto">
                    <table id="myTable" class="w-full text-left">
                        <thead class="bg-gray-100 text-gray-600 text-sm leading-normal">
                            <tr>
                                <th class="p-4 font-bold" width="5%">No</th>
                                <th class="p-4 font-bold">Organizer</th>
                                <th class="p-4 font-bold">Event</th>
                                <th class="p-4 font-bold">Location</th>
                                <th class="p-4 font-bold">Start</th>
                                <th class="p-4 font-bold">End</th>
                                <th class="p-4 font-bold">
                                    <div class="flex items-center justify-center">Status</div>
                                </th>
                                <th class="p-4 font-bold" width="15%">
                                    <div class="flex items-center justify-center">Action</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @php $no = 1; @endphp
                            @foreach ($events as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="p-4 font-medium">{{ $no++ }}</td>
                                    <td class="p-4 font-bold text-gray-900">{{ $item->name }}</td>
                                    <td class="p-4">{{ $item->event }}</td>
                                    <td class="p-4 text-xs max-w-xs truncate" title="{{ $item->location }}">
                                        <i class="fas fa-location-dot text-gray-400"></i> {{ $item->location }}
                                    </td>
                                    <td class="p-4 text-xs">{{ $item->start_time }}</td>
                                    <td class="p-4 text-xs">{{ $item->end_time }}</td>
                                    <td class="p-4">
                                        @php
                                            $st = strtolower($item->status);
                                            $badge = 'bg-gray-100 text-gray-600 border-gray-200';
                                            if (str_contains($st, 'active') || str_contains($st, 'approve') || str_contains($st, 'success')) {
                                                $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                            } elseif (str_contains($st, 'check') || str_contains($st, 'pending') || str_contains($st, 'wait')) {
                                                $badge = 'bg-amber-50 text-amber-700 border-amber-200';
                                            } elseif (str_contains($st, 'reject') || str_contains($st, 'cancel') || str_contains($st, 'out')) {
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
                                            {{-- Detail --}}
                                            <a href="{{ route('showevent', ['id' => $item->id, 'event' => $item->event]) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-emerald-500 text-white rounded-lg shadow hover:bg-emerald-600 hover:scale-105 transition"
                                                title="Detail">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>

                                            @if (auth()->user()->level === 'Admin')
                                                {{-- Delete --}}
                                                <form method="post" action="{{ route('delevent', ['id' => $item->id]) }}"
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('modal/event.js') }}"></script>

    <!-- Modal (Organizer only) -->
    @if (auth()->user()->level === 'Organizer')
        @include('modal.eventAdd')
    @endif

    @include('layout.errors')
    @include('sweetalert::alert')
</body>

</html>