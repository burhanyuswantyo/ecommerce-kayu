<?php

namespace App\Livewire\Widgets;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Menunggu Konfirmasi', Transaction::wherePaymentStatus('waiting_confirmation')->count()),
            Stat::make('Total Transaksi', Transaction::count()),
            Stat::make('Total Produk', Product::count()),
            Stat::make('Total Pelanggan', Customer::count()),
        ];
    }
}
