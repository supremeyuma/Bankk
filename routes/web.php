<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\PinController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\WireTransferController;
use App\Http\Controllers\User\AccountController;


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
    return view('user.dashboard');
})->middleware(['auth', 'verified'])->name('user.dashboard');
Route::get('/transactions', [DashboardController::class, 'transactions'])->name('user.transactions');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/transfer', [TransferController::class, 'showTransferForm'])->name('user.transfer.form');
Route::post('/transfer', [TransferController::class, 'handleTransfer'])->name('user.transfer.handle');
Route::get('/wire-transfer', [WireTransferController::class, 'create'])->name('user.transfer.wire');
Route::post('/wire-transfer', [WireTransferController::class, 'store'])->name('user.wire.transfer.store');

Route::get('/api/accounts/{account_number}', [AccountController::class, 'getAccountByNumber']);

Route::get('/accounts/lookup/{accountNumber}', [AccountController::class, 'getAccountByNumber'])
    ->middleware('auth')
    ->name('accounts.lookup');

Route::get('/set-pin', [PinController::class, 'showPinForm'])->middleware('auth');
Route::post('/set-pin', [PinController::class, 'storePin'])->middleware('auth');


Route::get('/admin/set-pin', [App\Http\Controllers\Admin\UserPinController::class, 'showForm'])->name('admin.setPinForm');
Route::post('/admin/set-pin', [App\Http\Controllers\Admin\UserPinController::class, 'setPin'])->name('admin.setPin');


require __DIR__.'/auth.php';
