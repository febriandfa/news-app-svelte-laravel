<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\NewsController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(NewsController::class)->group(function () {
        // NEWS ROUTE
        Route::get('/news/index', 'index')->name('news.index');
        Route::get('/news/show/{id}', 'show')->name('news.show');
        Route::get('/news/create', 'create')->name('news.create');
        Route::post('/news/create', 'store')->name('news.store');
        Route::get('/news/edit/{id}', 'edit')->name('news.edit');
        Route::patch('/news/edit/{id}', 'update')->name('news.update');
        Route::delete('/news/destroy/{id}', 'destroy')->name('news.destroy');

        // COMMENT ROUTE
        Route::post('/news/show/{id}/comment' , 'storeComment')->name('comment.store');
        Route::delete('/news/show/{id}/comment/destroy', 'destroyComment')->name('comment.destroy');

        // REPLY ROUTE
        Route::post('/news/show/{newsId}/comment/{commentId}/reply' , 'storeCommentReply')->name('reply.store');
    });

    Route::controller(AdsController::class)->group(function() {
        Route::get('/ads/index', 'index')->name('ads.index');
        Route::get('/ads/show/{id}', 'show')->name('ads.show');
        Route::get('/ads/create', 'create')->name('ads.create');
        Route::post('/ads/create', 'store')->name('ads.store');
        Route::delete('/ads/destroy/{id}', 'destroy')->name('ads.destroy');
    });
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
