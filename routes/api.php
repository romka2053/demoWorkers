<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\WorkerController;
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

Route::post("register/", [AuthController::class,'register']);
Route::post("login/", [AuthController::class,'login']);
Route::post('upload_file/',[WorkerController::class,'image']);

Route::get("tree/", [WorkerController::class,'tree']);
Route::group(['middleware'=>['auth:sanctum']],function (){

    Route::post("logout/", [AuthController::class,'logout']);
    Route::post("workers/", [WorkerController::class,'store']);
    Route::put("workers/{id}", [WorkerController::class,'update']);
    Route::get("workers/{id}", [WorkerController::class,'show']);
    Route::delete("workers/{id}", [WorkerController::class,'destroy']);
    Route::get("workers/", [WorkerController::class,'index']);


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
