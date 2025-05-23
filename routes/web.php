<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/todos', [TodoController::class, 'index'])->name('todo.index');
Route::get('/todos/bycategory', [TodoController::class, 'indexByCategory'])->name('todo.index-by-category');
Route::post('/todos', [TodoController::class, 'store'])->name('todo.store');
Route::delete('/todos', [TodoController::class, 'destroy'])->name('todo.destroy');

// 名称変更
Route::patch('/todos/{todo}', [TodoController::class, 'updateTitle'])->name('todos.update-title');

// テストコントローラ
Route::get('/test', [TestController::class, 'index'])->name('test.index');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
