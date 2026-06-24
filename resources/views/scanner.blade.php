<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scanner</title>
    @include('layout.head')
    <style>
        #reader {
            width: 100%;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            overflow: hidden;
        }

        #output {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    @include('layout.sidebar')

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                    <i class="fas fa-qrcode text-orange-500"></i> Scanner
                </h1>
                <p class="text-sm text-gray-500">Arahkan kamera ke QR tiket pengunjung</p>
            </div>

            <!-- Scanner Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div id="reader"></div>
                <div id="output" class="text-gray-700"></div>
            </div>
        </div>
    </main>

    @include('sweetalert::alert')

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`QR Code scanned: ${decodedText}`);
            document.getElementById("output").textContent = "⏳ Mengalihkan ke detail tiket...";
            window.location.href = `/scan/${decodedText}`;
        }

        const qrScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        qrScanner.render(onScanSuccess);
    </script>
</body>

</html>
