<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Event</title>
    @include('layout.head')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('layout.sidebar')
    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-5">
            <div class="w-full bg-white rounded-md h-fit mx-auto">
                <div class="p-3 text-center">
                    <h1 class="font-extrabold text-3xl">Add Event</h1>
                </div>
                <div class="p-6 space-y-4">
                    @if ($errors->any())
                        <div class="bg-red-200 text-red-800 p-4 rounded-xl mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="storeForm" class="space-y-10" method="post" action="{{ route('postevent') }}"
                        enctype="multipart/form-data">
                        @csrf @method('post')
                        <!-- Penanggung Jawab Section -->
                        <div class="space-y-4">
                            <h1 class="text-2xl font-bold">Penanggung Jawab</h1>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Name:</label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="name" name="name" placeholder="John Doe" value="{{ old('name') }}"
                                        required />
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Nomor Whatsapp:</label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="no_telpon" name="no_telpon" placeholder="08XXXXXXXXX"
                                        value="{{ old('no_telpon') }}" required />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Foto KTP:</label>
                                <input type="file"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                    id="ktp" name="ktp" required>
                            </div>
                        </div>

                        <!-- Event Section -->
                        <div class="space-y-4">
                            <h1 class="text-2xl font-bold">Event</h1>
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Category Event:</label>
                                <select id="kategori" name="kategori_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                    required>
                                    <option></option>
                                    @foreach ($kategoris as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Nama Event:</label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="event" name="event" placeholder="Event" value="{{ old('event') }}"
                                        required />
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Event Organizer:</label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="organizer" name="organizer" placeholder="Organizer"
                                        value="{{ old('organizer') }}" required />
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Start Time:</label>
                                    <input type="datetime-local"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="start_time" name="start_time" value="{{ old('start_time') }}" required />
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">End time:</label>
                                    <input type="datetime-local"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="end_time" name="end_time" value="{{ old('end_time') }}" required />
                                </div>
                            </div>

                            <!-- Location Section -->
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Location:</label>
                                <input type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    id="location" name="location" value="{{ old('location') }}" required readonly />
                                <input type="text" id="searchLocation"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" />
                                <div class="flex gap-2">
                                    <button type="button" id="searchBtn"
                                        class="bg-blue-500 text-white p-2 rounded-lg w-full">Search</button>
                                    <button type="button" id="locateBtn"
                                        class="bg-green-500 text-white p-2 rounded-lg w-full">Use My Location</button>
                                </div>
                                <div id="map"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">

                                <!-- Poster Section -->
                                <div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-black">Poster 1:</label>
                                        <input type="file"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                            id="img" name="img" required>
                                    </div>
                                </div>
                                <div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-black">Poster 2:</label>
                                        <input type="file"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                            id="img2" name="img2" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div>
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Description:</label>
                                <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full h-44" id="description"
                                    name="description" placeholder="Description" required>{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Syarat Section --}}
                        <div>
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Syarat Dan Ketentuan:</label>
                                <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full h-44" id="syarat"
                                    name="syarat" placeholder="Tidak Boleh Membawa Alat Tajam" required>{{ old('syarat') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="bg-blue-500 text-white p-4 text-xl font-light w-full hover:bg-blue-600 transition-all delay-150 rounded-md">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        $('#description').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['font', ['bold', 'underline']],
                ['color', ['color']],
                ['para', ['ul', 'paragraph']],
            ]
        });
        $('#syarat').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['font', ['bold', 'underline']],
                ['color', ['color']],
                ['para', ['ul', 'paragraph']],
            ]
        });
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([-6.21462, 106.84513], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Add a marker
        var marker = L.marker([-6.21462, 106.84513]).addTo(map);
        marker.bindPopup('Your Event Location').openPopup();

        // Handle the search button click to get coordinates from the geocoding service
        document.getElementById('searchBtn').onclick = function() {
            var searchInput = document.getElementById('searchLocation').value;
            if (searchInput) {
                var url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchInput)}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            var location = data[0];
                            map.setView([location.lat, location.lon], 15);
                            marker.setLatLng([location.lat, location.lon]);
                            document.getElementById('location').value = location
                                .display_name; // Set descriptive location
                        } else {
                            alert('Location not found');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching location:', error);
                    });
            }
        };

        document.getElementById('locateBtn').onclick = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    // Update the map view
                    map.setView([lat, lon], 15);
                    marker.setLatLng([lat, lon]);

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.display_name) {
                                // Set the input value to the location name
                                document.getElementById('location').value = data.display_name;
                            } else {
                                document.getElementById('location').value = "Location name not found";
                            }
                        })
                        .catch(error => {
                            console.error("Error with reverse geocoding:", error);
                            document.getElementById('location').value = "Error fetching location name";
                        });
                }, function(error) {
                    // Handle geolocation errors
                    alert("Error fetching your location: " + error.message);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        };

        const form = document.getElementById('storeForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        });
    </script>

</body>

</html>
