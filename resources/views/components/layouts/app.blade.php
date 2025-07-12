<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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

        

        document.addEventListener('click', function(e) {
            if (e.target && (e.target.id === 'printBarcodeBtn' || e.target.closest(
                '#printBarcodeBtn'))) {
                printBarcodeLabel();
            }
        });
        // Register loader hooks for navigation
        Livewire.on('register-loader-hooks', () => {
            // Show loader when navigation starts
            document.addEventListener('livewire:navigating', () => {
                Livewire.dispatch('showLoader');
            });

            // Hide loader when navigation is complete
            document.addEventListener('livewire:navigated', () => {
                // Small delay to ensure smooth transition
                setTimeout(() => {
                    Livewire.dispatch('hideLoader');
                }, 300);
            });
        });

        Livewire.on('scanner-mounted', (event) => {
            const barcodeInput = document.getElementById('barcode-input');

            // Focus on barcode input when component is ready
            setTimeout(() => barcodeInput.focus(), 100);

            // Handle barcode scanner input (fast typing)
            barcodeInput.addEventListener('input', function(e) {
                // USB scanners typically send an Enter key after scanning
                if (e.inputType === 'insertLineBreak') {
                    // Dispatch event to Livewire component
                    Livewire.dispatch('barcode-scanned');
                }
            });

            // Re-focus after adding items
            Livewire.hook('commit', ({
                component,
                commit,
                respond,
                succeed,
                fail
            }) => {
                respond(() => {
                    succeed(({
                        snapshot,
                        effect
                    }) => {
                        // Check if this is the invoice create component
                        if (barcodeInput && component.name ===
                            'invoice.create-invoice') {
                            barcodeInput.focus();
                        }
                    });
                });
            });
        });
    });
</script>
