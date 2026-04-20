<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\VentaController;

Route::get('/health', function () {
    return response('OK', 200);
});

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Estadísticas
    Route::get('/estadisticas', [DashboardController::class, 'estadisticas'])
        ->name('estadisticas');

    // Usuarios
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.resetPassword');
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    // Leads — rutas fijas primero, dinámicas después
    Route::post('/leads/importar', [LeadController::class, 'importar'])
        ->name('leads.importar');
    Route::post('/leads/asignar', [LeadController::class, 'asignar'])
        ->name('leads.asignar');
    Route::post('/leads/{id}/tipificar', [LeadController::class, 'tipificar'])
        ->name('leads.tipificar');
    Route::post('/leads/{id}/marcar-telefono', [LeadController::class, 'marcarTelefono'])
        ->name('leads.marcarTelefono');
    Route::post('/leads/{id}/actualizar-telefonos', [LeadController::class, 'actualizarTelefonos'])
        ->name('leads.actualizarTelefonos');
    Route::put('/leads/{id}/editar', [LeadController::class, 'editarLead'])
        ->name('leads.editar');
    Route::post('/leads/{id}/agendar-seguimiento', [LeadController::class, 'agendarSeguimiento'])
        ->name('leads.agendarSeguimiento');

    // Ventas
    Route::post('/ventas', [VentaController::class, 'store'])
        ->name('ventas.store');
    Route::get('/ventas/mis-ventas', [VentaController::class, 'misVentas'])
        ->name('ventas.misVentas');
    Route::put('/ventas/{id}', [VentaController::class, 'update'])
        ->name('ventas.update');

    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])
        ->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leida', [NotificacionController::class, 'marcarLeida'])
        ->name('notificaciones.leida');
    Route::post('/notificaciones/leer-todas', [NotificacionController::class, 'marcarTodasLeidas'])
        ->name('notificaciones.leerTodas');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
