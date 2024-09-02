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



public function handle(Request $request, Closure $next)
    {
        // Get the current tenant
        $tenantId = tenant('id');

        // Fetch tenant with subscription information
        $tenant = Tenant::where('tenants.id', $tenantId)
                        ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                        ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
                        ->first();

        // Get current date
        $date = Carbon::now()->format("Y-m-d");

        // Check if tenant exists and validate tenant status and subscription dates
        if ($tenant) {
            if ($tenant->is_active == 0 || !($tenant->starting_date <= $date && $tenant->end_date >= $date)) {
                // Log out the user
            //     Auth::logout();
            //     // $request->session()->flash('error', 'Your tenant is inactive or your subscription has expired. Please contact your Super Admin.');
            //     // Session::flash('error', 'Your tenant is inactive or your subscription has expired. Please contact your Super Admin.');
            // // $request->session()->invalidate();
            // // $request->session()->regenerateToken();

            // // Redirect to the login page with an error message
            // return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
            Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();

            // Redirect to the login page with an error message
            return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
           
        }
    }

        return $next($request);
    }
}