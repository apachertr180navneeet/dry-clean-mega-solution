<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // dd("hello Guys");
        return view('auth.login');
        // return view('backend.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // $tenant = Tenant::where('email','=', $request->email)->first();
        $tenant = Tenant::where('email', $request->email)
                ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                ->first();
                // dd($tenant);
                if (is_null($tenant)) {
                    return redirect('/login')->with('error', 'Invalid email or password.');
                }
        $date = Carbon::now()->format("Y-m-d");
        // dd($date);
        // if (!$request->authenticate()) {
        //     return redirect('/login')->with('error', 'Invalid email or password.');
        // }
        if($tenant->is_deleted == 0 && $tenant->is_active == 1){
            if ($tenant->starting_date <= $date && $tenant->end_date >= $date) {
                $request->authenticate();

                $request->session()->regenerate();

                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                // Auth::guard('web')->logout();
                // Auth::logout();
                // request()->session()->flush();
                return redirect('/login')->with('error', 'Your subscription is expired. Please contact your Super Admin.');
            }
        }else{
            // Auth::guard('web')->logout();
                // Auth::logout();
                // request()->session()->flush();
            return redirect('/login')->with('error', 'Please Contact your Super Admin');
        }


        // return redirect()->route('app-dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
