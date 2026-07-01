<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Star Unity — Customer Portal' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/sass/client/main.scss', 'resources/js/client.js'])
    @livewireStyles
</head>
<body>
    <div class="app">
        <x-client.drawer :active="$active ?? null" />
        {{ $slot }}
    </div>
    @livewireScripts
    @stack('scripts')
</body>
</html>
