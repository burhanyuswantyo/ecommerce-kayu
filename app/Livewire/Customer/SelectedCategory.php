<?php

namespace App\Livewire\Customer;

use App\Models\Category;
use Livewire\Component;

class SelectedCategory extends Component
{
    public function render()
    {
        $categories = Category::whereHas('products')->inRandomOrder()->take(5)->get();

        return view('livewire.customer.selected-category', compact('categories'));
    }
}
