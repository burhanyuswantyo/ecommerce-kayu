<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return view('customer.transaction.index');
    }

    public function invoice(string $invoiceNumber)
    {
        $transaction = Transaction::whereInvoiceNumber($invoiceNumber)
            ->with('transactionItems', 'customer')
            ->first();

        return view('customer.transaction.invoice', compact('transaction'));
    }
}
