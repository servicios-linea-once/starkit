<?php

use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\AvatarController;
use App\Http\Controllers\Settings\TwoFactorSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('settings')->name('settings.')->group(function () {

    // Perfil
    Route::get ('profile',        [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('profile',       [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('account',      [ProfileController::class, 'destroy'])->name('account.destroy');

    // Avatar
    Route::post  ('avatar',       [AvatarController::class, 'update'])  ->name('avatar.update');
    Route::delete('avatar',       [AvatarController::class, 'destroy']) ->name('avatar.destroy');

    // Contraseña
    Route::get ('password',       [PasswordController::class, 'edit'])  ->name('password.edit');
    Route::put ('password',       [PasswordController::class, 'update'])->name('password.update');

    // 2FA
    Route::post  ('two-factor',   [TwoFactorSettingsController::class, 'enable']) ->name('two-factor.enable');
    Route::delete('two-factor',   [TwoFactorSettingsController::class, 'disable'])->name('two-factor.disable');


    Route::get('login-history', [LoginHistoryController::class, 'mine'])->name('login-history.mine');
    Route::delete('sessions', [LoginHistoryController::class, 'destroyOtherSessions'])
        ->name('sessions.destroy');
});
