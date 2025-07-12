<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        /* Optimized loader animation */
        .loader {
            border-top-color: #3498db;
            animation: spinner 0.8s linear infinite;
            will-change: transform;
        }
        
        @keyframes spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <style>
        /* Loader animation */
        .loader {
            border-top-color: #3498db;
            -webkit-animation: spinner 1.5s linear infinite;
            animation: spinner 1.5s linear infinite;
        }
        
        @-webkit-keyframes spinner {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        
        @keyframes spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Page Navigation Loader Component -->
    <livewire:components.loader-component />

    <div class="md:p-2 md:bg-gray-200 h-screen">
        <div class="bg-gray-100 md:bg-gray-200 h-screen md:h-[98vh] flex gap-3">
            <livewire:components.sidebar />
            <div class="bg-white h-full md:rounded-lg md:shadow-lg overflow-y-auto flex-1">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('livewire:init', () => {
        // Register loader hooks for navigation
      
    });

    
    </script>
