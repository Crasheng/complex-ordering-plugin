<?php

use Illuminate\Support\Facades\Route;
use Osmanco\ComplexCollection\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Control Panel Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Control Panel routes for your addon.
|
*/

Route::prefix('complex-collection-ordering')->name('complex-collection-ordering.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
    Route::get('/create', [ItemController::class, 'create'])->name('create');
    Route::post('/', [ItemController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ItemController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ItemController::class, 'update'])->name('update');
    Route::delete('/{id}', [ItemController::class, 'destroy'])->name('destroy');

    // API endpoint to get staff categories by main category
    Route::get('/staff-categories/{mainCategory}', [ItemController::class, 'getStaffCategories'])
        ->name('staff-categories');
    Route::post('/update-order', [ItemController::class, 'updateOrder'])->name('update-order');

});
