<div class="min-h-screen bg-gray-50 py-6">
    <style>
        /* Product search dropdown styling */
        .product-search-dropdown {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 9999 !important;
            position: absolute !important;
        }
        
        .product-search-container {
            position: relative;
        }
        
        .product-item:hover {
            background-color: #f3f4f6;
        }
        
        .product-item.selected {
            background-color: #dbeafe;
        }
        
        /* Ensure table cells have relative positioning for dropdown positioning */
        .invoice-table td {
            position: relative;
        }
        
        /* Override any overflow hidden on parent containers */
        .table-container {
            overflow: visible !important;
        }
    </style>
    <div class="px-4 sm:px-6 lg:px-6">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl text-gray-900">
                            {{ $invoice->isCashSale() ? 'Edit Cash Sale' : 'Edit Sales Invoice' }}
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $invoice->isCashSale() ? 'Update cash sale transaction details' : 'Edit sales invoice details for your customer' }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if(!$isEditing)
                        <button wire:click="enableEdit" wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg wire:loading.remove wire:target="enableEdit" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <svg wire:loading wire:target="enableEdit" class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Edit
                        </button>
                        @else
                        <button wire:click="save" wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Save
                        </button>
                        <button wire:click="save('save_and_send')" wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $invoice->isCashSale() ? 'Save & Print' : 'Save & Send' }}
                        </button>
                        @endif
                        <a href="{{ route('invoice.view', $invoice->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </a>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Invoice Details -->
                        <div class="bg-white shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ $invoice->isCashSale() ? 'Sale Details' : 'Invoice Details' }}
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $invoice->isCashSale() ? 'Receipt Number' : 'Invoice Number' }}
                                        </label>
                                        <input type="text" wire:model="invoice_number"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                        @error('invoice_number')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $invoice->isCashSale() ? 'Sale Date' : 'Invoice Date' }} *
                                        </label>
                                        <input type="date" wire:model.live.debounce.500ms="invoice_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            {{ $isEditing ? '' : 'readonly disabled' }}>
                                        @error('invoice_date')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                                        <select wire:model.live.debounce.500ms="partie_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            {{ $isEditing ? '' : 'disabled' }}>
                                            <option value="">Select Customer</option>
                                            <option value="{{ $cash_sale_customer->id }}"
                                                class="font-semibold text-green-600">
                                                💰 Cash Sale Customer
                                            </option>
                                            <optgroup label="Regular Customers">
                                                @foreach ($parties->where('id', '!=', $cash_sale_customer->id) as $partie)
                                                    <option value="{{ $partie->id }}">{{ $partie->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        @error('partie_id')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!$invoice->isCashSale())
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                        <input type="date" wire:model.live.debounce.500ms="due_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            {{ $isEditing ? '' : 'readonly disabled' }}>
                                        @error('due_date')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                                @if ($invoice->isCashSale())
                                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                                                        00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0
                                                        001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-green-800">Cash Sale Mode</h3>
                                                <div class="mt-2 text-sm text-green-700">
                                                    <ul class="list-disc list-inside space-y-1">
                                                        <li>Payment will be marked as received immediately</li>
                                                        <li>No due date required - payment is instant</li>
                                                        <li>Stock will be updated automatically</li>
                                                        <li>Receipt will be generated for customer</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="bg-white shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ $invoice->isCashSale() ? 'Sale Items' : 'Invoice Items' }}
                                    </h2>
                                    @if($isEditing)
                                    <div class="flex items-center space-x-3">
                                        <!-- Scan Barcode Button -->
                                        <button type="button" wire:click="toggleBarcodeScanner"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001Описание: -1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                                </path>
                                            </svg>
                                            Scan Barcode
                                        </button>
                                        <!-- Add Item Button -->
                                        <button type="button" wire:click="addInvoiceItem"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-teal-600 bg-teal-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add Item
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="py-6">
                                <!-- Barcode Scanner (Conditional) -->
                                @if($showBarcodeScanner && $isEditing)
                                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Scan Barcode (USB Scanner)
                                            </label>
                                            <button type="button" wire:click="toggleBarcodeScanner" 
                                                class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <input type="text" id="barcode-input" wire:model.live.debounce.500ms="barcodeInput" 
                                                wire:keydown.enter.prevent="handleBarcodeScan" 
                                                placeholder="Scan barcode here" autofocus
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <span class="text-gray-500 text-sm">↵ Enter</span>
                                            </div>
                                        </div>
                                        @error('barcodeInput')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                        <!-- Scan Status Indicator -->
                                        <div class="flex items-center text-sm text-gray-500 mt-2">
                                            <div class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></div>
                                            <span>Ready to scan - focus is on barcode field</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="overflow-x-auto table-container">
                                    <table class="min-w-full divide-y divide-gray-200 invoice-table">
                                        <thead class="bg-gray-50 w-full">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Product
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Unit Price
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total
                                                </th>
                                                @if($isEditing)
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Action
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($invoice_items as $index => $item)
                                                <tr>
                                                    <td class="px-2 py-4 invoice-table-cell">
                                                        <div class="product-search-container">
                                                            <input 
                                                                type="text" 
                                                                id="product-search-{{ $index }}"
                                                                placeholder="Search or select product..."
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                                autocomplete="off"
                                                                onkeyup="filterProducts({{ $index }}, this.value)"
                                                                onkeydown="handleKeyDown({{ $index }}, event)"
                                                                onfocus="showDropdown({{ $index }})"
                                                                onblur="handleBlur({{ $index }})"
                                                                {{ $isEditing ? '' : 'readonly disabled' }}
                                                                value="{{ $item['product_name'] ?? '' }}"
                                                            >
                                                            <!-- Dropdown -->
                                                            <div 
                                                                id="product-dropdown-{{ $index }}"
                                                                class="product-search-dropdown w-full mt-1 bg-white border border-gray-300 rounded-md max-h-60 overflow-auto hidden"
                                                                style="position: absolute; top: 100%; left: 0; z-index: 9999; min-width: 300px;"
                                                                onmousedown="event.preventDefault()"
                                                            >
                                                                <div id="product-list-{{ $index }}">
                                                                    @foreach ($products as $product)
                                                                        <div 
                                                                            class="product-item px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                                                                            onmousedown="selectProduct({{ $index }}, {{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                                            data-name="{{ strtolower($product->name) }}"
                                                                            data-code="{{ strtolower($product->item_code) }}"
                                                                        >
                                                                            <div class="flex justify-between items-center">
                                                                                <div>
                                                                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                                                                    <div class="text-sm text-gray-500">
                                                                                        Code: {{ $product->item_code }} | 
                                                                                        Stock: {{ $product->stock_quantity }} | 
                                                                                        Price: ₹{{ number_format($product->selling_price, 2) }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div id="no-results-{{ $index }}" class="px-4 py-2 text-gray-500 text-center hidden">
                                                                    No products found
                                                                </div>
                                                            </div>
                                                            <input type="hidden" wire:model.live.debounce.500ms="invoice_items.{{ $index }}.product_id" id="product-id-{{ $index }}">
                                                        </div>
                                                        @error('invoice_items.' . $index . '.product_id')
                                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number"
                                                            wire:model.live.debounce.500ms="invoice_items.{{ $index }}.quantity"
                                                            min="1" step="1"
                                                            class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                            {{ $isEditing ? '' : 'readonly disabled' }}>
                                                        @error('invoice_items.' . $index . '.quantity')
                                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number"
                                                            wire:model.live.debounce.500ms="invoice_items.{{ $index }}.unit_price"
                                                            min="0" step="0.01"
                                                            class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                            {{ $isEditing ? '' : 'readonly disabled' }}>
                                                        @error('invoice_items.' . $index . '.unit_price')
                                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                                        ₹{{ number_format($item['total'], 2) }}
                                                    </td>
                                                    @if($isEditing)
                                                    <td class="px-6 py-4">
                                                        @if (count($invoice_items) > 1)
                                                            <button type="button"
                                                                wire:click="removeInvoiceItem({{ $index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                                                                        7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Notes -->
                        <div class="bg-white shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">Additional Information</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 gap-6">
                                    @if (!$invoice->isCashSale())
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                                            <input type="text" wire:model.live.debounce.500ms="payment_terms"
                                                placeholder="e.g., Net 30 days"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                {{ $isEditing ? '' : 'readonly disabled' }}>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                        <textarea wire:model.live.debounce.500ms="terms_conditions" rows="3" placeholder="Enter terms and conditions..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            {{ $isEditing ? '' : 'readonly disabled' }}></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                        <textarea wire:model.live.debounce.500ms="notes" rows="3" placeholder="Any additional notes..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            {{ $isEditing ? '' : 'readonly disabled' }}></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Calculations -->
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow-sm rounded-lg sticky top-6">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ $invoice->isCashSale() ? 'Sale Summary' : 'Invoice Summary' }}
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- Sale Type Badge -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Type</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->isCashSale() ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $invoice->isCashSale() ? '💰 Cash Sale' : '📄 Credit Sale' }}
                                    </span>
                                </div>

                                <!-- Subtotal -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Subtotal</span>
                                    <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($subtotal ?: 0), 2) }}</span>
                                </div>

                                <!-- Discount -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Discount</span>
                                        <span class="text-sm font-medium text-gray-900">-₹{{ number_format((float)($discount_amount ?: 0), 2) }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="number" wire:model.live.debounce.500ms="discount_percentage"
                                                min="0" max="100" step="0.01" placeholder="%"
                                                class="w-full px-4 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                title="Discount Percentage" {{ $isEditing ? '' : 'readonly disabled' }}>
                                        </div>
                                        <div>
                                            <input type="number" wire:model.live.debounce.500ms="discount_amount" min="0"
                                                step="0.01" placeholder="Amount"
                                                class="w-full px-4 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                title="Discount Amount" {{ $isEditing ? '' : 'readonly disabled' }}>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tax -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Tax
                                            ({{ number_format((float)($tax_percentage ?: 0), 2) }}%)</span>
                                        <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($tax_amount ?: 0), 2) }}</span>
                                    </div>
                                    <div>
                                        <input type="number" wire:model.live.debounce.500ms="tax_percentage" min="0"
                                            max="100" step="0.01" placeholder="Tax %"
                                            class="w-full px-4 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            title="Tax Percentage" {{ $isEditing ? '' : 'readonly disabled' }}>
                                    </div>
                                </div>

                                <!-- Round Off -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Round Off</span>
                                    <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($round_off ?: 0), 2) }}</span>
                                </div>

                                <hr class="border-gray-200">

                                <!-- Total -->
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-medium text-gray-900">Total</span>
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold text-gray-900">₹{{ number_format((float)($total ?: 0), 2) }}</span>
                                        <div wire:loading class="ml-2">
                                            <svg class="animate-spin h-4 w-4 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-gray-200">

                                <!-- Payment Section -->
                                <div class="space-y-4">
                                    <h3 class="text-sm font-medium text-gray-900">Payment Details</h3>
                                    
                                    <!-- Payment Method -->
                                    <div class="space-y-2">
                                        <label class="block text-sm text-gray-600">Payment Method</label>
                                        <select wire:model.live.debounce.500ms="payment_method"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                            {{ $isEditing ? ($invoice->isCashSale() ? 'required' : '') : 'disabled' }}>
                                            <option value="">Select Payment Method</option>
                                            @foreach($paymentMethods as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('payment_method')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Paid Amount -->
                                    <div class="space-y-2">
                                        <label class="block text-sm text-gray-600">Paid Amount</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                            <input type="number" wire:model.live.debounce.500ms="paid_amount"
                                                min="0" max="{{ $total }}" step="0.01"
                                                placeholder="0.00"
                                                class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                {{ $isEditing ? '' : 'readonly disabled' }}>
                                        </div>
                                        @error('paid_amount')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Due Amount Display -->
                                    <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                        <span class="text-sm text-gray-600">Due Amount</span>
                                        <span class="text-sm font-medium {{ $due_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            ₹{{ number_format((float)($due_amount ?: 0), 2) }}
                                        </span>
                                    </div>

                                    <!-- Change Amount Display (for cash sales with overpayment) -->
                                    @if($invoice->isCashSale() && isset($change_amount) && $change_amount > 0)
                                        <div class="flex justify-between items-center py-2 px-3 bg-green-50 rounded border border-green-200">
                                            <span class="text-sm text-green-700 font-medium">Change to Return</span>
                                            <span class="text-sm font-bold text-green-700">
                                                ₹{{ number_format((float)($change_amount ?: 0), 2) }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Payment Status Badge -->
                                    @if($paid_amount > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Payment Status</span>
                                            @if($due_amount <= 0 && (!isset($change_amount) || $change_amount <= 0))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✓ Fully Paid
                                                </span>
                                            @elseif(isset($change_amount) && $change_amount > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    💰 Overpaid
                                                </span>
                                            @elseif($paid_amount > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⚡ Partial Payment
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Manual Recalculate Button (for edge cases) -->
                                @if($isEditing)
                                <div class="pt-2">
                                    <button type="button" wire:click="recalculate" wire:loading.attr="disabled"
                                        class="w-full text-xs text-gray-500 hover:text-gray-700 focus:outline-none flex items-center justify-center">
                                        <span wire:loading.remove wire:target="recalculate">🔄 Recalculate</span>
                                        <span wire:loading wire:target="recalculate" class="flex items-center">
                                            <svg class="animate-spin h-3 w-3 text-teal-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Calculating...
                                        </span>
                                    </button>
                                </div>
                                @endif

                                <div class="pt-4 border-t border-gray-200">
                                    <div class="bg-{{ $invoice->isCashSale() ? 'green' : 'teal' }}-50 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-{{ $invoice->isCashSale() ? 'green' : 'teal' }}-400 mr-2"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                @if ($invoice->isCashSale())
                                                    <path
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0
                                                        012-2h8a2 2 0 012 2v4a2 2 центральный банк 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0
                                                        000 4z">
                                                    </path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9
                                                        9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-{{ $invoice->isCashSale() ? 'green' : 'teal' }}-800">
                                                    {{ $invoice->isCashSale() ? 'Cash Payment' : 'Invoice Total' }}
                                                </p>
                                                <p class="text-xs text-{{ $invoice->isCashSale() ? 'green' : 'teal' }}-600">
                                                    {{ $invoice->isCashSale() ? 'Payment received immediately' : 'Amount payable by customer' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- JavaScript for delayed calculations and product search -->
        <script>
            // Product search functions
            function showDropdown(index) {
                // Close all other dropdowns first
                closeAllDropdowns();
                
                const dropdown = document.getElementById(`product-dropdown-${index}`);
                if (dropdown) {
                    dropdown.classList.remove('hidden');
                    
                    // Adjust position if dropdown would go off screen
                    const rect = dropdown.getBoundingClientRect();
                    const windowHeight = window.innerHeight;
                    
                    if (rect.bottom > windowHeight) {
                        // Position dropdown above the input if there's not enough space below
                        dropdown.style.top = 'auto';
                        dropdown.style.bottom = '100%';
                        dropdown.style.marginBottom = '4px';
                        dropdown.style.marginTop = '0';
                    } else {
                        // Default position below the input
                        dropdown.style.top = '100%';
                        dropdown.style.bottom = 'auto';
                        dropdown.style.marginTop = '4px';
                        dropdown.style.marginBottom = '0';
                    }
                }
            }
            
            function hideDropdown(index) {
                const dropdown = document.getElementById(`product-dropdown-${index}`);
                if (dropdown) {
                    dropdown.classList.add('hidden');
                }
            }
            
            function closeAllDropdowns() {
                const dropdowns = document.querySelectorAll('[id^="product-dropdown-"]');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
            
            function handleBlur(index) {
                // Store the timeout so it can be cleared if needed
                window.blurTimeout = setTimeout(() => {
                    const dropdown = document.getElementById(`product-dropdown-${index}`);
                    const activeElement = document.activeElement;
                    
                    // Don't hide if focus is still within the dropdown or container
                    if (dropdown && !dropdown.contains(activeElement)) {
                        hideDropdown(index);
                    }
                }, 300);
            }
            
            function handleKeyDown(index, event) {
                const dropdown = document.getElementById(`product-dropdown-${index}`);
                if (!dropdown || dropdown.classList.contains('hidden')) return;
                
                const visibleItems = dropdown.querySelectorAll('.product-item:not([style*="display: none"])');
                
                if (event.key === 'Escape') {
                    hideDropdown(index);
                    event.preventDefault();
                } else if (event.key === 'ArrowDown') {
                    // Navigate down
                    event.preventDefault();
                    // You can add arrow key navigation here if needed
                } else if (event.key === 'ArrowUp') {
                    // Navigate up
                    event.preventDefault();
                    // You can add arrow key navigation here if needed
                } else if (event.key === 'Enter' && visibleItems.length > 0) {
                    // Select first visible item
                    event.preventDefault();
                    visibleItems[0].click();
                }
            }
            
            function filterProducts(index, searchTerm) {
                const dropdown = document.getElementById(`productogels: product-dropdown-${index}`);
                const productList = document.getElementById(`product-list-${index}`);
                const noResults = document.getElementById(`no-results-${index}`);
                
                if (!dropdown || !productList) return;
                
                const items = productList.querySelectorAll('.product-item');
                let visibleCount = 0;
                
                items.forEach(item => {
                    const name = item.getAttribute('data-name');
                    const code = item.getAttribute('data-code');
                    const searchLower = searchTerm.toLowerCase();
                    
                    if (searchTerm === '' || name.includes(searchLower) || code.includes(searchLower)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                if (noResults) {
                    if (visibleCount === 0 && searchTerm !== '') {
                        noResults.classList.remove('hidden');
                    } else {
                        noResults.classList.add('hidden');
                    }
                }
                
                // Show dropdown if not already visible
                dropdown.classList.remove('hidden');
            }
            
            function selectProduct(index, productId, productName) {
                // Set the hidden input value
                const hiddenInput = document.getElementById(`product-id-${index}`);
                const searchInput = document.getElementById(`product-search-${index}`);
                
                if (hiddenInput) {
                    hiddenInput.value = productId;
                    // Trigger Livewire change event
                    hiddenInput.dispatchEvent(new Event('input'));
                    // Also trigger change event for Livewire to detect
                    const changeEvent = new Event('change', { bubbles: true });
                    hiddenInput.dispatchEvent(changeEvent);
                }
                
                if (searchInput) {
                    searchInput.value = productName;
                }
                
                // Hide dropdown immediately
                hideDropdown(index);
                
                // Prevent the blur event from interfering
                clearTimeout(window.blurTimeout);
            }
            
            // Initialize search inputs when new rows are added
            function initializeProductSearch() {
                // This function can be called when new rows are added
                const searchInputs = document.querySelectorAll('[id^="product-search-"]');
                searchInputs.forEach(input => {
                    const index = input.id.split('-').pop();
                    const hiddenInput = document.getElementById(`product-id-${index}`);
                    
                    // If there's already a selected product, show its name
                    if (hiddenInput && hiddenInput.value) {
                        // Find the product name from the dropdown items
                        const dropdown = document.getElementById(`product-dropdown-${index}`);
                        if (dropdown) {
                            const selectedItem = dropdown.querySelector(`[onclick*="${hiddenInput.value}"]`);
                            if (selectedItem) {
                                const productName = selectedItem.querySelector('.font-medium').textContent;
                                input.value = productName;
                            }
                        }
                    }
                });
            }

            document.addEventListener('livewire:initialized', () => {
                let discountPercentageTimeout;
                let discountAmountTimeout;
                let dueAmountTimeout;

                Livewire.on('delay-calculate-discount-from-percentage', () => {
                    clearTimeout(discountPercentageTimeout);
                    discountPercentageTimeout = setTimeout(() => {
                        @this.calculateDiscountFromPercentage();
                    }, 500);
                });

                Livewire.on('delay-calculate-discount-from-amount', () => {
                    clearTimeout(discountAmountTimeout);
                    discountAmountTimeout = setTimeout(() => {
                        @this.calculateDiscountFromAmount();
                    }, 500);
                });

                Livewire.on('delay-calculate-due', () => {
                    clearTimeout(dueAmountTimeout);
                    dueAmountTimeout = setTimeout(() => {
                        @this.calculateDueFromPaid();
                    }, 300);
                });

                Livewire.on('focus-barcode-input', () => {
                    setTimeout(() => {
                        const input = document.getElementById('barcode-input');
                        if (input) {
                            input.focus();
                        }
                    }, 100);
                });
                
                // Initialize product search after Livewire loads
                setTimeout(initializeProductSearch, 100);
                
                // Add event listeners for closing dropdowns
                let isScrollingInDropdown = false;
                
                // Modified scroll event listener to not close when scrolling inside dropdown
                document.addEventListener('scroll', function(e) {
                    // Check if scrolling is happening inside a dropdown
                    const target = e.target;
                    if (target && target.closest && target.closest('[id^="product-dropdown-"]')) {
                        return; // Don't close if scrolling inside dropdown
                    }
                    closeAllDropdowns();
                }, true);
                
                document.addEventListener('click', function(e) {
                    // Close dropdowns if clicking outside of product search containers
                    if (!e.target.closest('.product-search-container')) {
                        closeAllDropdowns();
                    }
                });
                
                // Close dropdowns on window resize
                window.addEventListener('resize', closeAllDropdowns);
            });
            
            // Re-initialize when Livewire updates the component
            document.addEventListener('livewire:updated', () => {
                setTimeout(initializeProductSearch, 100);
            });
        </script>
    </div>