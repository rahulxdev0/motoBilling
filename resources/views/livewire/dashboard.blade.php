<div class="flex-1 p-6 bg-gray-50 overflow-y-auto md:rounded-md">
    <!-- Header with welcome message and refresh button -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <button class="sm:hidden p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    wire:click="$dispatch('toggle-mobile-sidebar')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">Business Dashboard</h1>
                    <p class="text-gray-600 mt-1">{{ now()->format('l, F j, Y') }} • Overview of your business performance</p>
                </div>
            </div>
            <button wire:click="refreshData"
                class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md flex items-center space-x-2 hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a wire:navigate href="{{ route('invoice.create') }}"
                class="bg-white border border-gray-200 p-4 rounded-lg hover:border-teal-300 hover:bg-teal-50 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">New Invoice</h3>
                        <p class="text-sm text-gray-500">Create invoice</p>
                    </div>
                </div>
            </a>

            <a wire:navigate href="{{ route('parties.create') }}"
                class="bg-white border border-gray-200 p-4 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Add Customer</h3>
                        <p class="text-sm text-gray-500">New party</p>
                    </div>
                </div>
            </a>

            <a wire:navigate href="{{ route('items.create') }}"
                class="bg-white border border-gray-200 p-4 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Add Product</h3>
                        <p class="text-sm text-gray-500">New item</p>
                    </div>
                </div>
            </a>

            <a wire:navigate href="{{ route('items.manage') }}"
                class="bg-white border border-gray-200 p-4 rounded-lg hover:border-amber-300 hover:bg-amber-50 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Inventory</h3>
                        <p class="text-sm text-gray-500">Manage stock</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Business Overview - Key Metrics Cards -->
    <div class="mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Business Overview</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-semibold text-gray-900 mt-2">₹{{ number_format($totalRevenue, 0) }}</p>
                        <div class="flex items-center mt-3">
                            <span class="text-sm {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center font-medium">
                                @if($revenueGrowth >= 0)
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                @endif
                                {{ abs($revenueGrowth) }}% from last month
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Revenue -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-semibold text-gray-900 mt-2">₹{{ number_format($monthlyRevenue, 0) }}</p>
                        <p class="text-sm text-gray-500 mt-3">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Invoices</p>
                        <p class="text-2xl font-semibold text-gray-900 mt-2">{{ number_format($totalInvoices) }}</p>
                        <p class="text-sm text-amber-600 mt-3">{{ $pendingInvoices }} pending payments</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center ml-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Customers</p>
                        <p class="text-2xl font-semibold text-gray-900 mt-2">{{ number_format($totalCustomers) }}</p>
                        <p class="text-sm text-gray-500 mt-3">Active parties</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center ml-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Recent Activity (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Invoices -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Recent Invoices</h2>
                            <p class="text-gray-500 text-sm mt-1">Latest sales transactions</p>
                        </div>
                        <a wire:navigate href="{{ route('invoice.manage') }}"
                            class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse ($recentInvoices as $invoice)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $invoice['id'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $invoice['customer'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">₹{{ number_format($invoice['amount'], 2) }}</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-md
                                        {{ $invoice['status'] === 'paid'
                                            ? 'bg-green-100 text-green-800'
                                            : ($invoice['status'] === 'partial'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($invoice['status']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">No recent invoices</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Top Selling Products</h2>
                        <p class="text-gray-500 text-sm mt-1">Best performing items this month</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse ($topProducts as $index => $product)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-600 font-medium text-sm">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product['name'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $product['quantity'] }} units sold</p>
                                    </div>
                                </div>
                                <span class="font-medium text-gray-900">₹{{ number_format($product['revenue'], 0) }}</span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-gray-500">No product sales data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Alerts & Notifications (1/3 width) -->
        <div class="space-y-8">
            <!-- Low Stock Alert -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-4 border-b border-gray-200 bg-red-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-medium text-red-900">Low Stock Alert</h2>
                            <p class="text-red-700 text-sm">{{ count($lowStockItems) }} items need attention</p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @forelse ($lowStockItems as $item)
                            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-md">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                    <p class="text-sm text-red-600">
                                        Only {{ $item['stock_quantity'] }} {{ $item['unit'] }} left
                                    </p>
                                </div>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    Min: {{ $item['reorder_level'] }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-green-600 font-medium text-sm">All items well stocked!</p>
                            </div>
                        @endforelse
                    </div>
                    @if(count($lowStockItems) > 0)
                        <div class="mt-4">
                            <a href="{{ route('items.manage') }}" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-md transition-colors text-center block font-medium text-sm">
                                Manage Inventory
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Quick Stats</h2>
                    <p class="text-gray-500 text-sm mt-1">Business overview</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700">Paid Invoices</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $totalInvoices - $pendingInvoices }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-yellow-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700">Pending Payments</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $pendingInvoices }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700">Low Stock Items</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ count($lowStockItems) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700">Total Customers</span>
                </div>
                <span class="font-medium text-gray-900">{{ $totalCustomers }}</span>
            </div>
        </div>
    </div>

   
            <!-- Low Stock Alert -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-4 border-b border-gray-200 bg-red-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-medium text-red-900">Low Stock Alert</h2>
                            <p class="text-red-700 text-sm">{{ count($lowStockItems) }} items need attention</p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @forelse ($lowStockItems as $item)
                            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-md">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                    <p class="text-sm text-red-600">
                                        Only {{ $item['stock_quantity'] }} {{ $item['unit'] }} left
                                    </p>
                                </div>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    Min: {{ $item['reorder_level'] }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-green-600 font-medium text-sm">All items well stocked!</p>
                            </div>
                        @endforelse
                    </div>
                    @if(count($lowStockItems) > 0)
                        <div class="mt-4">
                            <a href="{{ route('items.manage') }}" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-md transition-colors text-center block font-medium text-sm">
                                Manage Inventory
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Quick Stats</h2>
                    <p class="text-gray-500 text-sm mt-1">Business overview</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700">Paid Invoices</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $totalInvoices - $pendingInvoices }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-yellow-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700">Pending Payments</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $pendingInvoices }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-700">Low Stock Items</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ count($lowStockItems) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700">Total Customers</span>
                </div>
                <span class="font-medium text-gray-900">{{ $totalCustomers }}</span>
            </div>
        </div>
    </div>

    <!-- Business Health Score -->
    <div class="bg-teal-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Business Health</h2>
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
        <div class="text-2xl font-semibold mb-2">
            @php
                $healthScore = min(100, max(0, 
                    ($totalRevenue > 0 ? 30 : 0) + 
                    ($pendingInvoices < $totalInvoices * 0.3 ? 30 : 0) + 
                    (count($lowStockItems) < 5 ? 25 : 0) + 
                    ($totalCustomers > 10 ? 15 : 0)
                ));
            @endphp
            {{ $healthScore }}%
        </div>
        <p class="text-teal-100 text-sm">
            @if($healthScore >= 80)
                Excellent performance across all metrics.
            @elseif($healthScore >= 60)
                Good performance with room for improvement.
            @elseif($healthScore >= 40)
                Fair performance. Focus on payment collection.
            @else
                Needs attention in sales and inventory management.
            @endif
        </p>
    </div>
</div>
