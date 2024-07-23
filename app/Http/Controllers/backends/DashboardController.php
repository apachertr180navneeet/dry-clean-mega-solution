<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;
use App\Models\User;

use App\Models\Blog;
use App\Models\Category;
use App\Models\CurruntOpening;
use App\Models\Tenant;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function dashboard()
    {
        $clientCounts = Tenant::where('is_deleted', 0)->count();
        return view('backend.dashboard', compact('clientCounts'));
    }

}
