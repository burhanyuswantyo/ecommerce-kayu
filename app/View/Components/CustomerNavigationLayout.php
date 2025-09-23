<?php

namespace App\View\Components;

use App\Livewire\Actions\Logout;
use Illuminate\View\Component;
use Illuminate\View\View;
use Livewire\Attributes\On;

class CustomerNavigationLayout extends Component
{
    public function render(): View
    {
        return view('livewire.layout.customer-navigation');
    }
}
