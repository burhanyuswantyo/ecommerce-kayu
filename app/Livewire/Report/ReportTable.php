<?php

namespace App\Livewire\Report;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Split;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelPdf\Enums\Orientation;

use function Spatie\LaravelPdf\Support\pdf;

class ReportTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Laporan Penjualan')
            ->query(TransactionItem::query()
                ->whereHas('transaction', fn($query) => $query->completed()))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('product_name')
                    ->wrap()
                    ->weight(FontWeight::Medium)
                    ->label('Produk')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('price')
                    ->prefix('Rp')
                    ->numeric(locale: 'id')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('subtotal')
                    ->prefix('Rp')
                    ->weight(FontWeight::SemiBold)
                    ->numeric(locale: 'id')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('transaction.invoice_number')
                    ->label('Ref')
                    ->weight(FontWeight::SemiBold)
                    ->translateLabel()
                    ->sortable(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make('product_name')
                    ->label('Produk')
                    ->options(TransactionItem::query()->whereHas('transaction', fn($query) => $query->completed())->pluck('product_name', 'product_name'))
                    ->multiple(),
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('product.category', 'name')
                    ->multiple(),
                Filter::make('created_at')
                    ->columnSpan(2)
                    ->form([
                        Split::make([
                            DatePicker::make('created_from')
                                ->label('Tanggal Awal'),
                            DatePicker::make('created_until')
                                ->label('Tanggal Akhir'),
                        ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->headerActions([
                Action::make('print')
                    ->color('success')
                    ->icon('heroicon-m-printer')
                    ->url(function (HasTable $livewire) {
                        $transactionItems = $livewire->getTableQueryForExport()->get();
                        return route('admin.report.print', $transactionItems);
                    }, true)
                // ->url(fn(HasTable $livewire) => route('admin.report.print', $livewire->query()), true)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.report.report-table');
    }
}
