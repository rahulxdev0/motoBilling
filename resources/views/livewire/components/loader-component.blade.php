    <!-- Page Navigation Loader -->
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300 {{ $isLoading ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
        <div class="bg-white p-5 rounded-lg shadow-xl flex items-center space-x-4">
            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-10 w-10"></div>
            <div class="text-gray-800 font-semibold">Loading...</div>
        </div>
    </div>

