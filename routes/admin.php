<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::controller(AdminController::class)->prefix('auth/admin')->group( function () {
    Route::post('/login','login');
    Route::post('/register', 'register');
    Route::post('/logout','logout');
    Route::get('/admin-profile', 'adminProfile');
});
