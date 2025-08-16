<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- or mix() if you're using Laravel Mix --}}
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900">
     <div class="max-w-lg mx-auto mt-6 px-4">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        
    {{ $slot }}

    @livewireScripts
</body>
</html>
