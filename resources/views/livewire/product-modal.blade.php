<x-modal :show="false" focusable name="product-view">
    <div class="p-6" x-data x-show="$store.productModal.selectedProduct">
        <div class="gap-4 grid grid-cols-2">
            <div class="flex flex-col items-center">
                <!-- Gambar besar -->
                <img :src="$store.productModal.selectedProduct.selectedImage || $store.productModal.selectedProduct.images[0]"
                    alt="Gambar Produk Besar"
                    class="mb-4 border border-gray-300 rounded w-full h-64 object-cover transition-all duration-300" />

                <!-- Thumbnail -->
                <div class="flex space-x-2 overflow-x-auto">
                    <template :key="img" x-for="img in $store.productModal.selectedProduct.images">
                        <img :class="{ 'ring-2 ring-brown-500': img === ($store.productModal.selectedProduct.selectedImage || $store
                                .productModal.selectedProduct.images[0]) }"
                            :src="img" @click="$store.productModal.selectedProduct.selectedImage = img"
                            alt="Thumbnail"
                            class="hover:opacity-80 border rounded w-16 h-16 object-cover cursor-pointer" />
                    </template>
                </div>
            </div>


            <div class="flex flex-col items-start">
                <h3 class="font-semibold text-gray-600 text-xl" x-text="$store.productModal.selectedProduct.name">
                </h3>
                <p class="font-semibold text-green-600" x-text="'Rp' + $store.productModal.selectedProduct.price">
                </p>

                <div class="bg-gray-100 mt-2 p-2 border rounded text-sm">
                    <p
                        x-text="'Berat: ' + $store.productModal.selectedProduct.weight + ' ' + $store.productModal.selectedProduct.weight_unit">
                    </p>
                    <p x-text="'Dimensi: ' + $store.productModal.selectedProduct.dimension"></p>
                </div>

                <p class="mt-2 text-sm" x-text="$store.productModal.selectedProduct.description"></p>
                <p class="inline-block bg-brown-100 mt-4 px-3 py-1 rounded-full text-brown-600 text-xs"
                    x-text="$store.productModal.selectedProduct.category"></p>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center bg-brown-50 px-6 py-4">
        <button @click.prevent="$store.productModal.close()" class="text-brown-600 hover:text-brown-500">Tutup</button>
        <button @click.prevent="$wire.addToCart($store.productModal.selectedProduct.id)"
            class="flex items-center gap-1.5 bg-brown-600 px-3 py-2 rounded text-white">
            <div wire:loading wire:target='addToCart'>
                <svg class="size-4 text-white animate-spin" fill="none" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"
                        stroke="currentColor">
                    </circle>
                    <path class="opacity-75"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        fill="currentColor"></path>
                </svg>
            </div>

            Tambahkan ke Keranjang
        </button>
    </div>
</x-modal>
