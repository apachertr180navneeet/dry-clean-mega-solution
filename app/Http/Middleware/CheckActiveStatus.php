<?php
// app/Http/Middleware/CheckActiveStatus.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use  Illuminate\Http\Request;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CheckActiveStatus
{
    // public function handle(Request $request, Closure $next)
    // {
    //     $user = Auth::user();
    //     dd($user);
    //     if (Auth::check() && Auth::user()->is_active == 0) {
    //         Auth::logout();
    //         return redirect('/login')->with('error', 'Your account is inactive. Please contact your Super Admin.');
    //     }elseif (!Auth::check() && Auth::user()->is_active == 1) {
    //         // If the user is active but not logged in, log them in again
    //         Auth::login(Auth::user());
    //     }
    //     // dd(Auth::user());
    //     // if (Auth::check()) {
    //     //     if (Auth::user()->is_active == 0) {
    //     //         Auth::logout();
    //     //         return redirect('/login')->with('error', 'Your account is inactive. Please contact your Super Admin.');
    //     //     }
    //     // }

    //     // return $next($request);
    // }
    // public function handle(Request $request, Closure $next)
    // {
    //     // $this->middleware('auth'); // Add this line to execute the auth middleware first

    //     if (Auth::check()) {
    //         if (Auth::check() && Auth::user()->is_active == 0) {
    //             Auth::logout();
    //             return redirect('/login')->with('error', 'Your account is inactive. Please contact your Super Admin.');
    //         }
    //     }

    //     return $next($request);
    // }
//     public function handle(Request $request, Closure $next)
// {
//     // Check if the user is authenticated
//     if (Auth::check()) {
//         // Get the authenticated user
//         $tenant = Tenant::where('email', Auth::user()->email)
//             ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
//             ->first();
//             // dd($tenant);

//         $date = Carbon::now()->format("Y-m-d");

//         if($tenant->is_deleted == 0 && $tenant->is_active == 1){
//             if ($tenant->starting_date <= $date && $tenant->end_date >= $date) {
//                 // User is active and subscription is valid, allow access
//                 return $next($request);
//             } else {
//                 // Log the user out if their subscription is expired
//                 Auth::logout();
//                 request()->session()->flush();
//                 return redirect('/login')->with('error', 'Your subscription is expired. Please contact your Super Admin.');
//             }
//         }else{
//             // Log the user out if their account is deleted or inactive
//             Auth::logout();
//             request()->session()->flush();
//             return redirect('/login')->with('error', 'Please Contact your Super Admin');
//         }
//     }

//     return $next($request);
// }



}
