<div x-data="{ selectedProduct: null }">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
        @foreach ($products as $product)
            @livewire('components.product-item', ['product' => $product], key($product->id))
        @endforeach
    </div>
    <x-modal :show="false" focusable name="product-view">
        <div class="p-6" x-show="selectedProduct">
            <div class="grid grid-cols-2 gap-4">
                <img :src="selectedProduct.image" alt="product" class="h-48 w-full object-cover" />

                <div class="flex flex-col">
                    <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-200" x-text="selectedProduct.name">
                    </h3>

                    <p class="card-title font-semibold text-green-600" x-text="'Rp' + selectedProduct.price"></p>

                    <div class="mt-2 flex flex-col rounded-lg border border-gray-200 bg-gray-100 p-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400"
                            x-text="'Berat: ' + selectedProduct.weight + ' ' + selectedProduct.weight_unit"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400"
                            x-text="'Dimensi: ' + selectedProduct.dimension"></p>
                    </div>

                    <p class="mt-2 text-sm" x-text="selectedProduct.description"></p>

                    <div class="mt-4 flex flex-wrap items-center justify-between">
                        <p class="rounded-full bg-brown-100/60 px-3 py-1 text-xs text-brown-500 dark:bg-gray-800"
                            x-text="selectedProduct.category">
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between bg-brown-50 p-6 py-4 text-center dark:bg-gray-700">
            <button
                class="focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-brown-600 px-3 py-2 text-sm font-medium text-brown-600 hover:border-brown-500 hover:text-brown-500 focus:border-brown-500 focus:text-brown-500 disabled:pointer-events-none disabled:opacity-50 dark:border-white dark:text-white dark:hover:border-neutral-300 dark:hover:text-neutral-300"
                type="button" x-data=""
                x-on:click.prevent="
                    selectedProduct = null;
                    $dispatch('close-modal', 'product-view');
                ">
                Tutup
            </button>
            <button
                class="focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-brown-600 px-3 py-2 text-sm font-medium text-white hover:bg-brown-700 focus:bg-brown-700 disabled:pointer-events-none disabled:opacity-50"
                type="button" wire:click="addToCart(selectedProduct.id)">
                <x-heroicon-o-shopping-cart class="size-5" />
                Tambahkan ke Keranjang
            </button>
        </div>
    </x-modal>
</div>
