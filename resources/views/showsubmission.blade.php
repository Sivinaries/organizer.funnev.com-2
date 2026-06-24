<!DOCTYPE html>
<html lang="en">

<head>
    <title>Detail Submission</title>
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
                <a href="{{ route('submissions') }}"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                        <i class="fas fa-file-lines text-orange-500"></i> Detail Submission
                    </h1>
                    <p class="text-sm text-gray-500">Detail pengajuan event Anda</p>
                </div>
            </div>

            <!-- Penanggung Jawab -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider border-b pb-2">Penanggung Jawab</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nama</p>
                        <p class="font-semibold text-gray-800">{{ $submission->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nomor Whatsapp</p>
                        <p class="font-semibold text-gray-800">{{ $submission->no_telpon }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-2">Foto KTP</p>
                    @if ($submission->ktp)
                        <img src="{{ asset('storage/' . $submission->ktp) }}" alt="Foto KTP"
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
                        <p class="font-semibold text-gray-800">{{ $submission->kategori->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nama Event</p>
                        <p class="font-semibold text-gray-800">{{ $submission->event }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Event Organizer</p>
                        <p class="font-semibold text-gray-800">{{ $submission->organizer }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Waktu</p>
                        <p class="font-semibold text-gray-800">{{ $submission->start_time }} &rarr; {{ $submission->end_time }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Location</p>
                    <p class="font-semibold text-gray-800 mb-2">{{ $submission->location }}</p>
                    <div id="map" class="rounded-lg" style="height: 320px;"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-2">Poster 1</p>
                        @if ($submission->img)
                            <img src="{{ asset('storage/' . $submission->img) }}" alt="Poster 1"
                                class="w-full rounded-lg border border-gray-200">
                        @else
                            <p class="text-gray-500">Tidak ada gambar</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-2">Poster 2</p>
                        @if ($submission->img2)
                            <img src="{{ asset('storage/' . $submission->img2) }}" alt="Poster 2"
                                class="w-full rounded-lg border border-gray-200">
                        @else
                            <p class="text-gray-500">Tidak ada gambar</p>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Description</p>
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg text-gray-700 prose max-w-none">
                        {!! $submission->description !!}
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Syarat Dan Ketentuan</p>
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg text-gray-700 prose max-w-none">
                        {!! $submission->syarat !!}
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <form method="post" action="{{ route('delsubmission', ['id' => $submission->id]) }}">
                    @csrf @method('delete')
                    <x-button type="button" variant="danger" icon="xmark"
                        class="cancel-confirm w-full justify-center">Cancel Pengajuan</x-button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([-6.200, 106.845], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        var locationString = @json($submission->location);
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

        document.querySelectorAll('.cancel-confirm').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const form = btn.closest('form');
                Swal.fire({
                    title: 'Batalkan pengajuan?',
                    text: 'Pengajuan event ini akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Tutup',
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });
    </script>

    @include('sweetalert::alert')
</body>

</html>
