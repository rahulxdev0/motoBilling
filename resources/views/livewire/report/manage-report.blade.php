<div>
    <div class="px-4 sm:px-6 lg:px-6 py-6">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="text-2xl font-semibold text-gray-700 mb-1">Reports & Analytics</h1>
            <p class="text-gray-600">Comprehensive business insights and financial reports</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2 ">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <label class="text-sm font-medium text-gray-700">Date Range:</label>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="date" wire:model.live="dateFrom"
                        class="border border-gray-300 rounded-md px-6 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-500">to</span>
                    <input type="date" wire:model.live="dateTo"
                        class="border border-gray-300 rounded-md px-6 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button wire:click="$refresh"
                    class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-md focus:ring-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto">
                    @php
                        $tabs = [
                            'balance-sheet' => ['name' => 'Balance Sheet', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                            'profit-loss' => ['name' => 'P&L Report', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                            'sales-summary' => ['name' => 'Sales Summary', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                            'purchase-summary' => ['name' => 'Purchase Summary', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13h10M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z'],
                            'stock-summary' => ['name' => 'Stock Summary', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            'low-stock' => ['name' => 'Low Stock', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z'],
                            'rate-list' => ['name' => 'Rate List', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            'daybook' => ['name' => 'Daybook', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253']
                        ];
                    @endphp

                    @foreach($tabs as $key => $tab)
                        <button wire:click="setActiveTab('{{ $key }}')"
                            class="flex items-center px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors {{ $activeTab === $key ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $tab['icon'] }}"></path>
                            </svg>
                            {{ $tab['name'] }}
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <div class="space-y-6">
            <!-- Balance Sheet -->
            @if($activeTab === 'balance-sheet')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Balance Sheet Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-600">Total Sales</p>
                                    <p class="text-2xl font-bold text-green-900">
                                        ₹{{ number_format($balanceSheet['sales_total'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-green-200 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-6 border border-red-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-600">Total Purchases</p>
                                    <p class="text-2xl font-bold text-red-900">
                                        ₹{{ number_format($balanceSheet['purchase_total'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-red-200 rounded-full">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-600">Stock Value</p>
                                    <p class="text-2xl font-bold text-blue-900">
                                        ₹{{ number_format($balanceSheet['stock_value'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-blue-200 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-600">Accounts Receivable</p>
                                    <p class="text-2xl font-bold text-yellow-900">
                                        ₹{{ number_format($balanceSheet['receivables'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-yellow-200 rounded-full">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-purple-600">Accounts Payable</p>
                                    <p class="text-2xl font-bold text-purple-900">
                                        ₹{{ number_format($balanceSheet['payables'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-purple-200 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-6 border border-indigo-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-indigo-600">Net Worth</p>
                                    <p class="text-2xl font-bold text-indigo-900">
                                        ₹{{ number_format($balanceSheet['net_worth'], 2) }}</p>
                                </div>
                                <div class="p-3 bg-indigo-200 rounded-full">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profit & Loss Report -->
            @if($activeTab === 'profit-loss')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Profit & Loss Statement</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                                <h3 class="text-sm font-medium text-green-600 mb-2">Total Revenue</h3>
                                <p class="text-3xl font-bold text-green-900">₹{{ number_format($profitLoss['revenue'], 2) }}
                                </p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                                <h3 class="text-sm font-medium text-red-600 mb-2">Cost of Goods Sold</h3>
                                <p class="text-3xl font-bold text-red-900">₹{{ number_format($profitLoss['cogs'], 2) }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <h3 class="text-sm font-medium text-blue-600 mb-2">Gross Profit</h3>
                                <p class="text-3xl font-bold text-blue-900">
                                    ₹{{ number_format($profitLoss['gross_profit'], 2) }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                                <h3 class="text-sm font-medium text-purple-600 mb-2">Profit Margin</h3>
                                <p class="text-3xl font-bold text-purple-900">
                                    {{ number_format($profitLoss['gross_profit_margin'], 1) }}%</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Net Profit</h3>
                                <p
                                    class="text-4xl font-bold {{ $profitLoss['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ₹{{ number_format($profitLoss['net_profit'], 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Sales Summary -->
            @if($activeTab === 'sales-summary')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Sales Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <div class="text-center">
                            <div
                                class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $salesSummary->total_invoices ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Total Invoices</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($salesSummary->total_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Total Amount</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-yellow-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($salesSummary->paid_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Paid Amount</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-red-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($salesSummary->pending_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Pending Amount</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-purple-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($salesSummary->avg_invoice_value ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Avg Invoice Value</p>
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Selling Products</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity Sold</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Revenue</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Unit Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($topSellingProducts as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $product->total_sold }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₹{{ number_format($product->total_revenue, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₹{{ number_format($product->selling_price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No sales
                                                data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Purchase Summary -->
            @if($activeTab === 'purchase-summary')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Purchase Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <div class="text-center">
                            <div
                                class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $purchaseSummary->total_invoices ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Total Invoices</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-red-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($purchaseSummary->total_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Total Amount</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($purchaseSummary->paid_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Paid Amount</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-yellow-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($purchaseSummary->pending_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Pending Amount</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="bg-purple-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                ₹{{ number_format($purchaseSummary->avg_invoice_value ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Avg Invoice Value</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stock Summary -->
            @if($activeTab === 'stock-summary')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Stock Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-600">Total Products</p>
                                    <p class="text-3xl font-bold text-blue-900">{{ $stockSummary->total_products ?? 0 }}</p>
                                </div>
                                <div class="p-3 bg-blue-200 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-600">Total Quantity</p>
                                    <p class="text-3xl font-bold text-green-900">{{ $stockSummary->total_quantity ?? 0 }}
                                    </p>
                                </div>
                                <div class="p-3 bg-green-200 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-purple-600">Stock Value</p>
                                    <p class="text-3xl font-bold text-purple-900">
                                        ₹{{ number_format($stockSummary->stock_value ?? 0, 2) }}</p>
                                </div>
                                <div class="p-3 bg-purple-200 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-600">Low Stock Items</p>
                                    <p class="text-3xl font-bold text-red-900">{{ $stockSummary->low_stock_items ?? 0 }}</p>
                                </div>
                                <div class="p-3 bg-red-200 rounded-full">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Low Stock Items -->
            @if($activeTab === 'low-stock')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Low Stock Alert</h2>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            {{ $lowStockItems->count() }} items need attention
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Current Stock</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Reorder Level</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($lowStockItems as $item)
                                    <tr class="{{ $item->stock_quantity == 0 ? 'bg-red-50' : 'bg-yellow-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->item_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->stock_quantity }} {{ $item->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->reorder_level }} {{ $item->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($item->stock_quantity == 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Out of Stock
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Low Stock
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center py-8">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                All products are well stocked!
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Rate List -->
            @if($activeTab === 'rate-list')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Product Rate List</h2>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export PDF
                        </button>
                    </div>
                    @php
                        $products = \App\Models\Product::with('category')->where('status', 'active')->get();
                    @endphp
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purchase Price</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Selling Price</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        MRP</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Profit Margin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->item_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₹{{ number_format($product->purchase_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₹{{ number_format($product->selling_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₹{{ number_format($product->mrp ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $margin = $product->purchase_price > 0 ? (($product->selling_price - $product->purchase_price) / $product->purchase_price) * 100 : 0;
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $margin > 20 ? 'bg-green-100 text-green-800' : ($margin > 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ number_format($margin, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Daybook -->
            @if($activeTab === 'daybook')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Daybook Entries</h2>
                        <div class="text-sm text-gray-500">
                            Showing entries from {{ Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to
                            {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice No.</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Party</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($daybookEntries as $entry)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Carbon\Carbon::parse($entry->invoice_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $entry->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $entry->partie->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->invoice_category === 'sales' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($entry->invoice_category) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₹{{ number_format($entry->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'unpaid' => 'bg-red-100 text-red-800',
                                                    'partial' => 'bg-yellow-100 text-yellow-800',
                                                    'overdue' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$entry->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($entry->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center py-8">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                No entries found for the selected date range
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>