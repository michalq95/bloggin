<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('comment', 'CommentCrudController');
    Route::crud('content', 'ContentCrudController');
    Route::crud('donation', 'DonationCrudController');
    Route::crud('donation-order', 'DonationOrderCrudController');
    Route::crud('image', 'ImageCrudController');
    Route::crud('post', 'PostCrudController');
    Route::crud('premium-membership', 'PremiumMembershipCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('taggable', 'TaggableCrudController');
    Route::crud('uploads', 'UploadsCrudController');
    Route::crud('user', 'UserCrudController');
}); // this should be the absolute last line of this file