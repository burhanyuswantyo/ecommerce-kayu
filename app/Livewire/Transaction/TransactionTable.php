<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TransactionTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Data Transaksi')
            ->query(Transaction::query())
            ->columns([
                TextColumn::make('invoice_number')
                    ->color('info')
                    ->url(fn($record) => route('admin.transaction.invoice', $record->invoice_number), true)
                    ->label('Invoice')
                    ->description(fn($record) => $record->created_at->format('d M Y H:i'))
                    ->weight(FontWeight::SemiBold)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->wrap()
                    ->label('Customer')
                    ->description(fn($record) => $record->phone)
                    ->weight(FontWeight::SemiBold)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('shipping_method')
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => __(str($state)->headline()->toString()))
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'delivery' => 'success',
                        'self_pickup' => 'info',
                    }),
                TextColumn::make('grand_total')
                    ->weight(FontWeight::SemiBold)
                    ->label('Total')
                    ->numeric(locale: 'id')
                    ->prefix('Rp')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->formatStateUsing(function ($record) {
                        if ($record->status === 'waiting_for_payment' && $record->payment_status === 'waiting_confirmation') {
                            return __('Waiting Confirmation');
                        }
                        return __(str($record->status)->headline()->toString());
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'waiting_for_payment' => 'gray',
                        'processed' => 'info',
                        'on_delivery' => 'warning',
                        'ready_pickup' => 'warning',
                        'completed' => 'success',
                        'canceled' => 'danger',
                        default => 'gray'
                    }),
                TextColumn::make('paid_at')
                    ->translateLabel()
                    ->dateTime('d M Y H:i')
                    ->icon('heroicon-o-document-check')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('success')
                    ->action(MediaAction::make()
                        ->media(fn($record) => asset("storage/$record->payment_evidence"))
                        ->modalHeading('Bukti Pembayaran'))
                    ->placeholder('-'),

            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'waiting_for_payment' => 'Menunggu Pembayaran',
                        'processed' => 'Diproses',
                        'on_delivery' => 'Dalam Pengiriman',
                        'ready_pickup' => 'Siap Diambil',
                        'completed' => 'Selesai',
                        'canceled' => 'Dibatalkan',
                    ]),
                SelectFilter::make('shipping_method')
                    ->multiple()
                    ->options([
                        'delivery' => 'Dikirim ke alamat',
                        'self_pickup' => 'Ambil sendiri',
                    ]),
            ])
            ->actions([
                Action::make('process')
                    ->translateLabel()
                    ->size(ActionSize::Small)
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(Transaction $record) => $record->update(['status' => 'processed', 'payment_status' => 'paid']))
                    ->visible(fn($record) => $record->status === 'waiting_for_payment' && $record->payment_status === 'waiting_confirmation'),
                Action::make('confirmDelivery')
                    ->modalWidth(MaxWidth::Small)
                    ->size(ActionSize::Small)
                    ->translateLabel()
                    ->button()
                    ->color('success')
                    ->form([
                        Select::make('courier')
                            ->options([
                                'JNE' => 'JNE',
                                'J&T' => 'J&T',
                                'Sicepat' => 'Sicepat',
                                'Lion Parcel' => 'Lion Parcel',
                            ])
                            ->required(),
                        TextInput::make('receipt_number')
                            ->required(),
                    ])
                    ->action(function (array $data, Transaction $record): void {
                        $data['status'] = 'on_delivery';
                        $record->update($data);
                    })
                    ->visible(fn($record) => $record->status === 'processed' && $record->shipping_method === 'delivery'),
                Action::make('confirmPickup')
                    ->label('Siap Diambil')
                    ->modalWidth(MaxWidth::Small)
                    ->size(ActionSize::Small)
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (array $data, Transaction $record): void {
                        $data['status'] = 'ready_pickup';
                        $record->update($data);
                    })
                    ->visible(fn($record) => $record->status === 'processed' && $record->shipping_method === 'self_pickup'),
                ViewAction::make()
                    ->icon(false)
                    ->size(ActionSize::Small)
                    ->button()
                    ->color('info')
                    ->infolist([
                        Grid::make(3)->schema([
                            TextEntry::make('invoice_number')
                                ->color('info')
                                ->url(fn($record) => route('admin.transaction.invoice', $record->invoice_number), true)
                                ->translateLabel()
                                ->columnSpan(2)
                                ->weight(FontWeight::SemiBold),

                            TextEntry::make('status')
                                ->formatStateUsing(function ($record) {
                                    if ($record->status === 'waiting_for_payment' && $record->payment_status === 'waiting_confirmation') {
                                        return __('Waiting Confirmation');
                                    }
                                    return __(str($record->status)->headline()->toString());
                                })
                                ->badge()
                                ->color(fn(string $state): string => match ($state) {
                                    'waiting_for_payment' => 'gray',
                                    'processed' => 'info',
                                    'on_delivery' => 'warning',
                                    'ready_pickup' => 'warning',
                                    'completed' => 'success',
                                    'canceled' => 'danger',
                                    default => 'gray'
                                }),
                        ]),
                        Split::make([
                            TextEntry::make('name')
                                ->translateLabel(),
                            TextEntry::make('phone')
                                ->translateLabel(),
                            TextEntry::make('shipping_method')
                                ->translateLabel()
                                ->formatStateUsing(fn($state) => __(str($state)->headline()->toString()))
                                ->badge()
                                ->color(fn(string $state): string => match ($state) {
                                    'delivery' => 'success',
                                    'self_pickup' => 'info',
                                }),
                            TextEntry::make('receipt_number')
                                ->translateLabel()
                                ->formatStateUsing(fn($record) => "$record->courier - $record->receipt_number")
                                ->visible(fn($record) => $record->receipt_number)

                        ]),
                        TextEntry::make('address')
                            ->translateLabel()
                            ->visible(fn($record) => $record->shipping_method === 'delivery'),
                        Split::make([
                            TextEntry::make('total_price')
                                ->translateLabel()
                                ->prefix('Rp')
                                ->numeric(locale: 'id'),
                            TextEntry::make('shipping_cost')
                                ->translateLabel()
                                ->prefix('Rp')
                                ->numeric(locale: 'id'),
                            TextEntry::make('grand_total')
                                ->translateLabel()
                                ->prefix('Rp')
                                ->weight(FontWeight::SemiBold)
                                ->numeric(locale: 'id'),
                        ]),
                        RepeatableEntry::make('transactionItems')
                            ->label('Items')
                            ->schema([
                                Split::make([
                                    ImageEntry::make('product.images.0')
                                        ->grow(false)
                                        ->size(48)
                                        ->label(false),
                                    TextEntry::make('product_name')
                                        ->label(false),
                                    TextEntry::make('price')
                                        ->hint(fn($record) => "$record->quantity x")
                                        ->prefix('Rp')
                                        ->label(false)
                                        ->numeric(locale: 'id'),
                                    TextEntry::make('subtotal')
                                        ->weight(FontWeight::SemiBold)
                                        ->prefix('Rp')
                                        ->formatStateUsing(fn($record) => $record->quantity * $record->price)
                                        ->numeric(locale: 'id'),
                                ])

                            ])
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.transaction.transaction-table');
    }
}
