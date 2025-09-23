<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Infolists\Components\Actions\MediaAction;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ProductImage extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public string $image;

    public function imageInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state(['path' => $this->image])
            ->statePath('path')
            ->schema([
                ImageEntry::make('')
                    ->alignCenter()
                    ->action(
                        MediaAction::make('Gambar')
                            ->media(asset($this->image))
                    ),
            ]);
    }


    public function render(): View
    {
        return view('livewire.product.product-image');
    }
}
