<div>


    <div class="min-h-screen bg-gray-50 py-6">
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
                            <button type="button" wire:click="save('draft')"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                Save as Draft
                            </button>
                            <button type="button" wire:click="save('save_and_send')"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
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
                        <!-- Invoice Details -->
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
                                            Invoice Date *
                                        </label>
                                        <input type="date" wire:model.live="invoice_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @error('invoice_date')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                                        <select wire:model.live="partie_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select Supplier</option>
                                            @foreach ($parties as $partie)
                                                <option value="{{ $partie->id }}">{{ $partie->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('partie_id')
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

                        <!-- Invoice Items -->
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
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                                </path>
                                            </svg>
                                            Scan Barcode
                                        </button>
                                        <!-- Add Item Button -->
                                        <button type="button" wire:click="addInvoiceItem"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-teal-600 bg-teal-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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
                                @if ($showBarcodeScanner)
                                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">
                                                Scan Barcode (USB Scanner)
                                            </label>
                                            <button type="button" wire:click="toggleBarcodeScanner"
                                                class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 w-full">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Product
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Unit Cost
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($invoice_items as $index => $item)
                                                <tr>
                                                    <td class="px-2 py-4">
                                                        <select
                                                            wire:model.live="invoice_items.{{ $index }}.product_id"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="">Select Product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('invoice_items.' . $index . '.product_id')
                                                            <span
                                                                class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number"
                                                            wire:model.live="invoice_items.{{ $index }}.quantity"
                                                            min="1" step="1"
                                                            class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        @error('invoice_items.' . $index . '.quantity')
                                                            <span
                                                                class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number"
                                                            wire:model.live="invoice_items.{{ $index }}.unit_price"
                                                            min="0" step="0.01"
                                                            class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        @error('invoice_items.' . $index . '.unit_price')
                                                            <span
                                                                class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                                        ₹{{ number_format($item['total'], 2) }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @if (count($invoice_items) > 1)
                                                            <button type="button"
                                                                wire:click="removeInvoiceItem({{ $index }})"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="w-5 h-5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                                                                    7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        @endif
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
                                <h2 class="text-lg font-medium text-gray-900">Additional Information</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment
                                            Terms</label>
                                        <input type="text" wire:model="payment_terms"
                                            placeholder="e.g., Net 30 days"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Terms &
                                            Conditions</label>
                                        <textarea wire:model="terms_conditions" rows="3" placeholder="Enter terms and conditions..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                        <textarea wire:model="notes" rows="3" placeholder="Any additional notes..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
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
                                    Purchase Summary
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- Subtotal -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Subtotal</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">₹{{ number_format($subtotal, 2) }}</span>
                                </div>

                                <!-- Discount -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Discount</span>
                                        <span
                                            class="text-sm font-medium text-gray-900">-₹{{ number_format($discount_amount, 2) }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="number" wire:model.live="discount_percentage"
                                                min="0" max="100" step="0.01" placeholder="%"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                title="Discount Percentage">
                                        </div>
                                        <div>
                                            <input type="number" wire:model.live="discount_amount" min="0"
                                                step="0.01" placeholder="Amount"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                title="Discount Amount">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tax -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Tax
                                            ({{ number_format($tax_percentage, 2) }}%)</span>
                                        <span
                                            class="text-sm font-medium text-gray-900">₹{{ number_format($tax_amount, 2) }}</span>
                                    </div>
                                    <div>
                                        <input type="number" wire:model.live="tax_percentage" min="0"
                                            max="100" step="0.01" placeholder="Tax %"
                                            class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                            title="Tax Percentage">
                                    </div>
                                </div>

                                <!-- Round Off -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Round Off</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">₹{{ number_format($round_off, 2) }}</span>
                                </div>

                                <hr class="border-gray-200">

                                <!-- Total -->
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-medium text-gray-900">Total</span>
                                    <span
                                        class="text-lg font-bold text-gray-900">₹{{ number_format($total, 2) }}</span>
                                </div>

                                <!-- Manual Recalculate Button (for edge cases) -->
                                <div class="pt-2">
                                    <button type="button" wire:click="recalculate"
                                        class="w-full text-xs text-gray-500 hover:text-gray-700 focus:outline-none">
                                        🔄 Recalculate
                                    </button>
                                </div>

                                <div class="pt-4 border-t border-gray-200">
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9
                                                9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-blue-800">
                                                    Purchase Total
                                                </p>
                                                <p class="text-xs text-blue-600">
                                                    Amount payable to supplier
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
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('focus-barcode-input', () => {
            const input = document.getElementById('barcode-input');
            if (input) {
                input.focus();
            }
        });
    });
</script>
