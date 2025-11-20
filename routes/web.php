<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\PostController;      
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::resource('posts', PostController::class);

Route::middleware(['auth'])->group(function () {

    Route::resource('actividades', ActividadController::class);

    Route::resource('comments', CommentController::class)
        ->only(['store', 'destroy']);

    Route::resource('notas', NotaController::class)->except(['create']);
});
