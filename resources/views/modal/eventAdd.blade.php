@php
    $kategoris = \App\Models\Kategori::all();
@endphp

<!-- MODAL ADD EVENT -->
<div id="addModal"
    class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto px-4 py-6">
    <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl relative my-5 flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                Ajukan Event
            </h2>
            <button id="closeAddModal"
                class="text-gray-400 hover:text-red-500 transition text-2xl leading-none">&times;</button>
        </div>

        <!-- Body -->
        <div class="p-8 overflow-y-auto">
            <form id="addForm" method="post" action="{{ route('postevent') }}" enctype="multipart/form-data"
                novalidate data-validate class="space-y-8">
                @csrf

                <!-- SECTION 1: Penanggung Jawab -->
                <div>
                    <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider mb-4 border-b pb-2">
                        Penanggung Jawab</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" placeholder="John Doe" value="{{ old('name') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor Whatsapp
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="no_telpon" id="no_telpon" placeholder="08XXXXXXXXX" value="{{ old('no_telpon') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Foto KTP
                                <span class="text-red-500">*</span></label>
                            <input type="file" name="ktp" id="ktp" accept="image/*"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Event -->
                <div>
                    <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider mb-4 border-b pb-2">Event</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category Event
                                <span class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                                <option value="">-- Pilih Category --</option>
                                @foreach ($kategoris as $item)
                                    <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Event
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="event" id="event" placeholder="Event" value="{{ old('event') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Event Organizer
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="organizer" id="organizer" placeholder="Organizer" value="{{ old('organizer') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Time
                                <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Time
                                <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Lokasi -->
                <div>
                    <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider mb-4 border-b pb-2">Lokasi</h3>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Location
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border bg-gray-50" required readonly>
                        <input type="text" id="searchLocation" placeholder="Cari lokasi..."
                            class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500">
                        <div class="flex gap-2">
                            <x-button type="button" id="searchBtn" variant="blue" size="md"
                                class="w-full justify-center">Search</x-button>
                            <x-button type="button" id="locateBtn" variant="success" size="md"
                                class="w-full justify-center">Use My Location</x-button>
                        </div>
                        <div id="map" class="rounded-lg" style="height:320px;width:100%"></div>
                    </div>
                </div>

                <!-- SECTION 4: Poster -->
                <div>
                    <h3 class="text-sm font-bold text-orange-600 uppercase tracking-wider mb-4 border-b pb-2">Poster</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Poster 1
                                <span class="text-red-500">*</span></label>
                            <input type="file" name="img" id="img" accept="image/*"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Poster 2
                                <span class="text-red-500">*</span></label>
                            <input type="file" name="img2" id="img2" accept="image/*"
                                class="w-full rounded-lg border-gray-300 shadow-sm p-2 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: Deskripsi -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Description
                            <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" data-required data-label="Description" class="w-full">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Syarat Dan Ketentuan
                            <span class="text-red-500">*</span></label>
                        <textarea name="syarat" id="syarat" data-required data-label="Syarat Dan Ketentuan" class="w-full">{{ old('syarat') }}</textarea>
                    </div>
                </div>

                <x-button type="submit" variant="primary" icon="paper-plane"
                    class="w-full justify-center">Ajukan Event</x-button>
            </form>
        </div>
    </div>
</div>
