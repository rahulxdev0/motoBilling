<div class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col h-full rounded-md">
    <!-- Header/Logo Section -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">MotoBilling</h1>
                <p class="text-sm text-gray-500">Billing System</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a wire:navigate href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-gray-700 bg-teal-50 border-r-4 border-teal-500 rounded-l-lg hover:bg-teal-100 transition-colors duration-200">
            <svg class="w-5 h-5 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v14l-5-3-5 3V5z"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Customers -->
        <a wire:navigate href="{{ route('parties.manage') }}"
            class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                </path>
            </svg>
            <span>Parties</span>
        </a>

        <!-- Items (Expandable) -->
        <div class="space-y-1">
            <!-- Items Main Button -->
            <button wire:click="toggleItems"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Items</span>
                </div>
                <!-- Chevron Icon -->
                <svg class="w-4 h-4 transition-transform duration-200 {{ $isItemsOpen ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Items Submenu -->
            @if ($isItemsOpen)
                <div class="ml-4 space-y-1 transition-all ease-in-out">
                    <!-- Inventory -->
                    <a wire:navigate href="{{ route('items.manage') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        <span>Inventory</span>
                    </a>

                    <!-- Warehouse -->
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Warehouse</span>
                    </a>
                </div>
            @endif
        </div>

        <div class="space-y-1">
            <!-- Items Main Button -->
            <button wire:click="toggleSales"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Sales</span>
                </div>
                <!-- Chevron Icon -->
                <svg class="w-4 h-4 transition-transform duration-200 {{ $isSalesOpen ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Items Submenu -->
            @if ($isSalesOpen)
                <div class="ml-4 space-y-1 transition-all ease-in-out">
                    <!-- sales Invoices -->
                    <a wire:navigate href="{{ route('invoice.manage') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Sales Invoices</span>
                    </a>

                    <!-- Warehouse -->
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Report</span>
                    </a>
                </div>
            @endif
        </div>

        <div class="space-y-1">
            <!-- Items Main Button -->
            <button wire:click="togglePurchase"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Purchases</span>
                </div>
                <!-- Chevron Icon -->
                <svg class="w-4 h-4 transition-transform duration-200 {{ $isPurchaseOpen ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Items Submenu -->
            @if ($isPurchaseOpen)
                <div class="ml-4 space-y-1 transition-all ease-in-out">
                    <!-- sales Invoices -->
                    <a wire:navigate href="{{ route('invoice.purchase') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Purchase Invoices</span>
                    </a>

                    <!-- Warehouse -->
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Report</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Payments -->
        <a href="#"
            class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <span>Payments</span>
        </a>

        <!-- Products/Services -->
        <a href="#"
            class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <span>Products</span>
        </a>

        <!-- Reports -->
        <a href="#"
            class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
            <span>Reports</span>
        </a>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-4"></div>

        <!-- Settings -->
        <a href="#"
            class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors duration-200">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Settings</span>
        </a>
    </nav>

    <!-- Footer/User Section -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">U</span>
            </div>
            <div class="flex-1 min-w-0">
                @if ($user)
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                @else
                    <p class="text-sm font-medium text-gray-700 truncate">Guest User</p>
                    <p class="text-xs text-gray-500 truncate">Not logged in</p>
                @endif
            </div>
            <button wire:click="logout"
                class="group curso flex items-center justify-center p-2.5 rounded-full bg-transparent hover:bg-red-500 transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75">
                <svg class="w-5 h-5 text-red-400 group-hover:text-white group-hover:scale-105 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-label="Logout">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
            </button>
        </div>
    </div>
</div>
