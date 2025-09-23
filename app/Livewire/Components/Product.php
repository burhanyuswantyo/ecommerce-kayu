<?php

namespace App\Livewire\Components;

use Filament\Notifications\Notification;
use Livewire\Component;

class Product extends Component
{
    public $products;

    public function render()
    {
        return view('livewire.components.product');
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
