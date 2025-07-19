<?php

use App\Livewire\Dashboard;
use App\Livewire\Invoice\CreateInvoice;
use App\Livewire\Invoice\ManageInvoice;
use App\Livewire\Invoice\ViewInvoice;
use App\Livewire\Items\CreateProduct;
use App\Livewire\Items\ManageItems;
use App\Livewire\Items\EditProduct;
use App\Livewire\Login;
use App\Livewire\Parties\CreatePatie;
use App\Livewire\Parties\ManageParties;
use App\Livewire\Parties\EditParty;
use App\Livewire\Purchase\CreatePurchaseInvoice;
use App\Livewire\Purchase\PurchaseInvoice;
use App\Livewire\Report\ManageReport;
use Illuminate\Support\Facades\Route;
use App\Livewire\Invoice\EditInvoice;
use App\Livewire\Setting\Settings;

Route::get('/', Login::class)->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/items/manage', ManageItems::class)->name('items.manage');
    Route::get('/items/create', CreateProduct::class)->name('items.create');
    Route::get('/items/{item}/edit', EditProduct::class)->name('items.edit');
    Route::get('/parties', ManageParties::class)->name('parties.manage');
    Route::get('/parties/create', CreatePatie::class)->name('parties.create');
    Route::get('/parties/{party}/edit', EditParty::class)->name('parties.edit');
    Route::get('/invoice/manage', ManageInvoice::class)->name('invoice.manage');
    Route::get('/invoice/create', CreateInvoice::class)->name('invoice.create');
    Route::get('/invoice/purchase', PurchaseInvoice::class)->name('invoice.purchase');
    Route::get('/invoice/purchase/create', CreatePurchaseInvoice::class)->name('invoice.purchase.create');
    Route::get('/report', ManageReport::class)->name('report.view');
    Route::get('/setting', Settings::class)->name('setting');

    // Invoice PDF routes
    Route::get('/invoice/{id}/pdf', [App\Http\Controllers\InvoicePdfController::class, 'view'])->name('invoice.pdf.view');
    Route::get('/invoice/{id}/download', [App\Http\Controllers\InvoicePdfController::class, 'download'])->name('invoice.pdf.download');
    Route::get('/invoice/{id}/show', [App\Http\Controllers\InvoicePdfController::class, 'show'])->name('invoice.pdf.show');

    // Add this route for viewing invoice details
    Route::get('/invoice/{invoice}/view', ViewInvoice::class)->name('invoice.view');

    // Add this route for editing invoice (fix: use fully qualified class name)
    Route::get('/invoice/{invoice}/edit', EditInvoice::class)->name('invoice.edit');
});