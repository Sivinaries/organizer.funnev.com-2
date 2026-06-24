<!DOCTYPE html>
<html lang="en">

<head>
    <title>Activities</title>
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
                    <i class="fas fa-history text-orange-500"></i> Log Aktivitas
                </h1>
                <p class="text-sm text-gray-500 mt-1">Pantau aktivitas pengguna & sistem</p>
            </div>

            <!-- Table Section -->
            <div class="w-full bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-5 overflow-auto">
                    <table id="myTable" class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal font-bold">
                            <tr>
                                <th class="p-4 w-1/4">Waktu / Akun</th>
                                <th class="p-4 w-1/5">Tipe Aksi</th>
                                <th class="p-4">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm divide-y divide-gray-200">
                            @forelse ($acts as $item)
                                @php
                                    $name = $item->user->name ?? 'System';
                                    $actLower = strtolower($item->action);
                                    $badgeClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                    $icon = 'fa-info-circle';
                                    if (str_contains($actLower, 'create') || str_contains($actLower, 'add')) {
                                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $icon = 'fa-plus-circle';
                                    } elseif (str_contains($actLower, 'update') || str_contains($actLower, 'edit')) {
                                        $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $icon = 'fa-edit';
                                    } elseif (str_contains($actLower, 'delete') || str_contains($actLower, 'remove') || str_contains($actLower, 'destroy')) {
                                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        $icon = 'fa-trash-alt';
                                    } elseif (str_contains($actLower, 'approve')) {
                                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $icon = 'fa-check-circle';
                                    } elseif (str_contains($actLower, 'login') || str_contains($actLower, 'logout')) {
                                        $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                        $icon = 'fa-key';
                                    } elseif (str_contains($actLower, 'withdraw')) {
                                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $icon = 'fa-money-bill';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition duration-150 align-top">
                                    <!-- Waktu & Akun -->
                                    <td class="p-4">
                                        <div class="flex flex-col gap-1 mb-3">
                                            <span class="font-bold text-gray-800 text-sm">{{ $item->created_at }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-xs font-bold shrink-0 uppercase shadow-sm">
                                                {{ strtoupper(substr($name, 0, 1)) }}
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="font-bold text-xs text-gray-700 truncate" title="{{ $name }}">{{ $name }}</p>
                                                <p class="text-[10px] text-gray-400 truncate">ID: #{{ $item->user_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Action Badge -->
                                    <td class="p-4 align-middle">
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase flex items-center gap-2 w-fit border shadow-sm {{ $badgeClass }}">
                                            <i class="fas {{ $icon }}"></i>
                                            {{ str_replace('_', ' ', $item->action) }}
                                        </span>
                                    </td>
                                    <!-- Description -->
                                    <td class="p-4 align-middle">
                                        <div class="bg-white border border-gray-100 p-4 rounded-lg shadow-sm">
                                            <p class="text-gray-700 font-medium text-sm leading-relaxed">{{ $item->description }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-50">
                                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-history text-4xl text-gray-300"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900">Tidak Ada Log Aktivitas</h3>
                                            <p class="text-sm text-gray-500 mt-1">Aktivitas sistem akan direkam di sini secara otomatis.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
            new DataTable('#myTable', { order: [] });
        });
    </script>
    @include('sweetalert::alert')
</body>

</html>
