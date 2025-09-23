<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid as ComponentsGrid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split as ComponentsSplit;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Infolists\Components\Actions\MediaAction;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ProductShow extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public Product $product;

    public function productInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->product)
            ->schema([
                Section::make('Detail Produk')
                    ->headerActions([
                        Action::make('editStock')
                            ->visible(in_array(auth()->user()->role, ['admin', 'super_admin']))
                            ->modalWidth(MaxWidth::ExtraSmall)
                            ->color('success')
                            ->label('Edit Stok')
                            ->button()
                            ->mountUsing(function (ComponentContainer $form, Product $record) {
                                $form->fill($record->only('stock'));
                            })
                            ->form([
                                TextInput::make('stock')
                                    ->label('Stok')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->action(function (array $data, Product $record) {
                                $record->update($data);

                                Notification::make()
                                    ->title('Stok berhasil diubah.')
                                    ->success()
                                    ->send();

                                return to_route('admin.product.show', $record);
                            }),
                        Action::make('edit')
                            ->visible(in_array(auth()->user()->role, ['admin', 'super_admin']))
                            ->color('warning')
                            ->mountUsing(function (ComponentContainer $form, Product $record) {
                                $form->fill($record->toArray());
                            })
                            ->form([
                                ComponentsSplit::make([
                                    Group::make([
                                        TextInput::make('name')
                                            ->label('Nama Produk')
                                            ->required(),
                                        ComponentsSplit::make([
                                            Select::make('category_id')
                                                ->label('Kategori')
                                                ->options(Category::pluck('name', 'id')->toArray() + ['other' => 'Lainnya (Tambah baru)'])
                                                ->placeholder('Pilih kategori')
                                                ->reactive()
                                                ->afterStateUpdated(
                                                    fn($state, callable $set) =>
                                                    $state === 'other' ? $set('new_category_name', '') : $set('new_category_name', null)
                                                ),

                                            TextInput::make('new_category_name')
                                                ->label('Kategori Baru')
                                                ->visible(fn($get) => $get('category_id') === 'other')
                                                ->required(fn($get) => $get('category_id') === 'other'),
                                        ]),
                                        ComponentsGrid::make()
                                            ->schema([
                                                TextInput::make('price')
                                                    ->label('Harga')
                                                    ->prefix('Rp')
                                                    ->numeric()
                                                    ->required(),
                                                TextInput::make('stock')
                                                    ->label('Stok')
                                                    ->numeric()
                                                    ->required(),
                                                TextInput::make('weight')
                                                    ->label('Berat')
                                                    ->numeric()
                                                    ->suffix('gram')
                                                    ->required(),
                                                TextInput::make('length')
                                                    ->label('Panjang')
                                                    ->numeric()
                                                    ->suffix('cm')
                                                    ->required(),
                                                TextInput::make('width')
                                                    ->label('Lebar')
                                                    ->numeric()
                                                    ->suffix('cm')
                                                    ->required(),
                                                TextInput::make('height')
                                                    ->label('Tinggi')
                                                    ->numeric()
                                                    ->suffix('cm')
                                                    ->required(),
                                            ])
                                    ]),
                                    Group::make([
                                        Textarea::make('description')
                                            ->label('Deskripsi')
                                            ->rows(3)
                                            ->required(),
                                        FileUpload::make('images')
                                            ->label('Gambar')
                                            ->multiple()
                                            ->image()
                                            ->disk('public')
                                            ->directory('products')
                                            ->minFiles(1)
                                            ->maxFiles(5)
                                            ->maxSize(1024)
                                            ->imagePreviewHeight(100)
                                            ->required(),
                                    ]),
                                ]),
                            ]),
                        Action::make('delete')
                            ->visible(in_array(auth()->user()->role, ['admin', 'super_admin']))
                            ->translateLabel()
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function (Product $record) {
                                $record->delete();

                                Notification::make()
                                    ->title('Produk berhasil dihapus.')
                                    ->success()
                                    ->send();

                                return to_route('admin.product.index');
                            })
                            ->sendSuccessNotification(),
                    ])
                    ->schema([
                        Grid::make(4)->schema([
                            TextEntry::make('name')
                                ->translateLabel()
                                ->weight(FontWeight::SemiBold)
                                ->columnSpan(3),
                            TextEntry::make('category.name')
                                ->translateLabel()
                                ->badge()
                                ->color('gray'),
                            TextEntry::make('description')
                                ->translateLabel()
                                ->columnSpan(4),
                            TextEntry::make('price')
                                ->translateLabel()
                                ->numeric(locale: "id")
                                ->prefix('Rp'),
                            TextEntry::make('weight')
                                ->translateLabel()
                                ->suffix(fn($record) => " $record->weight_unit"),
                            TextEntry::make('dimension')
                                ->translateLabel(),
                            TextEntry::make('stock')
                                ->translateLabel()
                                ->color(fn($state) => $state < 10 ? 'danger' : 'gray'),
                            Section::make()
                                ->schema([
                                    Split::make([

                                        TextEntry::make('productSold')
                                            ->state(fn($record) => $record->transactionItems()->sum('quantity'))
                                            ->translateLabel(),
                                        TextEntry::make('totalSold')
                                            ->state(function ($record) {
                                                $subtotal = 0;
                                                $total = $record->transactionItems()->get()->map(function ($item) use ($subtotal) {
                                                    $subtotal += $item->quantity * $item->price;
                                                    return $subtotal;
                                                });
                                                return $total->sum();
                                            })
                                            ->prefix('Rp')
                                            ->numeric(locale: 'id')
                                            ->translateLabel(),
                                    ])

                                ])

                        ])
                    ])
            ]);
    }


    public function render(): View
    {
        return view('livewire.product.product-show');
    }
}
