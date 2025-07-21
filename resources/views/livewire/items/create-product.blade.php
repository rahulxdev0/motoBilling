<div class="min-h-screen bg-gray-50 py-8">


    <div class="px-4 sm:px-6 lg:px-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-teal-700">Create New Product</h1>
                    <p class="mt-2 text-sm text-gray-600">Add a new product to your inventory with detailed information and barcode generation.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a wire:navigate href="{{ route('items.manage') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Form -->
        <form wire:submit.prevent="save" class="space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="p-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Basic Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input wire:model.blur="name" type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                                    placeholder="Enter product name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Brand -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <input wire:model="brand" type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter brand name">
                                @error('brand')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.change="category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-300 @enderror">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Item Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Item Code <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="item_code" type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('item_code') border-red-300 @enderror"
                                    placeholder="Auto-generated">
                                @error('item_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    SKU <span class="text-red-500">*</span>
                                </label>
                                <div class="flex">
                                    <input wire:model="sku" type="text"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-blue-500 @error('sku') border-red-300 @enderror"
                                        placeholder="Stock Keeping Unit">
                                    <button type="button" wire:click="generateSku"
                                        class="px-4 py-3 bg-teal-600 text-white rounded-r-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('sku')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter product description"></textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            Pricing & Stock Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Purchase Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Purchase Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-500">₹</span>
                                    <input wire:model="purchase_price" type="number" step="0.01"
                                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('purchase_price') border-red-300 @enderror"
                                        placeholder="0.00">
                                </div>
                                @error('purchase_price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Selling Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Selling Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-500">₹</span>
                                    <input wire:model="selling_price" type="number" step="0.01"
                                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('selling_price') border-red-300 @enderror"
                                        placeholder="0.00">
                                </div>
                                @error('selling_price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- MRP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">MRP</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-500">₹</span>
                                    <input wire:model="mrp" type="number" step="0.01"
                                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mrp') border-red-300 @enderror"
                                        placeholder="0.00">
                                </div>
                                @error('mrp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Stock Quantity <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="stock_quantity" type="number"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-300 @enderror"
                                    placeholder="0">
                                @error('stock_quantity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reorder Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Reorder Level <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="reorder_level" type="number"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reorder_level') border-red-300 @enderror"
                                    placeholder="10">
                                @error('reorder_level')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Unit <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="unit"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach ($this->units as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Barcode & Additional Info -->
                <div class="space-y-6">

                    <!-- Barcode Section -->
                    <div class="bg-gray-50 border border-gray-400 rounded-lg py-6 px-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            Barcode
                        </h3>

                        <div class="space-y-4">
                            <!-- Barcode Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input wire:model="barcode" type="text"
                                        class="flex-1 px-4 py-2 col-span-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter or scan barcode">
                                    <div class="flex gap-1">
                                        <button type="button" wire:click="generateBarcode"
                                        class="px-3 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500"
                                        title="Auto Generate">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="clearBarcode"
                                        class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                        title="Clear">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    </div>
                                </div>
                                @error('barcode')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Barcode Label Generator -->
                            @if($barcode && $name)
                                <div class="border-t pt-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-sm font-medium text-gray-700">Label Preview</span>
                                        <div class="flex items-center gap-2">
                                            <input type="number" min="1" wire:model="barcodePrintQty"
                                                class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                                                placeholder="Qty">
                                            <button type="button" wire:click="generateBarcodeLabel"
                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                Generate
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if(isset($barcodeLabel))
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50" id="barcodeLabelPreview">
                                            @for($i = 0; $i < ($barcodePrintQty ?? 1); $i++)
                                                <div class="flex flex-col items-center mb-3 p-2 bg-white rounded border">
                                                    {!! $barcodeLabel !!}
                                                    <div class="text-center mt-1">
                                                        <p class="font-normal text-xs">{{ $name }}</p>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        
<button
    type="button"
    @click="$dispatch('barcode-generated')"
    class="mt-3 w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
>
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Print Labels
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status & Additional Info -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Additional Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input wire:model="status" type="radio" value="active"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input wire:model="status" type="radio" value="inactive"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Model Compatibility -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model Compatibility</label>
                                <input wire:model="model_compatibility" type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., Honda CBR 150R">
                                @error('model_compatibility')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full flex items-center justify-center px-6 py-3 bg-teal-600 text-white text-lg font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-50 transition-colors duration-200"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    
                                    Create Product
                                </span>
                                <div wire:loading wire:target="save" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Creating...
                                </div>
                            </button>
                            
                            <a wire:navigate href="{{ route('items.manage') }}"
                                class="w-full flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 text-lg font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<!-- Modal: Barcode Label Preview & Print -->
<div
    x-data="{ open: false }"
    x-show="open"
    @barcode-generated.window="open = true"
    @keydown.escape.window="open = false"
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black/50"
>
    <div class="bg-white rounded-lg print:p-0 p-6 max-w-md w-full print:shadow-none shadow-lg" @click.away="open = false">
        <h2 class="text-lg font-bold text-gray-800 mb-4 print:hidden">Barcode Label Preview</h2>

        @if(isset($barcodeLabel))
            <div class="overflow-y-auto max-h-80 w-full print:overflow-visible print:max-h-full" id="printable">
                @for($i = 0; $i < ($barcodePrintQty ?? 1); $i++)
                    <div class="flex flex-col items-center mb-3 p-2 bg-white rounded border">
                        {!! $barcodeLabel !!}
                        <div class="text-center mt-1">
                            <p class="font-normal text-xs">{{ $name }}</p>
                        </div>
                    </div>
                @endfor
            </div>
        @else
            <p class="text-sm text-gray-500 print:hidden">No barcode generated yet.</p>
        @endif

        <div class="mt-6 flex justify-end space-x-2 print:hidden">
            <button @click="window.print()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Print
            </button>
            <button @click="open = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>

    
</div>


