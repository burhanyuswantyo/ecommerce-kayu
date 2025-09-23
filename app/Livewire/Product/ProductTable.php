<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split as LayoutSplit;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;

class ProductTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Produk')
            ->query(Product::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('images.0')
                    ->label('Gambar')
                    ->square(),
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->weight(FontWeight::SemiBold)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->tooltip(fn($record) => $record->category->name)
                    ->limit(10)
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->prefix('Rp')
                    ->sortable()
                    ->numeric(locale: 'id')
                    ->searchable(),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->weight(FontWeight::SemiBold)
                    ->color(fn(string $state): string => $state < 10 ? 'danger' : 'gray')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('transaction_items_count')
                    ->label('Sold')
                    ->translateLabel()
                    ->counts(['transactionItems' => function ($query) {
                        $query
                            ->whereHas('transaction', fn($query) => $query->whereNotIn('status', ['waiting_for_payment']));
                    }])
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Aktif?')
                    ->onColor('success'),
                TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->sortable()
                    ->dateTime('d M Y H:i')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')

            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Produk')
                    ->icon('heroicon-m-plus')
                    ->form([
                        Split::make([
                            Group::make([
                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required(),
                                // Select::make('category_id')
                                //     ->label('Kategori Produk')
                                //     ->relationship('category', 'name')
                                //     ->required(),
                                Split::make([
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

                                Grid::make()
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
                                    ->required(),
                            ]),
                        ]),
                    ])
                    ->using(function (string $model, array $data) {
                        if ($data['category_id'] === 'other') {
                            $category = Category::create(['name' => $data['new_category_name']]);
                            unset($data['new_category_name']);
                            $data['category_id'] = $category->id;
                        }

                        return $model::create($data);
                    })
            ])
            ->actions([
                EditAction::make('editStock')
                    ->modalWidth(MaxWidth::ExtraSmall)
                    ->color('success')
                    ->label('Edit Stok')
                    ->button()
                    ->size(ActionSize::ExtraSmall)
                    ->icon('heroicon-m-calculator')
                    ->form([
                        TextInput::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->required(),
                    ])
                    ->visible(in_array(auth()->user()->role, ['admin', 'super_admin'])),
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info')
                        ->label('Detail')
                        ->url(fn($record) => route('admin.product.show', $record)),
                    EditAction::make()
                        ->color('warning')
                        ->form([
                            Split::make([
                                Group::make([
                                    TextInput::make('name')
                                        ->label('Nama Produk')
                                        ->required(),
                                    Split::make([
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
                                    Grid::make()
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
                        ])
                        ->visible(in_array(auth()->user()->role, ['admin', 'super_admin'])),
                    DeleteAction::make()
                        ->visible(in_array(auth()->user()->role, ['admin', 'super_admin']))
                ])
                    ->color('info')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.product.product-table');
    }
}
