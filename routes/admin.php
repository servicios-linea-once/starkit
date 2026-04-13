<?php

use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::middleware('permission:users.view')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
        });

        Route::middleware('permission:users.create')->group(function () {
            Route::get ('users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('users',        [UserController::class, 'store']) ->name('users.store');
        });

        Route::middleware('permission:users.edit')->group(function () {
            Route::get  ('users/{user}/edit', [UserController::class, 'edit'])        ->name('users.edit');
            Route::put  ('users/{user}',      [UserController::class, 'update'])      ->name('users.update');
            Route::patch('users/{user}/status',[UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        Route::middleware('permission:users.delete')->group(function () {
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // Roles
        Route::middleware('permission:roles.view')->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        });
        Route::middleware('permission:roles.create')->group(function () {
            Route::get ('roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('roles',        [RoleController::class, 'store']) ->name('roles.store');
        });
        Route::middleware('permission:roles.edit')->group(function () {
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])  ->name('roles.edit');
            Route::put('roles/{role}',      [RoleController::class, 'update'])->name('roles.update');
        });
        Route::middleware('permission:roles.delete')->group(function () {
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        });

// Historial de sesiones (global)
        Route::middleware('permission:users.view')->group(function () {
            Route::get('login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');
        });
    });
