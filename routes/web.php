<?php

use App\Http\Controllers\TenantController;
use App\Http\Controllers\backends\{
    DashboardController,
    HomeController,
    AuthController
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    // Route for the dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard'); // Name for route reference


    // Profile management routes
    Route::controller(HomeController::class)->group(function () {
        Route::get('/myProfile', 'myprofile')->name('myProfile');
        Route::get('/edit/profile/{id}', 'editprofile')->name('edit.profile');
        Route::post('/profile/update/{id}', 'updateprofilepost')->name('profile.update');
    });

    // Password management routes
    Route::controller(AuthController::class)->group(function () {
        Route::get('/change/password', 'changePassword')->name('change.password');
        Route::post('/change/password/post', 'changePasswordPost')->name('change.password.post');
    });

    // Resource routes for managing tenants
    Route::resource('tenants', TenantController::class);

    // Custom route for deleting a tenant, typically used for AJAX requests
    Route::post('/admin/delete-tenant/{id}', [TenantController::class, 'deleteTenant'])
        ->name('tenant.delete');
});


require __DIR__ . '/auth.php';
