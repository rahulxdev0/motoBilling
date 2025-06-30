<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;

class CategoryModal extends Component
{
    public $show = false;
    
    #[Validate('required|string|max:255')]
    public $categoryName = '';
    
    #[Validate('nullable|string|max:500')]
    public $description = '';

    protected $listeners = [
        'open-category-modal' => 'openModal',
        'close-category-modal' => 'closeModal'
    ];

    /**
     * Custom validation rules with database uniqueness check
     */
    protected function rules()
    {
        return [
            'categoryName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->categoryId ?? null),
            ],
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages()
    {
        return [
            'categoryName.required' => 'Category name is required.',
            'categoryName.max' => 'Category name must not exceed 255 characters.',
            'categoryName.unique' => 'This category name already exists.',
            'description.max' => 'Description must not exceed 500 characters.',
        ];
    }

    /**
     * Open the modal and reset form
     */
    public function openModal()
    {
        $this->show = true;
        $this->categoryName = '';
        $this->description = '';
        $this->resetValidation();
    }

    /**
     * Close the modal and reset form
     */
    public function closeModal()
    {
        $this->show = false;
        $this->categoryName = '';
        $this->description = '';
        $this->resetValidation();
    }

    /**
     * Add new category to database
     */
    public function addCategory()
    {
        // Validate the form data
        $this->validate();

        try {
            // Create new category in database
            $category = Category::create([
                'name' => trim($this->categoryName),
                'description' => trim($this->description) ?: null,
            ]);

            // Emit success event to parent component
            $this->dispatch('category-added', [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
            ]);

            $this->dispatch('category-created', [
                'id' => $category->id,
                'name' => $category->name,
            ]);

            // Show success message
            session()->flash('success', 'Category "' . $category->name . '" created successfully!');

            // Close modal
            $this->closeModal();

        } catch (\Exception $e) {
            // Handle any database errors
            $this->addError('categoryName', 'Failed to create category. Please try again.');
            
            // Log the error for debugging
            \Log::error('Category creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Real-time validation for category name
     */
    public function updatedCategoryName()
    {
        $this->validateOnly('categoryName');
    }

    /**
     * Real-time validation for description
     */
    public function updatedDescription()
    {
        $this->validateOnly('description');
    }

    public function render()
    {
        return view('livewire.components.category-modal');
    }
}
