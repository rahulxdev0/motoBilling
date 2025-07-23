<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-200">
    <div class="container mx-auto px-4 py-8 sm:py-6 flex flex-col items-center justify-center min-h-screen">
        <!-- Main Card -->
        <div class="w-full max-w-md md:max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="md:flex">
                <!-- Left Side: Brand Section (hidden on mobile) -->
                <div class="hidden md:block md:w-1/2 bg-gradient-to-br from-teal-500 to-emerald-600 p-8 text-white">
                    <div class="h-full flex flex-col">
                        <!-- Logo & Brand -->
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">MotoBilling</h2>
                                <p class="text-xs text-teal-100">Smart Business Solution</p>
                            </div>
                        </div>
                        
                        <!-- Main Content -->
                        <div class="mt-10 flex-1 flex flex-col justify-center">
                            <h1 class="text-3xl font-bold mb-2">Welcome Back!</h1>
                            <p class="text-teal-100 mb-8">Sign in to access your billing dashboard</p>
                            
                            <!-- Feature List -->
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="mt-1 flex-shrink-0 w-5 h-5 bg-teal-400/30 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Streamlined Invoicing</p>
                                        <p class="text-xs text-teal-100/80">Create and manage professional invoices</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="mt-1 flex-shrink-0 w-5 h-5 bg-teal-400/30 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Inventory Management</p>
                                        <p class="text-xs text-teal-100/80">Track products and stock levels</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="mt-1 flex-shrink-0 w-5 h-5 bg-teal-400/30 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Business Analytics</p>
                                        <p class="text-xs text-teal-100/80">Gain insights through detailed reports</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                            </div>
                </div>
                
                <!-- Right Side: Login Form -->
                <div class="w-full md:w-1/2 p-8 md:p-10">
                    <!-- Mobile Logo (only visible on small screens) -->
                    <div class="flex md:hidden items-center justify-center mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h2 class="text-xl font-bold text-gray-800">MotoBilling</h2>
                            <p class="text-xs text-gray-500">Smart Business Solution</p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Sign In</h2>
                        <p class="text-gray-600 mt-1">Access your account to continue</p>
                    </div>
                    
                    <!-- Login Form -->
                    <form wire:submit="login" class="space-y-6">
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input wire:model="email" id="email" type="email" autocomplete="email" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="your@email.com">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <a href="#" class="text-xs font-medium text-teal-600 hover:text-teal-500">Forgot password?</a>
                            </div>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input wire:model="password" id="password" type="password" autocomplete="current-password"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200"
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center">
                            <input wire:model="remember" id="remember" type="checkbox" 
                                class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded transition">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Remember me for 30 days
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="fex justify-center">
                            <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-md hover:shadow-lg transition-all duration-200"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    Sign In
                                </span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Signing in...
                                </span>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Demo Info -->
                    <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Demo Credentials</h3>
                                <div class="mt-1 text-sm text-blue-700 space-y-1">
                                    <p><span class="font-semibold">Email:</span> admin@billing.com</p>
                                    <p><span class="font-semibold">Password:</span> password123</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-4 text-center text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} MotoBilling. All rights reserved.</p>
            <p class="mt-1">Developed by <a href="https://www.comestro.com" class="text-teal-600 hover:text-teal-700 transition-colors">Comestro Techlabs Pvt Ltd</a></p>
        </div>
    </div>
</div>