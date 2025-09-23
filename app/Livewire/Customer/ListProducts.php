<?php

namespace App\Livewire\Customer;

use App\Models\Product;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class ListProducts extends Component
{
    use WithPagination;

    public $category;

    public $search;
    public $selectedCategories = [];
    public $sortBy = 'created_at|desc';

    public $sortOptions = [
        'created_at|desc' => 'Terbaru',
        'created_at|asc' => 'Terlama',
        'price|asc' => 'Harga Terendah',
        'price|desc' => 'Harga Tertinggi',
    ];

    public $perPage = 12;


    public function render()
    {
        $products = Product::query()
            ->active()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        $categories = $products->get()->pluck('category')->unique();

        $products =  $products
            ->when($this->selectedCategories ?? false, function ($query) {
                $filter = array_keys(array_filter($this->selectedCategories));
                count($filter) > 0 ? $query->whereIn('category_id', $filter) : $query;
            })
            ->when($this->sortBy, function ($query) {
                [$field, $direction] = explode('|', $this->sortBy);
                $query->orderBy($field, $direction);
            })->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.customer.list-products', compact('products', 'categories'));
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function addToCart($productId)
    {
        if (auth('customer')->check()) {
            // Cek apakah produk sudah ada di keranjang
            $cart = auth('customer')->user()->carts()->where('product_id', $productId)->first();
            if ($cart) {
                // Jika produk sudah ada, tingkatkan jumlahnya
                $cart->increment('quantity');
            } else {
                // Jika produk belum ada, tambahkan ke keranjang
                auth('customer')->user()->carts()->create([
                    'product_id' => $productId,
                    'quantity' => 1,
                ]);
            }

            Notification::make()
                ->title('Produk berhasil ditambahkan ke keranjang')
                ->success()
                ->send();

            $this->dispatch('close-modal', 'product-view');
            $this->dispatch('cart-updated');
        } else {
            $this->dispatch('close-modal', 'product-view');
            $this->dispatch('open-modal', 'login-form');
        }
    }
}
