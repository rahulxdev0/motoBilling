<div>


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
            .purchase-table td {
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
                                Create Purchase Invoice
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">
                                Record new inventory purchases from suppliers
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z"></path>
                                </svg>
                                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Save as Draft
                            </button>
                            <button type="button" wire:click="save('save_and_send')" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Save & Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Purchase Details -->
                        <div class="bg-white shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Purchase Details
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Invoice Number
                                        </label>
                                        <input type="text" wire:model="invoice_number"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                            readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Supplier *
                                        </label>
                                        <select wire:model.live="partie_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select Supplier</option>
                                            @foreach ($parties as $party)
                                                <option value="{{ $party->id }}">{{ $party->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('partie_id')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Invoice Date *
                                        </label>
                                        <input type="date" wire:model.live="invoice_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('invoice_date')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                        <input type="date" wire:model="due_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Items -->
                        <div class="bg-white shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Purchased Items
                                    </h2>
                                    <div class="flex items-center space-x-3">
                                        <!-- Scan Barcode Button -->
                                        <button type="button" wire:click="toggleBarcodeScanner"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
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
                                </div>
                            </div>
                            <div class="py-6">
                                <!-- Barcode Scanner (Conditional) -->
                                @if($showBarcodeScanner)
                                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200 mx-6">
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
                                            <input type="text" id="barcode-input" wire:model.live="barcodeInput" 
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
                                
                                <div class="overflow-x-auto table-container px-6">
                                    <table class="min-w-full divide-y divide-gray-200 purchase-table">
                                        <thead class="bg-gray-50 w-full">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Product
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Unit Cost
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($invoice_items as $index => $item)
                                                <tr>
                                                    <td class="px-2 py-4 w-80">
                                                        <div class="product-search-container">
                                                            @if(!empty($item['product_name']))
                                                                <div class="font-medium text-gray-900">{{ $item['product_name'] }}</div>
                                                            @else
                                                                <input 
                                                                    type="text" 
                                                                    id="product-search-{{ $index }}"
                                                                    placeholder="Search or select product..."
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                    autocomplete="off"
                                                                    onkeyup="filterProducts({{ $index }}, this.value)"
                                                                    onkeydown="handleKeyDown({{ $index }}, event)"
                                                                    onfocus="showDropdown({{ $index }})"
                                                                    onblur="handleBlur({{ $index }})"
                                                                >
                                                                <!-- Dropdown -->
                                                                <div 
                                                                    id="product-dropdown-{{ $index }}"
                                                                    class="product-search-dropdown w-full mt-1 bg-white border border-gray-300 rounded-md max-h-60 overflow-auto hidden"
                                                                    style="position: absolute; top: 100%; left: 0; z-index: 9999; min-width: 300px;"
                                                                    onmousedown="event.preventDefault()"
                                                                >
                                                                    <!-- Search results will be populated by JavaScript -->
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
                                                                                            Purchase Price: ₹{{ number_format($product->purchase_price, 2) }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <!-- No results message -->
                                                                    <div id="no-results-{{ $index }}" class="px-4 py-2 text-gray-500 text-center hidden">
                                                                        No products found
                                                                    </div>
                                                                </div>
                                                                <!-- Hidden input for actual value -->
                                                                <input type="hidden" wire:model.live="invoice_items.{{ $index }}.product_id" id="product-id-{{ $index }}">
                                                            @endif
                                                        </div>
                                                        @error('invoice_items.' . $index . '.product_id')
                                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number"
                                                            wire:model.live.debounce.300ms="invoice_items.{{ $index }}.quantity"
                                                            min="1" step="1"
                                                            class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        @error('invoice_items.' . $index . '.quantity')
                                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number"
                                                            wire:model.live.debounce.300ms="invoice_items.{{ $index }}.unit_price"
                                                            min="0" step="0.01"
                                                            class="w-28 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        @error('invoice_items.' . $index . '.unit_price')
                                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            ₹{{ number_format($item['total'], 2) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <button type="button" wire:click="removeInvoiceItem({{ $index }})"
                                                            class="text-red-600 hover:text-red-900">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </td>
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
                                <h2 class="text-lg font-medium text-gray-900">Terms & Notes</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                                        <textarea wire:model="payment_terms" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Enter payment terms..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                        <textarea wire:model="notes" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Enter notes..."></textarea>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                    <textarea wire:model="terms_conditions" rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter terms and conditions..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Calculations -->
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow-sm rounded-lg sticky top-6">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">Purchase Summary</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- GST Summary (if items have GST) -->
                                @if(!empty($gst_summary))
                                    <div class="border-t pt-4">
                                        <h3 class="text-sm font-medium text-gray-900 mb-2">GST Summary</h3>
                                        <div class="bg-gray-50 rounded p-3">
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="text-gray-600 border-b border-gray-200">
                                                        <th class="text-left py-1">HSN</th>
                                                        <th class="text-right py-1">Taxable</th>
                                                        <th class="text-right py-1">Rate</th>
                                                        <th class="text-right py-1">GST</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($gst_summary as $hsn => $summary)
                                                        <tr>
                                                            <td class="py-1 text-gray-900">{{ $hsn ?: 'N/A' }}</td>
                                                            <td class="text-right py-1 text-gray-900">₹{{ number_format($summary['taxable_amount'], 2) }}</td>
                                                            <td class="text-right py-1 text-gray-900">{{ $summary['gst_rate'] }}%</td>
                                                            <td class="text-right py-1 text-gray-900">₹{{ number_format($summary['tax_amount'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Total GST -->
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-sm text-gray-600">Total GST</span>
                                            <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($tax_amount ?: 0), 2) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Subtotal -->
                                <div class="flex justify-between items-center border-t pt-4">
                                    <span class="text-sm text-gray-600">Subtotal</span>
                                    <span class="text-sm text-gray-900">₹{{ number_format((float)($subtotal ?: 0), 2) }}</span>
                                </div>

                                <!-- Discount Display -->
                                @if($discount_amount > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Discount</span>
                                        <span class="text-sm text-red-600">-₹{{ number_format((float)$discount_amount, 2) }}</span>
                                    </div>
                                @endif

                                <!-- Round Off -->
                                @if($round_off != 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Round Off</span>
                                        <span class="text-sm text-gray-900">₹{{ number_format((float)$round_off, 2) }}</span>
                                    </div>
                                @endif

                                <!-- Total -->
                                <div class="flex justify-between items-center border-t pt-4 border-gray-300">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-gray-900">₹{{ number_format((float)($total ?: 0), 2) }}</span>
                                </div>

                                <!-- Payment Section -->
                                <div class="border-t pt-4 space-y-4">
                                    <h3 class="text-sm font-medium text-gray-900">Payment Details</h3>
                                    
                                    <!-- Payment Method -->
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Payment Method</label>
                                        <select wire:model.live="payment_method" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select Method</option>
                                            @foreach($paymentMethods as $key => $method)
                                                <option value="{{ $key }}">{{ $method }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Paid Amount -->
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Paid Amount</label>
                                        <input type="number" wire:model.live.debounce.300ms="paid_amount"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0.00" step="0.01" min="0">
                                    </div>

                                    <!-- Fully Paid Checkbox -->
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model.live="isFullyPaid" id="fully_paid" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="fully_paid" class="ml-2 text-sm text-gray-600">Mark as fully paid</label>
                                    </div>

                                    <!-- Due Amount -->
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Due Amount</span>
                                        <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($due_amount ?: 0), 2) }}</span>
                                    </div>

                                    <!-- Change Amount (for cash purchases) -->
                                    @if($this->isCashPurchase() && $change_amount > 0)
                                        <div class="flex justify-between items-center bg-green-50 px-3 py-2 rounded">
                                            <span class="text-sm text-green-600">Change to Return</span>
                                            <span class="text-sm font-medium text-green-600">₹{{ number_format((float)$change_amount, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
        const dropdown = document.getElementById(`product-dropdown-${index}`);
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
