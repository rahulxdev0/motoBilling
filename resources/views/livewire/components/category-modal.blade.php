<div>
    @if($show)
    <!-- Backdrop with smooth animation -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-in fade-in duration-200" 
         wire:click="closeModal">
        
        <!-- Modal Container -->
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 w-full max-w-md transform transition-all animate-in slide-in-from-bottom-4 duration-300" 
             wire:click.stop>
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <!-- Category Icon -->
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Add Category</h3>
                        <p class="text-sm text-gray-500">Create a new product category</p>
                    </div>
                </div>
                
                <!-- Close Button -->
                <button wire:click="closeModal" 
                        class="flex-shrink-0 w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form wire:submit="addCategory" class="space-y-6">
                    <!-- Category Name Field -->
                    <div class="space-y-2">
                        <label for="categoryName" class="block text-sm font-medium text-gray-900">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                wire:model.live.debounce.300ms="categoryName" 
                                id="categoryName"
                                placeholder="e.g., Motor Oils, Brake Parts..."
                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200 @error('categoryName') border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-500/20 @enderror">
                            
                            <!-- Input Icon -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                        </div>
                        
                        @error('categoryName')
                            <div class="flex items-center space-x-2 text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Description Field (Optional) -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-900">
                            Description <span class="text-gray-400">(Optional)</span>
                        </label>
                        <div class="relative">
                            <textarea 
                                wire:model.live.debounce.500ms="description" 
                                id="description"
                                rows="3"
                                placeholder="Brief description of this category..."
                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all duration-200 resize-none @error('description') border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-500/20 @enderror"></textarea>
                            
                            <!-- Character Counter -->
                            <div class="absolute bottom-2 right-2 text-xs text-gray-400">
                                {{ strlen($description) }}/500
                            </div>
                        </div>
                        
                        @error('description')
                            <div class="flex items-center space-x-2 text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <button type="button" 
                            wire:click="closeModal"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20">
                            Cancel
                        </button>
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="addCategory"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="flex items-center space-x-2">
                                <!-- Loading Spinner -->
                                <svg wire:loading wire:target="addCategory" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                
                                <!-- Add Icon -->
                                <svg wire:loading.remove wire:target="addCategory" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                
                                <span wire:loading.remove wire:target="addCategory">Create Category</span>
                                <span wire:loading wire:target="addCategory">Creating...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

