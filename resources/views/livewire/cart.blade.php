<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
  <div class="{{ $carts->isEmpty() ? 'col-span-3' : 'col-span-2' }}">
    <div class="flex flex-col rounded-xl bg-white p-6 shadow">
      <h3 class="mb-6 text-2xl font-semibold text-brown-700">Keranjang Saya</h3>
      <div class="flex flex-col gap-6 divide-y">
        @forelse ($carts as $cart)
          <div class="flex items-start gap-4 pt-6">
            <img alt="" class="size-16 rounded-xl border" src="storage/{{ $cart->product->images[0] }}">
            <div class="flex w-full flex-col gap-2">
              <div class="flex justify-between gap-4">
                <p class="font-mediu line-clamp-2 text-gray-800">
                  {{ $cart->product->name }}</p>
                <p class="text-nowrap font-bold text-gray-500">Rp. {{ thousand($cart->product->price) }}</p>
              </div>
              <div class="flex items-center justify-end gap-2">
                <button class="text-gray-400 hover:text-gray-500" type="button"
                  wire:click='remove({{ $cart->product_id }})'>
                  <x-heroicon-o-trash class="size-5" />
                </button>
                <div
                  class="flex items-center rounded-lg border border-gray-200 bg-white focus:ring-brown-500 active:ring-brown-500">
                  <button class="h-8 rounded-s-lg px-2" type="button" wire:click='decrement({{ $cart->product_id }})'>
                    <x-heroicon-m-minus class="size-4" />
                  </button>
                  <input class="block h-8 w-8 border-0 p-1 text-center focus:ring-0 active:ring-0" type="text"
                    value="{{ $cart->quantity }}">
                  <button class="h-8 rounded-e-lg px-2" type="button" wire:click='increment({{ $cart->product_id }})'>
                    <x-heroicon-m-plus class="size-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        @empty
          <p class="mx-auto text-gray-500">Keranjang Anda kosong.</p>
        @endforelse
      </div>
    </div>
  </div>
  @unless ($carts->isEmpty())
    <div>
      <div class="flex flex-col rounded-xl bg-white p-6 shadow">
        <h3 class="mb-6 text-2xl font-semibold text-brown-700">Ringkasan Belanja</h3>
        <div class="mb-6 flex flex-col gap-2 divide-y divide-dashed">
          @foreach ($carts as $cart)
            <div class="flex justify-between gap-4 pt-2">
              <p class="line-clamp-2 text-sm font-medium text-gray-800">
                {{ $cart->product->name }}
              </p>
              <p class="text-nowrap text-sm text-gray-500">{{ $cart->quantity }} x
                {{ thousand($cart->product->price) }}
              </p>
            </div>
          @endforeach
        </div>
        <div class="mb-4 flex items-center justify-between">
          <p class="font-medium text-gray-800">Total</p>
          <p class="font-semibold text-gray-800">Rp{{ thousand($total) }}</p>
        </div>
        <button @click="window.location.href='{{ route('checkout.index') }}'"
          class="focus:outline-hidden w-full items-center gap-x-2 rounded-lg border border-transparent bg-brown-600 px-3 py-2 font-semibold text-white hover:bg-brown-700 focus:bg-brown-700 disabled:pointer-events-none disabled:opacity-50"
          type="button">
          Checkout
        </button>
      </div>
    </div>
  @endunless
</div>
