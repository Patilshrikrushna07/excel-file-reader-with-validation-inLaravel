<?php

use App\Http\Controllers\DropdownController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("upload", [FileController::class, 'upload']);

Route::post('/import_user', [UserController::class, 'import_user']);



Route::get('/doctors', [FileController::class, 'list_user']);

// Route::get('/gettoken', [DropdownController::class, 'getcountries']);
// Route::get('/getStates', [DropdownController::class, 'getStates']);
// Route::get('/getCities', [DropdownController::class, 'getCities']);


