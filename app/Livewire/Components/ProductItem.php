<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ProductItem extends Component
{
    public $product;

    public function render()
    {
        return view('livewire.components.product-item');
    }
}
