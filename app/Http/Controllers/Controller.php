<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    // public function __construct()
    // {
    //     $this->checkActiveStatus();
    // }

    // public function checkActiveStatus()
    // {
    //     $user = Auth::user();
    //     dd($user);
    //     if (Auth::check()) {
    //         $tenantId = tenant('id');

    //         $tenant = Tenant::where('tenants.id', $tenantId)
    //                         ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
    //                         ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
    //                         ->first();
    //         $date = Carbon::now()->format("Y-m-d");

    //         if ($tenant) {
    //             if ($tenant->is_active == 0 || !($tenant->starting_date <= $date && $tenant->end_date >= $date)) {
    //                 Auth::logout();
    //                 return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
    //             }
    //         }
    //     }
    // }
}
