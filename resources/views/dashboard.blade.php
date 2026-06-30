<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    @include('layout.head')
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <i class="fas fa-gauge-high text-indigo-600"></i> Dashboard
                </h1>
                <p class="text-sm text-gray-500">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
            </div>

            @if (auth()->user()->level === 'Organizer')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-calendar-days text-violet-600"></i> Total Event
                        </h2>
                        <canvas id="grafikEvent" width="100" height="50"></canvas>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-ticket text-blue-600"></i> Ticket Last Event
                        </h2>
                        <canvas id="grafikTicket" width="100" height="50"></canvas>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-money-bill-trend-up text-emerald-600"></i> Revenue Last Event
                        </h2>
                        <canvas id="grafikTransaction" width="100" height="50"></canvas>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-wallet text-teal-600"></i> Total Balance
                        </h2>
                        <canvas id="grafikBalance" width="100" height="50"></canvas>
                    </div>
                </div>
            @endif

            @if (auth()->user()->level === 'Admin')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-calendar-days text-violet-600"></i> Total Event
                        </h2>
                        <canvas id="grafikEvent" width="100" height="50"></canvas>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-receipt text-green-600"></i> Total Transactions
                        </h2>
                        <canvas id="grafikTicket" width="100" height="50"></canvas>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-users text-sky-600"></i> Total User
                        </h2>
                        <canvas id="grafikTransaction" width="100" height="50"></canvas>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                        <h2 class="font-bold text-gray-700 flex items-center gap-2 mb-4">
                            <i class="fas fa-user-tie text-purple-600"></i> Total Organizer
                        </h2>
                        <canvas id="grafikBalance" width="100" height="50"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
        function getDynamicMax(data) {
            return Math.max(...data) + (Math.max(...data) * 0.1);
        }

        // CHART 1: Total Events
        const labels1 = {{ Js::from($labels1) }};
        const data1 = {{ Js::from(data: $data1) }};
        new Chart(document.getElementById('grafikEvent'), {
            type: 'bar',
            data: {
                labels: labels1,
                datasets: [{
                    label: 'Jumlah Event',
                    data: data1,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.2)',
                    fill: true,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: getDynamicMax(data1) } } }
        });

        // CHART 2: Total Ticket
        const labels2 = {{ Js::from($labels2) }};
        const data2 = {{ Js::from(data: $data2) }};
        new Chart(document.getElementById('grafikTicket'), {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [{
                    label: 'Jumlah Ticket Terjual',
                    data: data2,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.2)',
                    fill: true,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: getDynamicMax(data2) } } }
        });

        // CHART 3: Total Revenue
        const labels3 = {{ Js::from($labels3) }};
        const data3 = {{ Js::from(data: $data3) }};
        new Chart(document.getElementById('grafikTransaction'), {
            type: 'bar',
            data: {
                labels: labels3,
                datasets: [{
                    label: 'Jumlah Pemasukan',
                    data: data3,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.2)',
                    fill: true,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: getDynamicMax(data3) } } }
        });

        // CHART 4: Total Balance
        const labels4 = {{ Js::from($labels4) }};
        const data4 = {{ Js::from(data: $data4) }};
        new Chart(document.getElementById('grafikBalance'), {
            type: 'bar',
            data: {
                labels: labels4,
                datasets: [{
                    label: 'Jumlah Pemasukan',
                    data: data4,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.2)',
                    fill: true,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: getDynamicMax(data4) } } }
        });
    </script>
    @include('sweetalert::alert')
</body>

</html>
