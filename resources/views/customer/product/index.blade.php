<x-customer-layout>
    <div class="py-6">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl">
            <div>
                <h3 class="mb-6 font-semibold text-2xl">
                    @if (request('search'))
                        Pencarian untuk "{{ request('search') }}"
                    @elseif(request('category'))
                        Produk Kategori "{{ $category->name }}"
                    @else
                        Semua Produk
                    @endif
                </h3>
                @livewire('customer.list-products', ['search' => $search, 'category' => $category])
            </div>
        </div>
    </div>
    <livewire:product-modal />
</x-customer-layout>
