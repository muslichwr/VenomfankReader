<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta name="series-id" content="{{ $series->id }}">
    <meta name="chapter-id" content="{{ $chapter->id }}">
    <meta name="content-type" content="{{ $contentType }}"> --}}
    <link href="{{ asset('src/output.css') }}" rel="stylesheet">
    <title>@yield('title') - Venomfank Readers</title>
    @stack('after-styles')
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @yield('content')

    @stack('after-scripts')
</body>
</html>