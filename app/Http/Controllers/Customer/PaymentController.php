<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(string $invoiceNumber)
    {
        $transaction = Transaction::whereInvoiceNumber($invoiceNumber)->first();

        return view('customer.payment.index', compact('transaction'));
    }

    public function confirm(Request $request, $transaction)
    {
        $request->validate([
            'payment_evidence' => ['required', 'image', 'max:2048']
        ]);

        $transaction = Transaction::whereInvoiceNumber($transaction)->first();

        $transaction->update([
            'payment_evidence' => $request->file('payment_evidence')->store('Payment Evidence', 'public'),
            'payment_status' => 'waiting_confirmation',
            'paid_at' => now()
        ]);

        return to_route('transaction.index');
    }
}
