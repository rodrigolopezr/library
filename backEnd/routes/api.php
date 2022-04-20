<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;

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


Route::controller(BooksController::class)->group(function () {
    Route::get('/books', 'get');
    Route::post('/books/new', 'new');
    Route::post('/books/update', 'update');
    Route::delete('/books/delete', 'delete');
    Route::get('/users', 'getUsers');
    Route::get('/categories', 'getCategories');
});
