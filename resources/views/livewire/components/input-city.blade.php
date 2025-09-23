<div class="relative">
    <input autocomplete="off"
        class="mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
        id="name" placeholder="Masukkan kota" type="text" wire:model.live.debounce.300ms="query" />

    @if (!empty($cities))
        <ul
            class="absolute z-10 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
            @foreach ($cities as $city)
                <li class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                    wire:click="selectCity('{{ $city['id'] }}','{{ $city['name'] }}')">
                    {{ $city['name'] }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
