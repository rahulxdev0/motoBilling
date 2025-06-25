<?php

use App\Livewire\Dashboard;
use App\Livewire\Invoice\CreateInvoice;
use App\Livewire\Invoice\ManageInvoice;
use App\Livewire\Items\ManageItems;
use App\Livewire\Parties\CreatePatie;
use App\Livewire\Parties\ManageParties;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "homepage";
});

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/items/manage', ManageItems::class)->name('items.manage');
Route::get('/parties', ManageParties::class)->name('parties.manage');
Route::get('/parties/create', CreatePatie::class)->name('parties.create');
Route::get('/invoice/manage', ManageInvoice::class)->name('invoice.manage');
Route::get('/invoice/create', CreateInvoice::class)->name('invoice.create');
