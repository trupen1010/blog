<?php

use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
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

//! Auth

Route::post('admin/login', [AuthController::class, 'adminLogin']);
Route::get('admin/logout', [AuthController::class, 'adminLogout']);

Route::get('tokens/create', [AuthController::class, 'guestUserCreateToken']);
Route::get('admin/tokens/create', [AuthController::class, 'adminCreateToken']);
Route::get('tokens/check-expiration', [AuthController::class, 'checkExpiration']);

Route::middleware('auth:sanctum')->group(function () {
    //! Home-Chart
    Route::get('admin/chart-data', [AdminHomeController::class, 'index']);
    //! Category
    Route::get('admin/categories-datatable', [AdminCategoryController::class, 'datatable']);
    Route::apiResource('admin/categories', AdminCategoryController::class);
    //! Tag
    Route::get('admin/tags-datatable', [AdminTagController::class, 'datatable']);
    Route::apiResource('admin/tags', AdminTagController::class);
    //! Author
    Route::get('admin/authors-datatable', [AdminAuthorController::class, 'datatable']);
    Route::post('admin/authors/{id}', [AdminAuthorController::class, 'update']);
    Route::apiResource('admin/authors', AdminAuthorController::class)->except('update');
    //! Post
    Route::get('admin/posts-datatable', [AdminPostController::class, 'datatable']);
    Route::post('admin/posts/{id}', [AdminPostController::class, 'update']);
    Route::apiResource('admin/posts', AdminPostController::class);
});

//! Category
Route::apiResource('categories', CategoryController::class);

//! Tag
Route::apiResource('tags', TagController::class);

//! Author
Route::apiResource('authors', AuthorController::class);

//! Post
Route::apiResource('posts', PostController::class);
