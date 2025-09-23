<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class EmployeeTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Pegawai')
            ->query(Employee::query())
            ->columns([
                TextColumn::make('name')
                    ->wrap()
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('identity_number')
                    ->label('NIK')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->wrap()
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->formatStateUsing(fn($state) => __(str($state)->headline()->toString()))
                    ->translateLabel()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->onColor('success')
                    ->translateLabel()
                    ->label('Aktif?'),

            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Tambah Karyawan')
                    ->form([
                        Grid::make()
                            ->schema([

                                TextInput::make('name')
                                    ->translateLabel()
                                    ->required(),
                                TextInput::make('phone')
                                    ->numeric()
                                    ->minLength(10)
                                    ->maxLength(12)
                                    ->prefix('+62')
                                    ->translateLabel(),
                                TextInput::make('email')
                                    ->email()
                                    ->translateLabel(),
                                TextInput::make('identity_number')
                                    ->label('NIK')
                                    ->numeric()
                                    ->minLength(16)
                                    ->maxLength(16)
                                    ->translateLabel(),
                                TextInput::make('position')
                                    ->translateLabel(),
                                Textarea::make('address')
                                    ->translateLabel(),
                                Radio::make('gender')
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->translateLabel()
                                    ->options([
                                        'male' => 'Laki - laki',
                                        'female' => 'Perempuan'
                                    ])
                                    ->required(),
                                ToggleButtons::make('is_active')
                                    ->label('Aktif?')
                                    ->boolean()
                                    ->grouped()
                                    ->default(true)
                                    ->required()
                            ])
                    ])
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Grid::make()
                            ->schema([
                                TextInput::make('name')
                                    ->translateLabel()
                                    ->required(),
                                TextInput::make('phone')
                                    ->numeric()
                                    ->minLength(10)
                                    ->maxLength(12)
                                    ->prefix('+62')
                                    ->translateLabel(),
                                TextInput::make('email')
                                    ->email()
                                    ->translateLabel(),
                                TextInput::make('identity_number')
                                    ->label('NIK')
                                    ->numeric()
                                    ->minLength(16)
                                    ->maxLength(16)
                                    ->translateLabel(),
                                TextInput::make('position')
                                    ->translateLabel(),
                                Textarea::make('address')
                                    ->translateLabel(),
                                Radio::make('gender')
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->translateLabel()
                                    ->options([
                                        'male' => 'Laki - laki',
                                        'female' => 'Perempuan'
                                    ])
                                    ->required(),
                                ToggleButtons::make('is_active')
                                    ->label('Aktif?')
                                    ->boolean()
                                    ->grouped()
                                    ->default(true)
                                    ->required()
                            ])
                    ])
                    ->button()
                    ->color('success')
                    ->icon(false)
                    ->size(ActionSize::Small),
                DeleteAction::make()
                    ->button()
                    ->icon(false)
                    ->size(ActionSize::Small)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.employee.employee-table');
    }
}
