<div class="space-y-6">
    {{ $this->productInfolist }}

    <x-filament::section>
        <x-slot name="heading">
            {{ __('Images') }}
        </x-slot>

        <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
            @foreach ($product->images as $image)
                <livewire:product.product-image :$image />
            @endforeach
        </div>
    </x-filament::section>
    <livewire:product.product-sold :$product />

    <x-filament-actions::modals />

</div>
