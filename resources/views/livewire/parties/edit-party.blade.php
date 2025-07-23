<div class="flex-1 p-6 bg-gray-50 overflow-y-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <!-- Navigation Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('parties.manage') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Parties
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit Party</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Title and Description -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-2">
                <button class="sm:hidden p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    wire:click="$dispatch('toggle-mobile-sidebar')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Party</h1>
                    <p class="text-gray-600 mt-1 text-sm">Update party details</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-4">
        <!-- Edit Button (shows first, beside Save/Cancel) -->
        <button type="button"
            wire:click="$set('editing', true)"
            class="inline-flex items-center px-4 py-2 border border-blue-500 rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition-colors"
            @if($editing) style="display:none" @endif>
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Party
        </button>
        <!-- Save Button (visible when editing) -->
        @if($editing)
        <button type="submit"
            form="edit-party-form"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
            Save
        </button>
        <!-- Cancel Button (visible when editing) -->
        <button type="button"
            wire:click="cancelEdit"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
            Cancel
        </button>
        @endif
        <!-- Toggle Status Button (Livewire action) -->
        <button type="button"
            wire:click="toggleStatus({{ $party->id }})"
            class="{{ $party->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-medium transition-colors">
            @if($party->is_active)
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Deactivate
            @else
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activate
            @endif
        </button>
        <!-- Go Back Button -->
        <a href="{{ route('parties.manage') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Go Back
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Party Information</h3>
            <p class="text-sm text-gray-600">Edit the details for this party</p>
        </div>

        <!-- Form Content -->
        <form wire:submit.prevent="save" id="edit-party-form" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Party Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Party Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name" id="name"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter party name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" wire:model="email" id="email"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter email address">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" wire:model="phone" id="phone"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('phone') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('phone') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter phone number">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Person -->
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Person
                    </label>
                    <input type="text" wire:model="contact_person" id="contact_person"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('contact_person') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('contact_person') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter contact person name">
                    @error('contact_person')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- GSTIN -->
                <div>
                    <label for="gstin" class="block text-sm font-medium text-gray-700 mb-2">
                        GST Number
                    </label>
                    <input type="text" wire:model="gstin" id="gstin"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('gstin') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('gstin') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter GST number">
                    @error('gstin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PAN -->
                <div>
                    <label for="pan" class="block text-sm font-medium text-gray-700 mb-2">
                        PAN Number
                    </label>
                    <input type="text" wire:model="pan" id="pan"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('pan') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('pan') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter PAN number">
                    @error('pan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>
                    <textarea wire:model="address" id="address" rows="3"
                        @if(!$editing) disabled class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('address') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @else class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('address') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        @endif
                        placeholder="Enter complete address"></textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="is_active"
                            @if(!$editing) disabled class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded bg-gray-100 cursor-not-allowed"
                            @else class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            @endif
                        >
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Active Status
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Check this box to make the party active</p>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions (hide default Save/Cancel, handled above) -->
        </form>
        <!-- Delete Button in Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button type="button"
                onclick="if(confirm('Are you sure you want to delete this party? This action cannot be undone.')) { @this.deleteParty({{ $party->id }}) }"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </div>
    </div>

    <!-- Form Guidelines Card -->
    <div class="mt-6 bg-blue-50 rounded-xl shadow-sm border border-blue-200 p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Form Guidelines</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Party Name and Phone Number are required fields</li>
                        <li>Email must be unique across all parties</li>
                        <li>GST Number should be 15 characters long if provided</li>
                        <li>PAN Number should be 10 characters long if provided</li>
                        <li>All parties are active by default</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>
