<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\AuthenticatedSessionController; 
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\Tenant\ServiceController;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    $clientCounts = Tenant::where(['is_deleted' => 0])->count();
    // $orderCounts = Order::where(['is_deleted' => 0])->count();
    
    // Get the count of pending orders in the last 10 days
    // $tenDaysAgo = Carbon::now()->subDays(10);
    // $pendingOrderCounts = Order::where('is_deleted', 0)
    //     ->where('status', 'pending') // Replace 'pending' with the actual status value for pending orders
    //     ->where('created_at', '>=', $tenDaysAgo)
    //     ->count();

    return view('backend.dashboard', compact('clientCounts'));
})->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/dashboard', function () {
//     return view('backend.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/myProfile', [App\Http\Controllers\backends\HomeController::class, 'myprofile'])->name('myProfile');
    Route::get('/edit/profile/{id}', [App\Http\Controllers\backends\HomeController::class, 'editprofile'])->name('edit.profile');
    Route::post('/profile/update/{id}', [App\Http\Controllers\backends\HomeController::class, 'updateprofilepost']);
    Route::get('/change/password', [App\Http\Controllers\backends\AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change/password/post', [App\Http\Controllers\backends\AuthController::class, 'changePasswordPost'])->name('change.password.post');
    Route::resource('tenants', App\Http\Controllers\TenantController::class);
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/myProfile', [App\Http\Controllers\backends\HomeController::class, 'myprofile'])->name('myProfile');
    Route::get('/edit/profile/{id}', [App\Http\Controllers\backends\HomeController::class, 'editprofile'])->name('edit.profile');
    Route::post('/profile/update/{id}/', [App\Http\Controllers\backends\HomeController::class, 'updateprofilepost'])->name('profile.update');
    Route::get('/change/password', [App\Http\Controllers\backends\AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change/password/post', [App\Http\Controllers\backends\AuthController::class, 'changePasswordPost'])->name('change.password.post');
    Route::resource('tenants', App\Http\Controllers\TenantController::class);
    // Route::get('/tenants', [App\Http\Controllers\TenantController::class, 'index'])->name('tenants.index');
    Route::post('/admin/delete-tenant/{id}', [App\Http\Controllers\TenantController::class, 'deleteTenant']);
});


require __DIR__ . '/auth.php';
