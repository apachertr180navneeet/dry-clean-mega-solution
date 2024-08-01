<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\backends\{
    DashboardController,
    HomeController,
    AuthController
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\AuthenticatedSessionController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\Tenant\ServiceController;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
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
// Route::get('/', function () {
//     return view('welcome');
// });



require __DIR__ . '/auth.php';
