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
     * Display a listing of the resource.
     */
    public function index(Tenant $tenant)
    {
        $tenants = Tenant::where('is_deleted', 0)->with(['domains', 'subscriptions'])->get();
        return view('tenants.index',['tenants'=>$tenants]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validatedData = $request->validate([
    //         'name'=>'required|string|max:255',
    //         'email'=>'required|email|max:255',
    //         'domain'=>'required|string|max:255|unique:domains,domain',
    //         'password' => ['required', Rules\Password::defaults()],
    //     ]);

    //     $tenant = Tenant::create($validatedData);
    //     //dd($tenant);

    //     $tenant->domains()->create([
    //         'domain'=> $validatedData['domain'].'.'. config('app.domain'),
    //     ]);

    //     $subscription = SubsCription::crate([
    //         'tenent_id' => $tenant->id,
    //         'start_date' => $request->start_date,
    //         'end_date' => $request->end_date,
    //     ]);

    //     // return view('backend.users.list');
    //     return redirect()->route('tenants.index');

    // }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'password' => ['required', Rules\Password::defaults()],
            'starting_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        // $tenant = Tenant::create($validatedData);
        $tenant->domains()->create([
            'domain' => $validatedData['domain'] . '.' . config('app.domain'),
        ]);
        Subscription::create([
            'tenant_id' => $tenant->id,
            'starting_date' => $validatedData['starting_date'],
            'end_date' => $validatedData['end_date'],
        ]);
        return redirect()->route('tenants.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $tenant_data = Tenant::with(['domains', 'subscriptions'])->find($tenant->id);
        return view('tenants.edit', ['tenents'=>$tenant_data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validator =Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain' => 'required|string|max:255',
            'starting_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }

        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->active,
        ]);

        $tenant->domains()->update([
            'domain' => $request->domain,
        ]);


        $tenant->subscriptions()->update([
            'starting_date' => $request->starting_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('tenants.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        //
    }

    public function deleteTenant($id)
    {
        try {
            DB::table('tenants')->where('id','=',$id)->update(['is_deleted'=>1]);
            return response()->json(['message' => 'Resource deleted successfully']);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }
}
