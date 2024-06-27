<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvenementController;
use App\Http\COntrollers\GoogleController;
use App\Http\COntrollers\ImageUploadController;
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
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profil', [AuthController::class, 'getUser'])->middleware('auth:sanctum');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [AuthController::class, 'reset']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

Route::post('/getAllEvt', [EvenementController::class, 'getAll']);
Route::post('/miseAJourEvenement', [EvenementController::class, 'update']);

Route::middleware('auth:sanctum')->get('/auth-check', function (Request $request) {
    return response()->json(['authenticated' => true, 'user' => $request->user()]);
});

Route::get('/auth-check', function () {
    return response()->json(['authenticated' => false]);
});

Route::get('auth/google', [GoogleController::class,'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class,'handleGoogleCallback']);

Route::post('/upload', [ImageUploadController::class, 'upload']);

Route::get('/photo/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});