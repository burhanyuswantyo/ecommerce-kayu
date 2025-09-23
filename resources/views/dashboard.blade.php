<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-gray-800 text-xl leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="space-y-6 mx-auto sm:px-6 lg:px-8 max-w-7xl">
            <livewire:widgets.stats-overview>
                <div class="gap-6 grid grid-cols-2">
                    <livewire:widgets.most-sales-table>
                        <livewire:widgets.low-stock-product-table>
                </div>
                <livewire:widgets.sales-chart>
        </div>
    </div>
</x-app-layout>
