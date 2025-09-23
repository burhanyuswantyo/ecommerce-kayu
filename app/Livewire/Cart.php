<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class Cart extends Component
{
    public function render()
    {
        $carts = auth('customer')->user()->carts()->with('product')->get();
        $total = $carts->pluck('product')->pluck('price')->sum();

        return view('livewire.cart', compact('carts', 'total'));
    }

    public function remove($productId)
    {
        $cart = auth('customer')->user()->carts()->where('product_id', $productId);

        if ($cart) {
            $cart->delete();
            $this->dispatch('cart-updated');
        }
    }


    public function increment($productId)
    {
        $cart = auth('customer')->user()->carts()->where('product_id', $productId)->first();

        if ($cart) {
            $cart->increment('quantity');
        }
    }

    public function decrement($productId)
    {
        $cart = auth('customer')->user()->carts()->where('product_id', $productId)->first();

        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete();
                $this->dispatch('cart-updated');
            }
        }
    }
}
