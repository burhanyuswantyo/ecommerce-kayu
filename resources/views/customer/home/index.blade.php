<x-customer-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6">
                @livewire('customer.product-best-seller')
                <div>
                    <h3 class="mb-2 text-xl font-semibold">Kategori Pilihan</h3>
                    @livewire('customer.selected-category')
                </div>
                <div>
                    <h3 class="mb-2 text-xl font-semibold">Semua Produk</h3>
                    @livewire('customer.list-products')
                </div>
            </div>

        </div>
    </div>
    <livewire:product-modal />
</x-customer-layout>
