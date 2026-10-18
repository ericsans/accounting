<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\JournalEntriesController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounts')->group(function () {
    Route::controller(AccountsController::class)->group(function () {
        Route::get('/ledger', 'ledger')->name('account.ledger');
    });
});

Route::prefix('journal-entries')->group(function () {
    Route::controller(JournalEntriesController::class)->group(function () {
        Route::post('/', 'store')->name('journalEntry.store');
        Route::put('/{journalEntry}', 'update')->name('journalEntry.update');
        Route::delete('/{journalEntry}', 'destroy')->name('journalEntry.destroy');
    });
});
