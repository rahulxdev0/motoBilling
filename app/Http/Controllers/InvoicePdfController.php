<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;

class InvoicePdfController extends Controller
{
    /**
     * Display the invoice as PDF
     */
    public function view($id)
    {
        $invoice = Invoice::with(['items.product', 'partie'])->findOrFail($id);
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        
        // Return view for preview
        return $pdf->stream('invoice-'.$invoice->invoice_number.'.pdf');
    }
    
    /**
     * Download the invoice as PDF
     */
    public function download($id)
    {
        $invoice = Invoice::with(['items.product', 'partie'])->findOrFail($id);
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        
        // Return download
        return $pdf->download('invoice-'.$invoice->invoice_number.'.pdf');
    }
    
    /**
     * Show the invoice in browser (HTML)
     */
    public function show($id)
    {
        $invoice = Invoice::with(['items.product', 'partie'])->findOrFail($id);
        
        // Return view for browser display
        return view('pdf.invoice', compact('invoice'));
    }
}
