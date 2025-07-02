<?php

use App\Livewire\Dashboard;
use App\Livewire\Invoice\CreateInvoice;
use App\Livewire\Invoice\ManageInvoice;
use App\Livewire\Items\ManageItems;
use App\Livewire\Login;
use App\Livewire\Parties\CreatePatie;
use App\Livewire\Parties\ManageParties;
use App\Livewire\Purchase\CreatePurchaseInvoice;
use App\Livewire\Purchase\PurchaseInvoice;
use Illuminate\Support\Facades\Route;

Route::get('/', Login::class)->name('login');

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/items/manage', ManageItems::class)->name('items.manage');
Route::get('/parties', ManageParties::class)->name('parties.manage');
Route::get('/parties/create', CreatePatie::class)->name('parties.create');
Route::get('/invoice/manage', ManageInvoice::class)->name('invoice.manage');
Route::get('/invoice/create', CreateInvoice::class)->name('invoice.create');
Route::get('/invoice/purchase', PurchaseInvoice::class)->name('invoice.purchase');
Route::get('/invoice/purchase/create', CreatePurchaseInvoice::class)->name('invoice.purchase.create');
