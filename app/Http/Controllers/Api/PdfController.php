<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function Invoice(Request $request)
    {
        $data = $request->data;
        $pdf_name = $data['invoice_no'] . '-' . time() . '.pdf';
        $invoice = PDF::loadView('Pdf.invoice', $data);
        return $invoice->download($pdf_name);
    }
}
