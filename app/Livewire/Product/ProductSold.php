<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\TransactionItem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ProductSold extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Product $product;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Produk Terjual')
            ->query(TransactionItem::query()
                ->whereProductId($this->product->id)
                ->whereHas('transaction', fn($query) => $query->where('status', '!=', 'waiting_for_payment')))
            ->columns([
                TextColumn::make('transaction.invoice_number')
                    ->label('Invoice')
                    ->weight(FontWeight::SemiBold)
                    ->translateLabel(),
                // TextColumn::make('quantity')
                //     ->translateLabel(),
                TextColumn::make('price')
                    ->prefix('Rp')
                    ->numeric(locale: 'id')
                    ->translateLabel(),
                // TextColumn::make('subtotal')
                //     ->formatStateUsing(fn($record) => $record->quantity * $record->price)
                //     ->prefix('Rp')
                //     ->numeric(locale: 'id')
                //     ->translateLabel(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->summarize([
                        Sum::make(),
                    ]),
                TextColumn::make('subtotal')
                    ->prefix('Rp')
                    ->numeric(locale: 'id')
                    ->summarize([
                        Sum::make()
                            ->prefix('Rp'),
                    ])
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
        return view('livewire.product.product-sold');
    }
}
