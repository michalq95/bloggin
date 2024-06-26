<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PremiumMembershipController;
use App\Http\Controllers\RandomAnswerController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UploadsController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\AdminOnlyGuardMiddleware;
use App\Http\Middleware\BindCommentRoute;
use App\Http\Middleware\BindVotableRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::resource('/tag', TagController::class)->only(['index']);
Route::get('/upload/{uploads}', [UploadsController::class, 'show']);
Route::get('/donation', [DonationController::class, 'index']);
Route::post('/webhook', [DonationController::class, 'webhook']);
Route::get('/random-image', RandomAnswerController::class);


Route::middleware('guard')->group(function () {
    Route::post('/donation/{donation}', [DonationController::class, 'checkout']);
    Route::get('/{model}/{id}/comment', [CommentController::class, 'index'])->middleware(BindCommentRoute::class);
    Route::resource('/post', PostController::class)->only(['index', 'show']);
});


Route::middleware(['auth:sanctum'])->group(function () {



    Route::post('logout',   [AuthController::class, 'logout']);
    Route::get('permissions',   [AuthController::class, 'permissions']);
    Route::get('/post/user/{user}', [PostController::class, 'userPosts']);

    Route::resource('/post', PostController::class)->except(['index', 'show']);

    Route::post('/{model}/{id}/comment', [CommentController::class, 'store'])->middleware(BindCommentRoute::class);
    Route::post('/{model}/{id}/vote', VoteController::class)->middleware(BindVotableRoute::class);
    Route::resource('/comment', CommentController::class)->only(["show", "update", "destroy"]);

    Route::resource('/upload', UploadsController::class)->only(['store', 'index']);
    Route::get('/upload/{uploads}', [UploadsController::class, 'show']);
    Route::delete('/upload/{uploads}', [UploadsController::class, 'destroy']);
    Route::put('/upload/{uploads}', [UploadsController::class, 'update']);
    Route::get("/upload/{uploads}/test", [UploadsController::class, 'test']);



    Route::resource('/image', ImageController::class)->only(['destroy', 'store']);
    Route::resource("/premium", PremiumMembershipController::class)->middleware(AdminOnlyGuardMiddleware::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
