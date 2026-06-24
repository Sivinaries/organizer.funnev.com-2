<!DOCTYPE html>
<html lang="en">

<head>
    <title>Detail Approval</title>
    @include('layout.head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="flex items-center gap-3 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <a href="{{ route('approvals') }}"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-orange-500"></i> Detail Approval
                    </h1>
                    <p class="text-sm text-gray-500">Tinjau detail pengajuan event sebelum menyetujui</p>
                </div>
            </div>

            <!-- Penanggung Jawab -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider border-b pb-2">Penanggung Jawab</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nama</p>
                        <p class="font-semibold text-gray-800">{{ $approval->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nomor Whatsapp</p>
                        <p class="font-semibold text-gray-800">{{ $approval->no_telpon }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-2">Foto KTP</p>
                    @if ($approval->ktp)
                        <img src="{{ asset('storage/' . $approval->ktp) }}" alt="Foto KTP"
                            class="max-w-md w-full rounded-lg border border-gray-200">
                    @else
                        <p class="text-gray-500">Tidak ada foto KTP</p>
                    @endif
                </div>
            </div>

            <!-- Event -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider border-b pb-2">Event</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Category</p>
                        <p class="font-semibold text-gray-800">{{ $approval->kategori->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nama Event</p>
                        <p class="font-semibold text-gray-800">{{ $approval->event }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Event Organizer</p>
                        <p class="font-semibold text-gray-800">{{ $approval->organizer }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Waktu</p>
                        <p class="font-semibold text-gray-800">{{ $approval->start_time }} &rarr; {{ $approval->end_time }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Location</p>
                    <p class="font-semibold text-gray-800 mb-2">{{ $approval->location }}</p>
                    <div id="map" class="rounded-lg" style="height: 280px;"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-2">Poster 1</p>
                        @if ($approval->img)
                            <img src="{{ asset('storage/' . $approval->img) }}" alt="Poster 1"
                                class="w-full rounded-lg border border-gray-200">
                        @else
                            <p class="text-gray-500">Tidak ada gambar</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-2">Poster 2</p>
                        @if ($approval->img2)
                            <img src="{{ asset('storage/' . $approval->img2) }}" alt="Poster 2"
                                class="w-full rounded-lg border border-gray-200">
                        @else
                            <p class="text-gray-500">Tidak ada gambar</p>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Description</p>
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg text-gray-700 prose max-w-none">
                        {!! $approval->description !!}
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Syarat Dan Ketentuan</p>
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg text-gray-700 prose max-w-none">
                        {!! $approval->syarat !!}
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col md:flex-row gap-4">
                <form method="post" action="{{ route('postapproval', ['id' => $approval->id]) }}" class="w-full">
                    @csrf @method('post')
                    <x-button type="button" variant="success" icon="check"
                        class="approve-confirm w-full justify-center">Approve</x-button>
                </form>
                <form method="post" action="{{ route('delapproval', ['id' => $approval->id]) }}" class="w-full">
                    @csrf @method('delete')
                    <x-button type="button" variant="danger" icon="xmark"
                        class="decline-confirm w-full justify-center">Decline</x-button>
                </form>
            </div>
        </div>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([-6.200, 106.845], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        var locationString = @json($approval->location);
        if (locationString) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationString)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var lat = data[0].lat, lon = data[0].lon;
                        L.marker([lat, lon]).addTo(map);
                        map.setView([lat, lon], 13);
                    }
                })
                .catch(error => console.error("Error fetching geocoding data:", error));
        }

        // Confirm dialogs
        function bindConfirm(selector, opts) {
            document.querySelectorAll(selector).forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const form = btn.closest('form');
                    Swal.fire(opts).then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });
        }
        bindConfirm('.approve-confirm', {
            title: 'Setujui event ini?', text: 'Event akan dipublikasikan.', icon: 'question',
            showCancelButton: true, confirmButtonColor: '#16a34a', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, setujui!', cancelButtonText: 'Batal',
        });
        bindConfirm('.decline-confirm', {
            title: 'Tolak pengajuan?', text: 'Pengajuan event akan dihapus.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, tolak!', cancelButtonText: 'Batal',
        });
    </script>

    @include('sweetalert::alert')
</body>

</html>
