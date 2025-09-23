<?php

namespace App\Livewire\Components;

use App\Models\Postal;
use Livewire\Component;

class InputCity extends Component
{
    public string $query = '';
    public array $cities = [];
    public int $id;

    public function updatedQuery()
    {
        if (strlen($this->query) >= 3) {
            $this->cities = Postal::where('name', 'like', '%' . $this->query . '%')
                ->orderBy('name')
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->cities = [];
        }
    }

    public function selectCity($id, $name)
    {
        $this->id = $id;
        $this->query = $name;
        $this->cities = [];

        $this->dispatch('citySelected', $id);
    }

    public function render()
    {
        return view('livewire.components.input-city');
    }
}
