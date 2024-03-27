<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news');
});

Route::post('/get-newsData', [NewsController::class, 'fetchNewsData']);
Route::post('/save-user', [RegisterController::class, 'register']);
Route::post('/user-login', [LoginController::class, 'checkLogin']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::delete('/delete-news/{id}', [NewsController::class, 'delete']);

Auth::routes();