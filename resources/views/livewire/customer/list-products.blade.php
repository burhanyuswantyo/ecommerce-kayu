<div x-data="{ selectedProduct: null }">
    <div class="grid grid-cols-4 gap-6">
        <div class="col-span-3 flex flex-col gap-4">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($products as $product)
                    @livewire('components.product-item', ['product' => $product], key($product->id))
                @endforeach
            </div>

            @if ($products->hasMorePages())
                <div x-data="{
                    observe() {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    @this.call('loadMore');
                                }
                            });
                        }, { threshold: 1 });
                        observer.observe(this.$el);
                    }
                }" x-init="observe">
                    <p class="text-center font-medium text-gray-500">Menampilkan lebih banyak produk...</p>
                </div>
            @endif
        </div>
        <div>
            <div class="rounded-xl bg-white p-4 shadow">
                <h5 class="mb-4 text-xl font-semibold">Filter</h5>
                <div class="flex flex-col gap-4">
                    <div>
                        <p class="font-medium text-gray-500">Urutkan</p>
                        <select class="mt-2 w-full rounded-lg border-gray-200 p-2 text-sm" wire:model.live="sortBy">
                            @foreach ($sortOptions as $key => $option)
                                <option value="{{ $key }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    @unless ($category)
                        <div>
                            <p class="mb-1 font-medium text-gray-500">Kategori</p>
                            <div class="ms-2 flex flex-col gap-1.5">
                                @foreach ($categories as $key => $category)
                                    <div class="flex">
                                        <input
                                            class="mt-0.5 shrink-0 rounded border-gray-200 text-brown-600 checked:border-brown-500 focus:ring-brown-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:border-brown-500 dark:checked:bg-brown-500 dark:focus:ring-offset-gray-800"
                                            id="category-{{ $category->id }}" type="checkbox" value="{{ $category->id }}"
                                            wire:model.live="selectedCategories.{{ $category->id }}">
                                        <label class="ms-2 text-sm text-gray-500 dark:text-neutral-400"
                                            for="category-{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</div>
