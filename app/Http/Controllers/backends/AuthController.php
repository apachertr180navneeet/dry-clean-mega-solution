<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Mail;

use Illuminate\Support\Str;


class AuthController extends Controller
{

    public function adminLogin()
    {
        if (!empty(Auth::user()->id)) {
            return view('backend.dashboard');
        }
        return view('backend.auth.login');
    }

    public function adminLoginPost(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('dashboard')->with('success', 'Login Successfully');
        } else {
            return redirect()->route('admin.login')->with('error', 'Login Failed | User Name or Password Not Match');
        }
    }

    public function changePassword()
    {
        $user = Auth::user();
        if($user->is_admin == '0'){
        $tenantId = tenant('id');

        // if (!$tenantId) {
        //     Auth::logout();
        //     return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        // }

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
        return view('backend.auth.changePassword');
    }

    public function changePasswordPost(Request $request)
    {
        // Custom validation rule for checking the old password
        $request->validate([
            'password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The old password is incorrect.'));
                }
            }],
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Password changed successfully');
    }

    public function forgetPassword()
    {
        $tenantId = tenant('id');

        // if (!$tenantId) {
        //     Auth::logout();
        //     return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        // }

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
        return view('backend.auth.forgetPassword');
    }

    public function forgetPasswordPost(Request $request)
    {
        $tenantId = tenant('id');

        // if (!$tenantId) {
        //     Auth::logout();
        //     return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        // }

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
        $emailotp = $request->input('email');
        $otp = mt_rand(100000, 999999);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $emailotp],
            ['token' => $otp, 'created_at' => now()]
        );
        // \Mail::to($email)->send(new ResetPasswordMail($otp));

        return view('backend.auth.otp');
    }

    public function createOTP()
    {
        $tenantId = tenant('id');

        // if (!$tenantId) {
        //     Auth::logout();
        //     return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        // }

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
        return view('backend.auth.otp');
    }

    public function storeOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');


        $validOtp = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $otp)
            ->where('created_at', '>', now()->subMinutes(config('auth.passwords.users.expire')))
            ->exists();

        if ($validOtp) {
            DB::table('password_resets')->where('email', $email)->delete();
            auth()->login(User::where('email', $email)->first());
            return redirect()->route('dashboard');
        } else {
           // return "Some Thing Went Wrong";
             return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin');
    }


    public function register()
    {
        $getrole = Role::get(['name']);
        return view('backend.users.create', compact('getrole'));
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'role' => 'required'

        ]);
        $input = $request->all();
        $password = Hash::make($input['password']);
        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = $password;
        $user->role = $request->role;
        $userRole = $request->role;
        $user->assignRole($userRole);
        $user->save();
        return redirect()->route('list.register')->with('success', 'User Add Successfully');
    }

    public function registerlist(Request $request)
    {
        if ($request->ajax()) {
            $data =  User::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit = route('edit.register', ['id' => $row->id]);
                    $delete = route('delete.register', ['id' => $row->id]);
                    $editbtn = '';
                    $editbtn = '<a class="edit btn btn-outline-success btn-sm" href="' . $edit . '" title="Edit"><i class="fa fa-edit" ></i></a>';
                    $deletebtn = '';
                    $deletebtn =  "<a href='" . $delete . "'  id='remove-page' class='btn btn-outline-danger btn-sm  remove-page-$row->id' title='Delete' onClick='removePage($row->id)'><i class='fa fa-trash'></i></a>";
                    return $editbtn . '&nbsp;' . $deletebtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.users.list');
    }

    public function registeredit($id)
    {
        $user = User::find($id);
        $getrole = Role::get(['name']);
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('backend.users.create', compact('user', 'getrole', 'userRole'));
    }

    public function registerupdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'role' => 'required'

        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $userRole = $request->role;
        $user->assignRole($userRole);
        $user->update();
        return redirect()->route('list.register')->with('success', 'User Updated Successfully');
    }

    public function registerdelete($id)
    {
        $delete = User::find($id)->delete();
        return redirect()->route('list.register')->with('success', 'User Deleted Successfully');
    }
}
