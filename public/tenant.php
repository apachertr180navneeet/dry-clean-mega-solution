<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
//use App\Http\Middleware\CheckActiveStatus;
use App\Http\Controllers\App\Auth\AuthenticatedSessionController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\Tenant\ServiceController;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// Route::middleware([
//     'web',
//     InitializeTenancyByDomain::class,
//     PreventAccessFromCentralDomains::class,
// ])->group(function () {


//     // Route::get('/', function () {
//     //     return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
//     // });
//     Route::get('/', function () {
//         return view('app.welcome');
//     });


//     Route::get('/tenant/dashboard', function () {
//         return view('app.dashboard');
//     })->middleware(['tenant_auth', 'verified'])->name('dashboard');


//     Route::middleware('tenant_auth')->group(function () {
//         Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//         Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//         Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//         // Route::resource('users', TenantController::class);
//     });

//     require __DIR__ . '/tenant_auth.php';//this is a route for going to tenant_auth file
// });
// dd(Auth::user());

// Route::middleware(['web', 'auth', 'CheckActiveStatus'])->group(function () {
Route::middleware([
    'web',

    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // 'auth',
    // CheckActiveStatus::class,
])->group(function () {

    // Route::get('/',function(){
    //     return view('app.welcome');
    // });


    // Route::get('/dashboard', function () {
    //     $clientCounts = User::where(['is_deleted' => 0, 'role_id' => 2])->count();
    //     $orderCounts = Order::where(['is_deleted' => 0])->count();
    //     //  dd($orderCounts);
    //     return view('app/dashboard', compact('clientCounts','orderCounts'));

    // })->middleware(['auth'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Tenant\DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');


    Route::middleware('auth')->group(function () {

        Route::get('/myProfile', [App\Http\Controllers\backends\HomeController::class, 'myprofile'])->name('myProfile');
        Route::get('/edit/profile/{id}', [App\Http\Controllers\backends\HomeController::class, 'editprofile'])->name('edit.profile');
        Route::post('/profile/update/{id}', [App\Http\Controllers\backends\HomeController::class, 'updateprofilepost']);
        Route::get('/change/password', [App\Http\Controllers\backends\AuthController::class, 'changePassword'])->name('change.password');
        Route::post('/change/password/post', [App\Http\Controllers\backends\AuthController::class, 'changePasswordPost'])->name('change.password.post');
        Route::resource('users', UserController::class);
    });

    Route::get('/admin/client', [App\Http\Controllers\Tenant\ClientController::class, 'index'])->middleware(['auth'])->name('clientpage');
    Route::post('/admin/add-client', [App\Http\Controllers\Tenant\ClientController::class, 'addClient'])->middleware(['auth'])->name('add.client');
    Route::post('/admin/edit-client/{id}', [App\Http\Controllers\Tenant\ClientController::class, 'editClient'])->middleware(['auth']);
    Route::get('/admin/delete-client/{id}', [App\Http\Controllers\Tenant\ClientController::class, 'deleteClient'])->middleware(['auth']);


    Route::get('/fetch-client-name', [App\Http\Controllers\Tenant\OrderController::class, 'fetchClientName'])->middleware(['auth']);
    Route::get('/admin/order', [App\Http\Controllers\Tenant\OrderController::class, 'index'])->middleware(['auth'])->name('addOrder');
    Route::post('/admin/add-order', [App\Http\Controllers\Tenant\OrderController::class, 'addOrder'])->middleware(['auth'])->name('add.order');
    Route::post('/get-service', [App\Http\Controllers\Tenant\OrderController::class, 'getServiceData'])->middleware(['auth']);
    Route::post('/get-allservice', [App\Http\Controllers\Tenant\OrderController::class, 'getAllServiceData'])->middleware(['auth']);
    Route::get('admin/edit-order/{id}', [App\Http\Controllers\Tenant\OrderController::class, 'editOrder'])->middleware(['auth'])->name('order.edit');
    Route::put('admin/update-order/{id}', [App\Http\Controllers\Tenant\OrderController::class, 'updateOrder'])->middleware(['auth'])->name('order.update');

    Route::get('/admin/view-order', [App\Http\Controllers\Tenant\OrderController::class, 'viewOrder'])->middleware(['auth'])->name('viewOrder');
    Route::get('/admin/show-order/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'OrderDetail'])->middleware(['auth'])->name('OrderDetail');
    Route::get('/admin/delete-order/{id}', [App\Http\Controllers\Tenant\OrderController::class, 'deleteOrder'])->middleware(['auth']);
    Route::get('/admin/receipt/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'PrintReceipt'])->middleware(['auth'])->name('receipt');
    Route::get('/admin/invoice/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'PrintInvoice'])->middleware(['auth'])->name('invoicepdf');
    Route::get('/get-services', [App\Http\Controllers\Tenant\OrderController::class, 'getServices'])->middleware(['auth'])->name('getServices');
    Route::get('/get-price', [App\Http\Controllers\Tenant\OrderController::class, 'getPrice'])->middleware(['auth'])->name('getprice');


    // Route::get('/admin/viewOrder', [App\Http\Controllers\Tenant\OrderController::class, 'viewOrder'])->name('viewOrder');
    // Route::get('/admin/orderDetail', [App\Http\Controllers\Tenant\OrderController::class, 'orderDetail'])->name('OrderDetail');
    // Route::post('/admin/add-order', [App\Http\Controllers\Tenant\OrderController::class, 'addOrder'])->name('add.order');



    Route::get('/admin/payment', function () {
        return view('/admin/payment');
    })->middleware(['auth'])->name('payment');
    // Route::get('/admin/invoice', function () {
    //     return view('/admin/invoice');
    // })->name('invoice');
    // Category Module Route

    Route::get('/admin/categorylist', [\App\Http\Controllers\Tenant\CategoryController::class, 'index'])->middleware(['auth'])->name('categorylist');
    Route::get('/admin/category', [\App\Http\Controllers\Tenant\CategoryController::class, 'addcategory'])->middleware(['auth'])->name('category');
    Route::post('/admin/category-add', [\App\Http\Controllers\Tenant\CategoryController::class, 'storeCategory'])->middleware(['auth'])->name('add.category.details');
    Route::get('/fetch-data-clothes', [\App\Http\Controllers\Tenant\CategoryController::class, 'fetchClothesData'])->middleware(['auth']);
    Route::get('/fetch-data-upholstrey', [\App\Http\Controllers\Tenant\CategoryController::class, 'fetchUpholsteryData'])->middleware(['auth']);
    Route::get('/fetch-data-footbags', [\App\Http\Controllers\Tenant\CategoryController::class, 'fetchFootBagData'])->middleware(['auth']);
    Route::get('/fetch-data-other', [\App\Http\Controllers\Tenant\CategoryController::class, 'fetchOtherData'])->middleware(['auth']);
    Route::get('/fetch-data-laundry', [\App\Http\Controllers\Tenant\CategoryController::class, 'fetchLaundryData'])->middleware(['auth']);
    Route::post('/delete-clothes/{id}', [\App\Http\Controllers\Tenant\CategoryController::class, 'deleteClothes'])->middleware(['auth']);
    Route::post('/admin/categorylist', [\App\Http\Controllers\Tenant\CategoryController::class, 'editItems'])->middleware(['auth']);

    //Service Add Functionality SK


    // Route::get('/admin/service', function () {
    //     return view('/admin/service');
    // })->name('service');
    Route::get('/admin/service', [App\Http\Controllers\Tenant\ServiceController::class, 'index'])->middleware(['auth'])->name('service');
    Route::post('/admin/add-service', [App\Http\Controllers\Tenant\ServiceController::class, 'addService'])->middleware(['auth'])->name('add.service');
    Route::post('/admin/edit-services/{id}', [App\Http\Controllers\Tenant\ServiceController::class, 'updateService'])->middleware(['auth']);
    Route::get('/admin/delete-services/{id}', [App\Http\Controllers\Tenant\ServiceController::class, 'deleteService'])->middleware(['auth']);
    // Route::get('/admin/services/{id}',[App\Http\Controllers\Tenant\ServiceController::class, 'destroy'])->name('destroy.service');
    // Route::get('/admin/receipt', function () {
    //     return view('/admin/receipt');
    // })->name('receipt');
    Route::get('/admin/invoicePdf', function () {
        return view('/admin/invoicePdf');
    })->middleware(['auth'])->name('invoicePdf');
    // Route::get('/admin/tagslist', function () {
    //     return view('/admin/tagslist');
    // })->name('tagslist');



    //Payment show Functionality SK
    Route::get('/admin/payment', [App\Http\Controllers\Tenant\PaymentController::class, 'index'])->middleware(['auth'])->name('payment');
    //Invoice show Functionality SK
    Route::get('/admin/invoice', [App\Http\Controllers\Tenant\InvoiceController::class, 'index'])->middleware(['auth'])->name('invoice');
    Route::get('/admin/indexfilter', [App\Http\Controllers\Tenant\InvoiceController::class, 'indexfilter'])->middleware(['auth'])->name('indexfilter');

    //for whatsapp message
    Route::match(['get', 'post'], '/send-wh-message/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'sendWhMessage'])->middleware(['auth'])->name('orders.store');
    // routes/web.php
Route::get('/download-receipt/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'downloadReceipt'])->middleware(['auth'])->name('download-receipt');
Route::get('/download-invoice/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'downloadInvoice'])->middleware(['auth'])->name('download-invoice');
//for paymnt settle
// Route::get('/settle-order/{orderId}/{paymentType}', [App\Http\Controllers\Tenant\PaymentController::class, 'settleOrder'])->name('settle-order');
// //deliver order
// Route::get('/deliver-order/{orderId}', [App\Http\Controllers\Tenant\PaymentController::class, 'deliverOrder'])->name('deliver-order');
Route::post('/settle-and-deliver-order/{orderId}', [App\Http\Controllers\Tenant\PaymentController::class, 'settleAndDeliverOrder'])->middleware(['auth']);


//print tags
Route::get('/admin/tagslist/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'tagList'])->middleware(['auth'])->name('tagslist');
//download taglist
Route::get('/print-taglist/{orderId}', [App\Http\Controllers\Tenant\OrderController::class, 'printTaglist'])->middleware(['auth'])->name('download-tagslist');
//for show reports in excel
Route::get('/admin/orders/export', [App\Http\Controllers\Tenant\InvoiceController::class, 'export'])->middleware(['auth'])->name('orders.export');



    require __DIR__ . '/tenant-auth.php';

});
// });
