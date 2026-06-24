<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Ticket</title>
    @include('layout.head')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

</head>

<body class="bg-gray-50">
    <!-- sidenav  -->
    @include('layout.sidebar')
    <!-- end sidenav -->
    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        <!-- Navbar -->
        @include('layout.navbar')
        <!-- end Navbar -->
        <div class="p-5">
            <div class="w-full bg-white rounded-md h-fit mx-auto">
                <div class="p-3 text-center">
                    <h1 class="font-extrabold text-3xl">Add ticket</h1>
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
                    <form id="storeForm" class="space-y-10" method="post" action="{{ route('postticket') }}"
                        enctype="multipart/form-data">
                        @csrf @method('post')
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Type:</label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="type" name="type" placeholder="Presale 1"
                                        value="{{ old('type') }}" required />
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Price (IDR):</label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="price" name="price" placeholder="100,000" value="{{ old('price') }}"
                                        required />
                                    <p id="priceOutput">Rp.</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Pcs:</label>
                                    <input type="number"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        id="pcs" name="pcs" placeholder="1XX" value="{{ old('pcs') }}"
                                        required />
                                </div>
                                <div class="space-y-2">
                                    <label class="font-semibold text-black">Event:</label>
                                    <select id="event" name="event_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full"
                                        required>
                                        <option></option>
                                        @foreach ($events as $eve)
                                            <option value="{{ $eve->id }}">{{ $eve->event }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Description:</label>
                            <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full h-44" id="desc"
                                name="desc" placeholder="Description" required>{{ old('desc') }}</textarea>
                        </div>
                        <button type="submit" id="submitBtn"
                            class="bg-blue-500 text-white p-4 text-xl font-light w-full hover:bg-blue-600 transition-all delay-150 rounded-md">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    $('#desc').summernote({
        placeholder: 'Hello stand alone ui',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['font', ['bold', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'paragraph']],
        ]
    });

    const priceInput = document.getElementById('price');
    const priceOutput = document.getElementById('priceOutput');

    function formatToIDR(value) {
        if (!value) return 'Rp. 0';
        return 'Rp. ' + parseInt(value.replace(/[^\d]/g, '')).toLocaleString('id-ID');
    }

    priceInput.addEventListener('input', () => {
        const formattedPrice = formatToIDR(priceInput.value);
        priceOutput.textContent = formattedPrice;
    });

          const form = document.getElementById('storeForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        });
</script>

</html>
