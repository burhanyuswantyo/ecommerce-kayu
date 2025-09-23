<?php

namespace App\Http\Controllers;

use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Mpdf\Mpdf;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function print(Request $request)
    {
        $transactionItems = TransactionItem::query()->whereIn('id', array_keys($request->query()))->get();

        $pdf = LaravelMpdf::loadView('admin.report.print', compact('transactionItems'), config: [
            'orientation' => 'L',
            'format' => 'A4',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 16,
            'margin_bottom' => 16,
        ]);

        return $pdf->stream('Laporan Penjualan' . now() . '.pdf');
    }
}
