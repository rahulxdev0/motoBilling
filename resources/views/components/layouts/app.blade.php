<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        <div class="md:p-2 md:bg-gray-200 h-screen">
           <div class="bg-gray-100 md:bg-gray-200 h-screen md:h-[98vh] flex gap-2">
                <livewire:components.sidebar />
                <div class="bg-white h-full md:rounded-lg md:shadow overflow-y-auto flex-1">
                    {{ $slot }}
                </div>
           </div>
        </div>
    </body>
</html>

<script>
document.addEventListener('livewire:init', () => {
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
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            respond(() => {
                succeed(({ snapshot, effect }) => {
                    // Check if this is the invoice create component
                    if (barcodeInput && component.name === 'invoice.create-invoice') {
                        barcodeInput.focus();
                    }
                });
            });
        });
    });
});
</script>