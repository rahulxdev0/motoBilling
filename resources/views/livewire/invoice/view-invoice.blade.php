<div class="min-h-screen bg-gray-50 py-6">
    <div class="px-4 sm:px-6 lg:px-6">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl text-gray-900">
                            {{ $invoice->isCashSale() ? 'Cash Sale' : 'View Sales Invoice' }}
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $invoice->isCashSale() ? 'Immediate cash sale transaction details' : 'Sales invoice details for your customer' }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('invoice.edit', $invoice->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form class="space-y-8">
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
                                    <input type="text" value="{{ $invoice->invoice_number }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $invoice->isCashSale() ? 'Sale Date' : 'Invoice Date' }}
                                    </label>
                                    <input type="date" value="{{ $invoice->invoice_date->format('Y-m-d') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                                    <input type="text" value="{{ $invoice->partie->name }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                </div>
                                @if (!$invoice->isCashSale())
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                    <input type="date" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
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
                                                    <li>Payment received immediately</li>
                                                    <li>No due date required</li>
                                                    <li>Stock updated automatically</li>
                                                    <li>Receipt generated for customer</li>
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
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($invoice->items as $item)
                                            <tr>
                                                <td class="px-2 py-4">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $item->product->name ?? '-' }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            Code: {{ $item->product->item_code ?? '-' }} | 
                                                            Price: ₹{{ number_format($item->unit_price, 2) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number" value="{{ $item->quantity }}"
                                                        class="w-20 px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number" value="{{ $item->unit_price }}"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                                    ₹{{ number_format($item->total, 2) }}
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
                                @if (!$invoice->isCashSale())
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                                        <input type="text" value="{{ $invoice->payment_terms }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>{{ $invoice->terms_conditions }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly disabled>{{ $invoice->notes }}</textarea>
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
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($invoice->subtotal ?: 0), 2) }}</span>
                            </div>

                            <!-- Discount -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Discount</span>
                                    <span class="text-sm font-medium text-gray-900">-₹{{ number_format((float)($invoice->discount_amount ?: 0), 2) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <input type="number" value="{{ $invoice->discount_percentage }}"
                                            class="w-full px-4 py-2 text-xs border border-gray-300 rounded bg-gray-100" readonly disabled>
                                    </div>
                                    <div>
                                        <input type="number" value="{{ $invoice->discount_amount }}"
                                            class="w-full px-4 py-2 text-xs border border-gray-300 rounded bg-gray-100" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Tax -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Tax
                                        ({{ number_format((float)($invoice->tax_percentage ?: 0), 2) }}%)</span>
                                    <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($invoice->tax_amount ?: 0), 2) }}</span>
                                </div>
                                <div>
                                    <input type="number" value="{{ $invoice->tax_percentage }}"
                                        class="w-full px-4 py-2 text-xs border border-gray-300 rounded bg-gray-100" readonly disabled>
                                </div>
                            </div>

                            <!-- Round Off -->
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Round Off</span>
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format((float)($invoice->round_off ?: 0), 2) }}</span>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Total -->
                            <div class="flex justify-between items-center">
                                <span class="text-base font-medium text-gray-900">Total</span>
                                <div class="flex items-center">
                                    <span class="text-lg font-bold text-gray-900">₹{{ number_format((float)($invoice->total ?: 0), 2) }}</span>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Payment Section -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-medium text-gray-900">Payment Details</h3>
                                
                                <!-- Payment Method -->
                                <div class="space-y-2">
                                    <label class="block text-sm text-gray-600">Payment Method</label>
                                    <input type="text" value="{{ ucfirst($invoice->payment_method) }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded bg-gray-100" readonly disabled>
                                </div>

                                <!-- Paid Amount -->
                                <div class="space-y-2">
                                    <label class="block text-sm text-gray-600">Paid Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                        <input type="number" value="{{ $invoice->paid_amount }}"
                                            class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded bg-gray-100" readonly disabled>
                                    </div>
                                </div>

                                <!-- Due Amount Display -->
                                <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                    <span class="text-sm text-gray-600">Due Amount</span>
                                    <span class="text-sm font-medium {{ $invoice->balance_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ₹{{ number_format((float)($invoice->balance_amount ?: 0), 2) }}
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
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$invoice->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($invoice->payment_status) }}
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
