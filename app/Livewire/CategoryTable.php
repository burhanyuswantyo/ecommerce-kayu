<?php

namespace App\Livewire;

use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CategoryTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Kategori')
            ->query(Category::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Jumlah Produk')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->sortable()
                    ->dateTime()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth(MaxWidth::Small)
                    ->label('Tambah Kategori')
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama kategori'),
                    ])
            ])
            ->actions([
                EditAction::make()
                    ->button()
                    ->color('success')
                    ->size(ActionSize::Small)
                    ->icon(false)
                    ->modalWidth(MaxWidth::Small)
                    ->form([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama kategori'),
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.category-table');
    }
}
