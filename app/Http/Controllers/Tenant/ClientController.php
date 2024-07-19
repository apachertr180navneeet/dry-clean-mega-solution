<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $clients = User::where(['is_deleted' => 0, 'role_id' => 2])->paginate(10);
        return view('admin.client', compact('clients'));
    }

    public function addClient(Request $request)
    {
        try {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:4|max:20',
                'mobile' => 'required|regex:/^[0-9()+-]+$/|min:4|max:15|unique:users,mobile'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            } else {
                $input = $request->all();
                User::create([
                    'name' => $input['name'],
                    'email' => $input['email'] ?? NULL,
                    'mobile' => $input['mobile'],
                    'password' => $input['password'] ?? NULL,
                    'role_id' => '2',
                ]);

                return redirect()->route('clientpage')->with('success', 'Client added successfully');
            }
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }

    public function editClient(Request $request, $id)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:20',
                'mobile' => 'required|numeric|min:4'
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
            dd($throwable->getMessage());
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
