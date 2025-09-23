<div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
    @foreach ($categories as $category)
        <a href="{{ route('product.index', ['category' => $category->slug]) }}">
            <div class="rounded-xl bg-white p-4 shadow transition duration-300 hover:scale-105">
                <div class="flex flex-col items-center gap-2">
                    @if ($category->image)
                        <img alt="{{ $category->name }}" class="size-16 rounded-xl border object-cover"
                            src="{{ asset('storage/products/example3.jpg') }}">
                    @else
                        <div class="flex size-16 items-center justify-center rounded-xl border bg-brown-200 p-4">
                            <p class="text-xl font-bold text-brown-700">{{ str()->substr($category->name, 0, 1) }}</p>
                        </div>
                    @endif
                    <p class="line-clamp-1 font-semibold">{{ $category->name }}</p>
                </div>
            </div>
        </a>
    @endforeach
</div>
