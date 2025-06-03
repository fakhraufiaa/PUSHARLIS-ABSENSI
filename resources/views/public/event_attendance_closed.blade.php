<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Ditutup - {{ $event->agenda }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8 flex items-center justify-center min-h-screen">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md text-center">
        <h1 class="text-3xl font-bold text-red-600 mb-4">Absensi Ditutup</h1>
        <p class="text-gray-700 text-lg mb-4">Event "{{ $event->agenda }}" telah selesai diselenggarakan atau absensinya telah dinonaktifkan.</p>
        <p class="text-gray-500">Terima kasih atas partisipasi Anda.</p>
        {{-- <a href="/" class="mt-6 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Kembali ke Beranda</a> --}}
    </div>
</body>
</html>
