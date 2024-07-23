<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function myprofile()
    {
        $user = Auth::user();
        if($user->is_admin == '0'){
            $tenantId = tenant('id');
            $tenant = Tenant::where('tenants.id', $tenantId)
                            ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                            ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
                            ->first();

            if (!$tenant) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
            }

            $date = Carbon::now()->format("Y-m-d");

            if ($tenant->is_active == 0 || !($tenant->starting_date <= $date && $tenant->end_date >= $date)) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
            }
        }
        return view('backend.profile');
    }

    public function editprofile($id)
    {
        $user = Auth::user();
        if($user->is_admin == '0'){
            $tenantId = tenant('id');

            $tenant = Tenant::where('tenants.id', $tenantId)
                            ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                            ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
                            ->first();

            if (!$tenant) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
            }

            $date = Carbon::now()->format("Y-m-d");

            if ($tenant->is_active == 0 || !($tenant->starting_date <= $date && $tenant->end_date >= $date)) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
            }
        }
        $user = User::find($id);
        return view('backend.updateProfile', compact('user'));
    }

    public function updateprofilepost(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('myProfile')->with('error', 'User not found');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            // Delete old image if exists
            if ($user->image && file_exists(public_path('images/' . $user->image))) {
                unlink(public_path('images/' . $user->image));
            }
            $user->image = $imageName;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('myProfile')->with('success', 'Profile updated successfully');
    }

}
