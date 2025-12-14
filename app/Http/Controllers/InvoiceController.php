<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download($order_id)
    {
        // Get data order
        $order = Order::with('items.product')->findOrFail($order_id);

        // Authorize 
        if (auth()->id() !== $order->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Load View PDF
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);
        $pdf->setPaper('a4', 'portrait');
        $fileName = 'Invoice-' . $order->code . '.pdf';

        return $pdf->stream($fileName);
    }
}
