<div class="flex-1 p-6 bg-gray-50 overflow-y-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <!-- Title and Action Buttons -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Items</h1>
                <p class="text-gray-600 mt-1">Manage your product inventory and stock levels</p>
            </div>
            <div class="flex space-x-4">
                <!-- Export PDF Button -->
                <button wire:click="exportPdf"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>Export PDF</span>
                </button>

                <!-- Create Item Button -->
                <button wire:click="handleCreateItem"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Item</span>
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Items</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($products) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Active inventory</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Stock Value -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Stock Value</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ${{ number_format(collect($products)->sum(function ($product) {
    return $product['stock_quantity'] * $product['purchase_price'];
}), 2) }}
                        </p>
                        <p class="text-sm text-green-600 mt-1">Purchase price basis</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ collect($products)->where('low_stock', true)->count() }}
                        </p>
                        <p class="text-sm text-red-600 mt-1">Need restocking</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Stock Quantity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Stock QTY</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ collect($products)->sum('stock_quantity') }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Units in stock</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Field -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            placeholder="Search by item name or code..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>

                <!-- Category Filter with Add Button -->
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <select wire:model.live="selectedCategory" id="category"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category['name'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="openAddCategoryModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg flex items-center space-x-1 transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm">Add</span>
                    </button>
                </div>
            </div>

            <!-- Filter Actions -->
            @if($search || $selectedCategory)
                <div class="mt-4 pt-4 border-t border-gray-200 px-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <span>Filters applied:</span>
                            @if($search)
                                <span class="bg-teal-100 text-teal-800 px-2 py-1 rounded">Search: "{{ $search }}"</span>
                            @endif
                            @if($selectedCategory)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $selectedCategory }}</span>
                            @endif
                        </div>
                        <button wire:click="clearFilters" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                            Clear all filters
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Table Component -->
    <livewire:items.components.product-table :products="$products" :sortBy="$sortBy" :sortDirection="$sortDirection"
        :key="$search . $selectedCategory . $sortBy . $sortDirection" />

    <!-- Category Modal Component -->
    <livewire:components.category-modal />
</div>