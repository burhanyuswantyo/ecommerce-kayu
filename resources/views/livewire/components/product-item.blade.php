<a class="bg-white shadow rounded-xl hover:scale-105 transition-transform duration-300 transform" href="#"
    x-data=""
    x-on:click="$store.productModal.open({
        id: '{{ $product->id }}',
        name: '{{ $product->name }}',
        description: `{{ str_replace('`', '\\`', $product->description) }}`,
        images: @js(collect($product->images)->map(fn($img) => asset('storage/' . $img))),
        price: '{{ thousand($product->price) }}',
        weight: '{{ $product->weight }}',
        weight_unit: '{{ $product->weight_unit }}',
        dimension: '{{ $product->dimension }}',
        category: '{{ $product->category->name }}'
    })">
    <figure>
        <img alt="product" class="rounded-t-xl w-full h-48 object-cover" src="storage/{{ $product->images[0] }}" />
    </figure>
    <div class="p-4">
        <h2 class="font-bold">{{ $product->name }}</h2>
        <p class="text-sm line-clamp-2">{{ str($product->description)->limit(1000) }}</p>
        <div class="flex flex-wrap justify-between items-center mt-4">
            <p class="bg-brown-100/60 dark:bg-gray-800 px-3 py-1 rounded-full text-brown-500 text-xs">
                {{ str($product->category->name)->limit(20) }}</p>
            </p>
            <p class="font-semibold text-green-600 card-title">{{ 'Rp' . thousand($product->price) }}</p>
        </div>
    </div>
</a>
