<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
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

class CustomerTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Data Pelanggan')
            ->query(Customer::query())
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn($record) => "@$record->username")
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('address')
                    ->wrap()
                    ->placeholder('-')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('city.name')
                    ->placeholder('-')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('transactions_count')
                    ->counts('transactions')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->translateLabel()
                    ->sortable(),
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
        return view('livewire.customer.customer-table');
    }
}
