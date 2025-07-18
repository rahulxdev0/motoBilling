<div class="min-h-screen bg-gray-50 py-6">
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
                        <button wire:click="enableEdit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        @else
                        <button wire:click="save"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"/>
                            </svg>
                            Save
                        </button>
                        @endif
                        <a href="{{ route('invoice.view', $invoice->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
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
                                    <input type="text" value="{{ $invoice_number }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $invoice->isCashSale() ? 'Sale Date' : 'Invoice Date' }}
                                    </label>
                                    <input type="date" wire:model.defer="invoice_date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'readonly disabled' }}>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                                    <select wire:model.defer="partie_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'disabled' }}>
                                        @foreach($parties as $partie)
                                            <option value="{{ $partie->id }}">{{ $partie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (!$invoice->isCashSale())
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                    <input type="date" wire:model.defer="due_date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'readonly disabled' }}>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ $invoice->isCashSale() ? 'Sale Items' : 'Invoice Items' }}
                            </h2>
                        </div>
                        <div class="py-6">
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
                                                            wire:model.live.debounce.300ms="search_product"
                                                            onkeyup="filterProducts({{ $index }}, this.value)"
                                                            onkeydown="handleKeyDown({{ $index }}, event)"
                                                            onfocus="showDropdown({{ $index }})"
                                                            onblur="handleBlur({{ $index }})"
                                                            {{ $isEditing ? '' : 'readonly disabled' }}
                                                        >
                                                        <!-- Dropdown -->
                                                        <div 
                                                            id="product-dropdown-{{ $index }}"
                                                            class="product-search-dropdown w-full mt-1 bg-white border border-gray-300 rounded-md max-h-60 overflow-auto hidden"
                                                            style="position: absolute; top: 100%; left: 0; z-index: 9999; min-width: 300px;"
                                                            onmousedown="event.preventDefault()"
                                                        >
                                                            <div id="product-list-{{ $index }}">
                                                                @foreach ($filtered_products as $product)
                                                                    <div 
                                                                        class="product-item px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                                                                        onmousedown="selectProduct({{ $index }}, {{ $product['id'] }}, '{{ addslashes($product['name']) }}')"
                                                                        data-name="{{ strtolower($product['name']) }}"
                                                                        data-code="{{ strtolower($product['item_code']) }}"
                                                                    >
                                                                        <div class="flex justify-between items-center">
                                                                            <div>
                                                                                <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                                                                                <div class="text-sm text-gray-500">
                                                                                    Code: {{ $product['item_code'] }} | 
                                                                                    Stock: {{ $product['stock_quantity'] }} | 
                                                                                    Price: ₹{{ number_format($product['selling_price'], 2) }}
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
                                                        <input type="hidden" wire:model.live="invoice_items.{{ $index }}.product_id" id="product-id-{{ $index }}">
                                                    </div>
                                                    @error('invoice_items.' . $index . '.product_id')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number"
                                                        wire:model.live.debounce.300ms="invoice_items.{{ $index }}.quantity"
                                                        min="1" step="1"
                                                        class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $isEditing ? '' : 'bg-gray-100' }}"
                                                        {{ $isEditing ? '' : 'readonly disabled' }}>
                                                    @error('invoice_items.' . $index . '.quantity')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number"
                                                        wire:model.live.debounce.300ms="invoice_items.{{ $index }}.unit_price"
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
                                @if($isEditing)
                                <div class="mt-4">
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
                                        <input type="text" wire:model.defer="payment_terms"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'readonly disabled' }}>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                    <textarea wire:model.defer="terms_conditions" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'readonly disabled' }}></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea wire:model.defer="notes" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'readonly disabled' }}></textarea>
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
                                        <input type="number" wire:model.defer="discount_percentage"
                                            class="w-full px-4 py-2 text-xs border border-gray-300 rounded {{ $isEditing ? '' : 'bg-gray-100' }}" min="0" max="100" step="0.01" {{ $isEditing ? '' : 'readonly disabled' }}>
                                    </div>
                                    <div>
                                        <input type="number" wire:model.defer="discount_amount"
                                            class="w-full px-4 py-2 text-xs border border-gray-300 rounded {{ $isEditing ? '' : 'bg-gray-100' }}" min="0" step="0.01" {{ $isEditing ? '' : 'readonly disabled' }}>
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
                                    <input type="number" wire:model.defer="tax_percentage"
                                        class="w-full px-4 py-2 text-xs border border-gray-300 rounded {{ $isEditing ? '' : 'bg-gray-100' }}" min="0" max="100" step="0.01" {{ $isEditing ? '' : 'readonly disabled' }}>
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
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Payment Section -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-900">Payment Details</h3>
                                
                                <!-- Payment Method -->
                                <div class="space-y-2">
                                    <label class="block text-sm text-gray-600">Payment Method</label>
                                    <input type="text" wire:model.defer="payment_method"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded {{ $isEditing ? '' : 'bg-gray-100' }}" {{ $isEditing ? '' : 'readonly disabled' }}>
                                </div>

                                <!-- Paid Amount -->
                                <div class="space-y-2">
                                    <label class="block text-sm text-gray-600">Paid Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                        <input type="number" wire:model.defer="paid_amount"
                                            class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded {{ $isEditing ? '' : 'bg-gray-100' }}" min="0" {{ $isEditing ? '' : 'readonly disabled' }}>
                                    </div>
                                </div>

                                <!-- Due Amount Display -->
                                <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                    <span class="text-sm text-gray-600">Due Amount</span>
                                    <span class="text-sm font-medium {{ $balance_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ₹{{ number_format((float)($balance_amount ?: 0), 2) }}
                                    </span>
                                </div>

                                <!-- Payment Status Badge -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Payment Status</span>
                                    @php
                                        $paymentColors = [
                                            'unpaid' => 'bg-red-100 text-red-800',
                                            'partial' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'overdue' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // ...existing JS from create-invoice for product search, dropdown, etc...
    // You can copy the JS block from create-invoice.blade.php here for full feature parity.
</script>
