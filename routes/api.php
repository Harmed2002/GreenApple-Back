<?php

use App\Http\Controllers\AuthPassportController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post("login", [AuthPassportController::class, "login"]);
// Route::post("register", [AuthPassportController::class, "register"]);

// Route::middleware('auth:api')->group(function () {
//     Route::post("logout", [AuthPassportController::class, "logout"]);
// });

Route::controller(AuthPassportController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource("client", ClientController::class);
    Route::apiResource("order", OrderController::class);
    Route::apiResource("product", ProductController::class);
    Route::post("product/{id}/actualizar-imagen", [ProductController::class, "actualizarImagen"]);
});


Route::get("/no-autorizado", function() {
    return response()->json(["message" => "Accion no autorizada"]);
})->name("login");
