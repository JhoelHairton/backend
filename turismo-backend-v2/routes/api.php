<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Emprendedor\EmprendedorController;
use App\Http\Controllers\Superadmin\PortalController;
use App\Http\Controllers\Superadmin\SuperadminController;
use App\Http\Controllers\Superadmin\PortalDesignController;

// 📦 Rutas públicas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('/register-turista', [AuthController::class, 'registerTurista']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

// 🛠️ Rutas protegidas para SUPERADMIN
Route::middleware(['auth:sanctum', 'role:superadmin'])->prefix('superadmin')->group(function () {
    // Gestión de usuarios
    Route::post('/crear-usuario-emprendedor', [SuperadminController::class, 'crearUsuarioEmprendedor']);
    
    //Gestion de empresa
    Route::get('/empresas/pendientes', [SuperadminController::class, 'listarEmpresasPendientes']);
    Route::put('/aprobar-empresa/{id}', [SuperadminController::class, 'aprobarEmpresa']);
    Route::put('/rechazar-empresa/{id}', [SuperadminController::class, 'rechazarEmpresa']);
    Route::get('/empresas/lista', [SuperadminController::class, 'listarTodasLasEmpresas']);

    
     // Portales
     Route::post('/portal', [PortalController::class, 'store']);
     Route::get('/portales', [PortalController::class, 'index']);
 
     // Diseños de portal
     Route::get('/portal/{id}/diseño', [PortalDesignController::class, 'show']);
     Route::post('/portal/diseño', [PortalDesignController::class, 'save']);
     Route::put('/portal/diseño/{id}', [PortalDesignController::class, 'update']);
     Route::delete('/portal/diseño/{id}', [PortalDesignController::class, 'destroy']);
});

// 🧑‍💼 Rutas para EMPRENDEDOR
Route::middleware(['auth:sanctum', 'role:emprendedor'])->prefix('emprendedor')->group(function () {
    Route::post('/crear-empresa', [EmprendedorController::class, 'crearEmpresa']);
    Route::get('/estado-empresa', [EmprendedorController::class, 'estadoEmpresa']);
});
