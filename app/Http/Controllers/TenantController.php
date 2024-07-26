<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch tenants that are not marked as deleted, including related domains and subscriptions
        $tenants = Tenant::where('is_deleted', 0)
            ->with(['domains', 'subscriptions'])
            ->get();

        // Return the view with the list of tenants
        return view('tenants.index', ['tenants' => $tenants]);
    }

    /**
     * Show the form for creating a new tenant.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return the view for creating a new tenant
        return view('backend.users.create');
    }

    /**
     * Store a newly created tenant in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'password' => ['required', Rules\Password::defaults()],
            'starting_date' => 'required|date',
            'end_date' => 'required|date|after:starting_date',
        ]);

        // Create a new tenant record
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Create associated domain record
        $tenant->domains()->create([
            'domain' => $validatedData['domain'] . '.' . config('app.domain'),
        ]);

        // Create associated subscription record
        Subscription::create([
            'tenant_id' => $tenant->id,
            'starting_date' => $validatedData['starting_date'],
            'end_date' => $validatedData['end_date'],
        ]);

        // Redirect to the tenant index page
        return redirect()->route('tenants.index');
    }

    /**
     * Show the form for editing the specified tenant.
     *
     * @param \App\Models\Tenant $tenant
     * @return \Illuminate\View\View
     */
    public function edit(Tenant $tenant)
    {
        // Fetch tenant data including related domains and subscriptions
        $tenant_data = Tenant::with(['domains', 'subscriptions'])->find($tenant->id);

        // Return the view for editing the tenant
        return view('tenants.edit', ['tenant' => $tenant_data]);
    }

    /**
     * Update the specified tenant in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tenant $tenant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tenant $tenant)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain' => 'required|string|max:255',
            'starting_date' => 'required|date',
            'end_date' => 'required|date|after:starting_date',
        ]);

        if ($validator->fails()) {
            // Redirect back with validation errors if validation fails
            return redirect()->back()->withErrors($validator->errors());
        }

        // Update tenant record
        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->active,
        ]);

        // Update associated domain record
        $tenant->domains()->update([
            'domain' => $request->domain,
        ]);

        // Update associated subscription record
        $tenant->subscriptions()->update([
            'starting_date' => $request->starting_date,
            'end_date' => $request->end_date,
        ]);

        // Redirect to the tenant index page
        return redirect()->route('tenants.index');
    }

    /**
     * Mark the specified tenant as deleted.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTenant($id)
    {
        try {
            // Mark tenant as deleted by updating the is_deleted flag
            Tenant::where('id', '=', $id)->update(['is_deleted' => 1]);

            // Return success response
            return response()->json(['message' => 'Resource deleted successfully']);
        } catch (\Throwable $throwable) {
            // Return error response if an exception occurs
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }
}
