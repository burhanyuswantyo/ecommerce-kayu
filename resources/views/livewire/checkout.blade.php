<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
  <div class="col-span-2">
    <div class="flex flex-col gap-5">
      <div class="flex flex-col rounded-xl bg-white p-6 shadow">
        <h3 class="mb-6 text-2xl font-semibold text-brown-700">Detail Pembeli</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="block text-sm text-gray-500 dark:text-gray-300" for="name">Nama
              Lengkap</label>
            <input
              class="mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
              id="name" type="text" wire:model="form.name" />
            @error('form.name')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm text-gray-500 dark:text-gray-300" for="phone">Nomor
              Handphone</label>
            <input
              class="mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
              id="phone" type="text" wire:model="form.phone" />
            @error('form.phone')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
      <div class="flex flex-col rounded-xl bg-white p-6 shadow">
        <h3 class="mb-6 text-2xl font-semibold text-brown-700">Detail Pengiriman</h3>
        <div class="flex flex-col gap-6">
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @foreach ($shippingMethods as $key => $shippingMethod)
              @if ($form['shipping_method'] === $key)
                <div class="flex cursor-pointer items-center gap-6 rounded-xl border border-blue-500 px-8 py-4"
                  wire:click="setShippingMethod('{{ $key }}')">
                  <x-heroicon-m-check-circle class="size-7 text-blue-600 dark:text-blue-500" />
                  <h2 class="text-lg font-semibold text-blue-600 dark:text-blue-500">
                    {{ $shippingMethod }}
                  </h2>
                </div>
              @else
                <div class="flex cursor-pointer items-center gap-6 rounded-xl border px-8 py-4"
                  wire:click="setShippingMethod('{{ $key }}')">
                  <x-heroicon-m-check-circle class="size-7 text-gray-400" />
                  <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    {{ $shippingMethod }}
                  </h2>
                </div>
              @endif
            @endforeach
          </div>
          @if ($form['shipping_method'] === 'delivery')
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-300" for="address">Alamat
                Penerima</label>
              <textarea
                class="mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                id="address" placeholder="Masukkan alamat lengkap penerima" rows="3" wire:model="form.address"></textarea>
              @error('form.address')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-300" for="subdistrict_id">Kota</label>
              <livewire:components.input-city />
              @error('form.subdistrict_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
              @enderror
            </div>
          @elseif($form['shipping_method'] === 'self_pickup')
            <div>
              <label class="block text-sm text-gray-500 dark:text-gray-300" for="address">Lokasi
                Pengambilan</label>
              <div class="grid grid-cols-2 gap-4">
                <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                  {{ config('store.store_address') }}
                </p>
                <ol class="list-disc">
                  @foreach (config('store.store_opening_hours') as $key => $openHour)
                    <li class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                      {{ $key }} {{ $openHour }}
                    </li>
                  @endforeach
                </ol>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col rounded-xl bg-white p-6 shadow">
      <h3 class="mb-6 text-2xl font-semibold text-brown-700">Detail Barang</h3>
      <div class="space-y-4">
        <div class="mb-6 flex flex-col gap-2 divide-y divide-dashed">
          @foreach ($carts as $cart)
            <div class="flex flex-row items-start gap-4 pt-2">
              <div class="flex grow gap-2">
                <p class="text-sm font-medium text-gray-800">
                  {{ $cart->quantity }}
                </p>
                <p class="text-sm font-medium text-gray-800">
                  x
                </p>
                <p class="line-clamp-2 text-sm font-medium text-gray-800">
                  {{ $cart->product->name }}
                </p>
              </div>
              <div class="flex flex-col items-end">
                <p class="text-nowrap text-sm text-gray-500">
                  Rp{{ thousand($cart->quantity * $cart->product->price) }}
                </p>
                <p class="text-nowrap text-sm text-gray-500">
                  ({{ $cart->quantity * $cart->product->weight_kg }} kg)
                </p>
              </div>
            </div>
          @endforeach
        </div>
        <hr>
        <div class="flex justify-between gap-4">
          <p class="line-clamp-2 text-sm font-medium text-gray-800">
            Biaya Pengiriman
          </p>
          <p class="text-nowrap text-sm text-gray-500">
            Rp{{ thousand($form['shipping_cost']) }}
          </p>
        </div>
        <div class="flex items-center justify-between">
          <p class="font-medium text-gray-800">Total</p>
          <p class="font-semibold text-gray-800">Rp{{ thousand($total) }}</p>
        </div>
      </div>
    </div>
    <button
      class="focus:outline-hidden w-full items-center gap-x-2 rounded-lg border border-transparent bg-brown-600 px-3 py-2 font-semibold text-white hover:bg-brown-700 focus:bg-brown-700 disabled:pointer-events-none disabled:opacity-50"
      type="button" wire:click="process">
      Proses
    </button>
  </div>
</div>
