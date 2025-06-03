<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi: {{ $event->agenda }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <div class="text-center mb-6">
        <img src="{{ asset('pln.png') }}" alt="Logo" class="mx-auto mb-4 w-24 h-24">
    </div>
        <h1 class="text-2xl font-bold mb-4">Absensi: {{ $event->agenda }}</h1>
        <p class="text-gray-600 mb-2">Tanggal: {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</p>
        <p class="text-gray-600 mb-6">Waktu: {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} | Lokasi: {{ $event->place }}</p>

        @if (Session::has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ Session::get('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('event.attend.process', $event->slug) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="position" class="block text-gray-700 text-sm font-bold mb-2">Position:</label>
                <input type="text" id="position" name="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email (Opsional):</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon (Opsional):</label>
                <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="signature" class="block text-gray-700 text-sm font-bold mb-2">Tanda Tangan (Opsional):</label>
                <div class="border rounded bg-white">
                    <canvas id="signature-pad" width="400" height="150" class="w-full"></canvas>
                </div>
                <input type="hidden" name="signature" id="signature">
                <div class="flex gap-2 mt-2">
                    <button type="button" id="clear-signature" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Clear</button>
                </div>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Absen Sekarang</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255,255,255,0)', // transparan
        });

        // Set nilai ke input hidden saat submit
        document.querySelector('form').addEventListener('submit', function (e) {
            if (!signaturePad.isEmpty()) {
                document.getElementById('signature').value = signaturePad.toDataURL();
            }
        });

        // Tombol clear
        document.getElementById('clear-signature').addEventListener('click', function () {
            signaturePad.clear();
            document.getElementById('signature').value = '';
        });
    </script>
</body>
</html>
