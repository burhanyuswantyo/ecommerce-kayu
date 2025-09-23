<div>
    @if ($products->isNotEmpty())
        <h3 class="mb-2 text-xl font-semibold">Produk Terlaris</h3>
        <div x-data="{ selectedProduct: null }">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                @foreach ($products as $product)
                    @livewire('components.product-item', ['product' => $product], key($product->id))
                @endforeach
            </div>
        </div>
    @endif
</div>
