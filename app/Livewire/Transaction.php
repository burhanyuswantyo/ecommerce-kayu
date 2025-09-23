<?php

namespace App\Livewire;

use App\Models\Transaction as ModelsTransaction;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Transaction extends Component
{
    use WithPagination;

    public $perPage = 5;

    public function render()
    {
        $transactions = auth('customer')->user()->transactions()
            ->with(['transactionItems.product'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.transaction', compact('transactions'));
    }

    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function complete($transactionId)
    {
        $transaction = ModelsTransaction::find($transactionId);
        $transaction->status = 'completed';
        $transaction->save();

        Notification::make()
            ->success()
            ->title('Pesanan berhasil diselesaikan.')
            ->send();
    }

    public function cancel($transactionId)
    {
        $transaction = ModelsTransaction::find($transactionId);
        $transaction->status = 'canceled';
        $transaction->save();

        Notification::make()
            ->success()
            ->title('Pesanan berhasil dibatalkan.')
            ->send();
    }
}
