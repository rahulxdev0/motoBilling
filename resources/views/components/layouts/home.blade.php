<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <!-- Page Navigation Loader Component -->
    <livewire:components.loader-component />
            {{ $slot }}
   </body>

<script>
document.addEventListener('livewire:init', () => {
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
});
</script>

</html>