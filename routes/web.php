<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AddressController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::post('address/lookup', [AddressController::class, 'lookup'])
        ->name('address.lookup');

    Route::resource('address', AddressController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});

Route::resource('address', AddressController::class)
  ->except(['create', 'store', 'edit', 'update', 'destroy']);

Route::name('address.restore')
    ->put(
        'address/{address}/restore',
        [AddressController::class, 'restore']
    )->withTrashed();

Route::get('login', [AuthController::class, 'create'])
  ->name('login');
Route::post('login', [AuthController::class, 'store'])
  ->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])
  ->name('logout');

Route::resource('user-account', UserAccountController::class)
  ->only(['create', 'store']);