<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\UserController;
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
Route::get('/posts/limiter', [PostController::class, 'limiter']);
Route::apiResource('/posts', PostController::class);

Route::get('/posts/{post}/favorites', [FavoriteController::class, 'isFavorited'])->name('posts.isfavorite');
Route::post('/posts/{post}/favorites', [FavoriteController::class, 'store'])->name('posts.favorite');
Route::delete('/posts/{post}/favorites', [FavoriteController::class, 'destroy'])->name('posts.unfavorite');

Route::get('/user/{profile}/posts', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/user/{profile}/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::middleware('auth:sanctum')->put('/user/{profile}/update', [ProfileController::class, 'update'])->name('profile.update');

// temp token generator for api
Route::post('/sanctum/token', [UserController::class, 'login'])->name('user.login');
Route::post('/user/register', [UserController::class, 'register'])->name('user.register');
Route::middleware('auth:sanctum')->delete('/sanctum/destroy/token',[UserController::class, 'logout'])->name('user.logout');
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'user'])->name('user.user');
