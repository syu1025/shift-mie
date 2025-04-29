<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineWebhookController;
use App\Http\Controllers\LineMessageController;

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

Route::get('/index', function () {
    return view('index');
})->name('index');

Route::get('/messages', [LineMessageController::class, 'index'])->name('messages.index');
