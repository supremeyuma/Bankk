<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/transfer', [TransferController::class, 'showTransferForm'])->name('transfer.form');
Route::post('/transfer', [TransferController::class, 'handleTransfer'])->name('transfer.handle');


Route::get('/api/accounts/{account_number}', [\App\Http\Controllers\AccountController::class, 'getAccountByNumber']);

Route::get('/accounts/lookup/{accountNumber}', [\App\Http\Controllers\AccountController::class, 'getAccountByNumber'])
    ->middleware('auth')
    ->name('accounts.lookup');

Route::get('/set-pin', [UserController::class, 'showPinForm'])->middleware('auth');
Route::post('/set-pin', [UserController::class, 'storePin'])->middleware('auth');


Route::get('/admin/set-pin', [App\Http\Controllers\Admin\UserPinController::class, 'showForm'])->name('admin.setPinForm');
Route::post('/admin/set-pin', [App\Http\Controllers\Admin\UserPinController::class, 'setPin'])->name('admin.setPin');


require __DIR__.'/auth.php';
