<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;


Route::middleware(['auth'])->group(function () {

Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])
    ->name('users.resetPassword');

   Route::get('/estadisticas', [DashboardController::class, 'estadisticas'])
    ->name('estadisticas');

    Route::post('/leads/{id}/tipificar', [LeadController::class, 'tipificar'])
        ->name('leads.tipificar');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->name('users.edit');

Route::put('/users/{user}', [UserController::class, 'update'])
    ->name('users.update');    

});
Route::get('/', function () {
    return view('welcome');
});


Route::post('/leads/importar', [LeadController::class, 'importar'])
    ->middleware('auth')
    ->name('leads.importar');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::post('/leads/asignar', [LeadController::class, 'asignar'])
    ->middleware('auth')
    ->name('leads.asignar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
