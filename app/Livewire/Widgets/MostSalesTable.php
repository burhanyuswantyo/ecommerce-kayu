<?php

namespace App\Livewire\Widgets;

use App\Models\Product;
use App\Models\TransactionItem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class MostSalesTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Penjualan Terbanyak')
            ->paginated(false)
            ->query(Product::query()
                ->whereHas('transactionItems')
                ->withSum('transactionItems', 'quantity')
                ->orderByDesc('transaction_items_sum_quantity')
                ->take(5))
            ->columns([
                TextColumn::make('name')
                    ->weight(FontWeight::Medium)
                    ->translateLabel(),
                TextColumn::make('transaction_items_sum_quantity')
                    ->label('Terjual')
                    ->sum('transactionItems', 'quantity'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.widgets.most-sales-table');
    }
}
