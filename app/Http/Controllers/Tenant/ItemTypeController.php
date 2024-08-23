<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Tenant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItemTypeController extends Controller
{
    //
    public function index(Request $request)
    {
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
        $query = ProductType::query();

        if ($request->ajax()) {
            $search = $request->input('search');
            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $itemtype = $query->orderBy('id', 'desc')->paginate(10);

            return response()->json([
                'itemtype' => $itemtype->items(),
                'pagination' => (string) $itemtype->links()
            ]);
        }

        $itemtype = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.itemtype', ['itemtype' => $itemtype]);
    }

    public function addType(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            } else {

                $input = $request->all();
                ProductType::create([
                    'name' => $input['name'],
                ]);
                // dd($client);
                return redirect()->route('itemtype')->with('success', 'Product Type added successfully');
            }
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }

    public function edit($id)
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
        $itemtype = ProductType::findOrFail($id);
        // You can pass $service to the view for editing
        return view('admin.itemtype', ['itemtype' => $itemtype]);
    }

    public function updateItemType(Request $request, $id)
    {
        try {
            $service = ProductType::findOrFail($id);
            $service->name = $request->input('name');
            $service->save();

            return redirect()->back()->with('success', 'Service updated successfully');
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }


    public function deleteItemType($id)
    {
        try {
            $resource = ProductType::findOrFail($id);
            $resource->delete();

            return response()->json(['message' => 'Resource deleted successfully']);
        } catch (\Throwable $throwable) {
            dd($throwable->getMessage());
        }
    }
}
