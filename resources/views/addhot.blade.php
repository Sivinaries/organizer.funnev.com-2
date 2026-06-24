<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Hot</title>
    @include('layout.head')
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
                    <h1 class="font-extrabold text-3xl">Add hot</h1>
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
                    <form id="storeForm" class="space-y-10" method="post" action="{{ route('posthot') }}" enctype="multipart/form-data">
                        @csrf @method('post')
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Event:</label>
                            <select id="event" name="event_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-md w-full" required>
                                <option></option>
                                @foreach ($events as $eve)
                                    <option value="{{ $eve->id }}">{{ $eve->event }}</option>
                                @endforeach
                            </select>
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

    <script>
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
