<?php

namespace App\Livewire\Customer;

use App\Models\Product;
use Livewire\Component;

class ProductBestSeller extends Component
{
    public function render()
    {
        $products = Product::query()
            ->active()
            ->whereHas('transactionItems')
            ->withSum('transactionItems', 'quantity')
            ->orderByDesc('transaction_items_sum_quantity')
            ->limit(4)
            ->get();

        return view('livewire.customer.product-best-seller', compact('products'));
    }
}
