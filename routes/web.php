<?php

use App\Livewire\Dashboard;
use App\Livewire\Items\ManageItems;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "homepage";
});

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/items/manage', ManageItems::class)->name('items.manage');
