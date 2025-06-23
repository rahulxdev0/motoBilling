<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        <div class="p-2 bg-gray-200 h-screen">
           <div class="bg-gray-200 h-[98vh] flex gap-2">
             <livewire:components.sidebar />
                {{ $slot }}
           </div>
        </div>
    </body>
</html>
