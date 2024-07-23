<?php

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController
};
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    // Routes for authentication session
    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/', 'create')->name('login'); // Display login form
        Route::get('login', 'create')->name('login'); // Display login form (alias)
        Route::post('login', 'store'); // Handle login form submission
    });

    // Routes for password reset links
    Route::controller(PasswordResetLinkController::class)->group(function () {
        Route::get('forgot-password', 'create')->name('password.request'); // Display forgot password form
        Route::post('forgot-password', 'store')->name('password.email'); // Handle forgot password form submission
    });

    // Consolidated routes for new password management
    Route::controller(NewPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'create')->name('password.reset'); // Display password reset form
        Route::post('reset-password', 'store')->name('password.store'); // Handle password reset form submission
        Route::get('new-password', 'newPassword')->name('new.password'); // Display new password form
        Route::post('store-new-password', 'storeNewPassword')->name('store.new.password'); // Handle new password form submission
    });
});


Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password Confirmation Routes
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Password Update Route
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Logout Route
    Route::any('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

