<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\backends\DashboardController;
use App\Http\Controllers\backends\HomeController;
use App\Http\Controllers\backends\AuthController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;

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

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // Profile routes
    Route::get('/myProfile', [HomeController::class, 'myprofile'])->name('myProfile');
    Route::get('/edit/profile/{id}', [HomeController::class, 'editprofile'])->name('edit.profile');
    Route::post('/profile/update/{id}', [HomeController::class, 'updateprofilepost'])->name('profile.update');

    // Password routes
    Route::get('/change/password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change/password/post', [AuthController::class, 'changePasswordPost'])->name('change.password.post');

    // Tenant routes
    Route::resource('tenants', TenantController::class);
    Route::post('/admin/delete-tenant/{id}', [TenantController::class, 'deleteTenant']);
});

require __DIR__ . '/auth.php';

