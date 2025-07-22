<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;

class PurchaseInvoicePdfController extends Controller
{
    /**
     * Display the purchase invoice as PDF
     */
    public function view($id)
    {
        $invoice = Invoice::with(['items.product', 'partie'])
            ->where('invoice_category', 'purchase')
            ->findOrFail($id);
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.purchase-invoice', compact('invoice'));
        
        // Return view for preview
        return $pdf->stream('purchase-invoice-'.$invoice->invoice_number.'.pdf');
    }
    
    /**
     * Download the purchase invoice as PDF
     */
    public function download($id)
    {
        $invoice = Invoice::with(['items.product', 'partie'])
            ->where('invoice_category', 'purchase')
            ->findOrFail($id);
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.purchase-invoice', compact('invoice'));
        
        // Return download
        return $pdf->download('purchase-invoice-'.$invoice->invoice_number.'.pdf');
    }
    
    /**
     * Show the purchase invoice in browser (HTML)
     */
    public function show($id)
    {
        $invoice = Invoice::with(['items.product', 'partie'])
            ->where('invoice_category', 'purchase')
            ->findOrFail($id);
        
        // Return view for browser display
        return view('pdf.purchase-invoice', compact('invoice'));
    }
}