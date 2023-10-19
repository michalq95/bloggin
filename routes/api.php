<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\BindCommentRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout',   [AuthController::class, 'logout']);
    Route::resource('/post', PostController::class)->except(['index', 'show']);
    Route::post('/{model}/{id}/comment', [CommentController::class, 'store'])->middleware(BindCommentRoute::class);
    Route::resource('/comment', CommentController::class)->only(["show", "update", "destroy"]);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::resource('/post', PostController::class)->only(['index', 'show']);
// Route::resource('post/{post}/comment/{}', CommentController::class)->only(['index', 'show']);
Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
