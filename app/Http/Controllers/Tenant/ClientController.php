<?php


namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
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
        $clients = User::where(['is_deleted' => 0, 'role_id' => 2])->orderBy('id', 'desc')->paginate(10);
        return view('admin.client', compact('clients'));
    }

    public function addClient(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:20',
            'mobile' => [
                'required',
                'regex:/^[0-9()+-]+$/',
                'min:4',
                'max:15',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', 0);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Check if a user with the same mobile number and is_deleted = 1 exists
        $existingUser = User::where('mobile', $request->mobile)->where('is_deleted', 1)->first();

        if ($existingUser) {
            // Update the existing user with the new data
            $existingUser->update([
                'name' => $request->name,
                'email' => $request->email ?? null,
                'password' => bcrypt($request->password) ?? $existingUser->password,
                'is_deleted' => 0,
                'role_id' => 2,
            ]);
        } else {
            // Create a new user
            User::create([
                'name' => $request->name,
                'email' => $request->email ?? null,
                'mobile' => $request->mobile,
                'password' => bcrypt($request->password) ?? null,
                'role_id' => 2,
            ]);
        }

        return redirect()->route('clientpage')->with('success', 'Client added successfully');
    } catch (\Throwable $throwable) {
        \Log::error($throwable->getMessage());
        return redirect()->back()->with('error', 'An error occurred while adding the client.');
    }
}

    public function editClient(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:20',
                'mobile' => [
                    'required',
                    'regex:/^[0-9()+-]+$/',
                    'min:4',
                    'max:15',
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            } else {
                $client = User::findOrFail($id);

                $input = $request->all();

                $client->update([
                    'name' => $input['name'],
                    'email' => $input['email'] ?? $client->email,
                    'mobile' => $input['mobile'],
                    'password' => $input['password'] ?? $client->password,
                    // You can update other fields as needed
                ]);

                return redirect()->route('clientpage')->with('success', 'Client updated successfully');
            }
        } catch (\Throwable $throwable) {
            \Log::error($throwable->getMessage());
            // dd($throwable->getMessage());
        }
    }


    public function deleteClient($id)
    {
        try {
            $client = User::findOrFail($id);
            $client->update(['is_deleted' => 1]);
            return response()->json(['message' => 'Client deleted successfully']);
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }
}
