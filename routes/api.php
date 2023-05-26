<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/product/{id}', [ProductsController::class, 'getProduct']);
Route::get('/product/images/{id}', [ProductsController::class, 'getProductImages']);

//Route::apiResource('products', 'API\ProductsController');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login',[AuthController::class, 'login']);
    Route::post('register',[AuthController::class, 'register']);

    Route::group([
        'middleware' => 'auth:api'
      ], function() {
          Route::get('logout', [AuthController::class , 'logout' ]);
          Route::get('user', [AuthController::class ,'user']);
      });
});