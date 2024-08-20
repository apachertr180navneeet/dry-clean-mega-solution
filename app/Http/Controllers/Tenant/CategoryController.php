<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductItem;
use App\Models\Service;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    //
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
        $clothes_datas = ProductItem::where('product_id', 1)->get();
        $upholstery_datas = ProductItem::where('product_id', 2)->get();
        $footwearandbags = ProductItem::where('product_id', 3)->get();
        $others = ProductItem::where('product_id', 4)->get();
        $laundries = ProductItem::where('product_id', 5)->get();
        $services = Service::all();
        // dd($clothes_data);
        return view('admin.categorylist', ['clothes_datas'=>$clothes_datas, 'upholstery_datas'=>$upholstery_datas, 'footwearandbags'=>$footwearandbags, 'others'=>$others, 'services'=>$services, 'laundries'=>$laundries]);
    }

    public function fetchClothesData(Request $request)
    {

        // dd($request->all());
        // Fetch data from the database and filter based on the selected option
        $query = ProductCategory::select('product_categories.id as product_cat_id','product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
        ->where('product_items.product_id', 1)
        ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
        ->join('operations','operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
    }

    public function fetchUpholsteryData(Request $request)
    {

        // dd($request->all());
        // Fetch data from the database and filter based on the selected option
        $query = ProductCategory::select('product_categories.id as product_cat_id','product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
        ->where('product_items.product_id', 2)
        ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
        ->join('operations','operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
    }

    public function fetchFootBagData(Request $request)
    {
        // Fetch data from the database and filter based on the selected option
        $query = ProductCategory::select('product_categories.id as product_cat_id','product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
        ->where('product_items.product_id', 3)
        ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
        ->join('operations','operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
    }

    public function fetchOtherData(Request $request)
    {

        // dd($request->all());
        // Fetch data from the database and filter based on the selected option
        $query = ProductCategory::select('product_categories.id as product_cat_id','product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
        ->where('product_items.product_id', 4)
        ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
        ->join('operations','operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
    }
    public function fetchLaundryData(Request $request)
    {

        // dd($request->all());
        // Fetch data from the database and filter based on the selected option
        $query = ProductCategory::select('product_categories.id as product_cat_id','product_categories.name as product_name', 'product_categories.price', 'operations.name as service_name', 'operations.id as operation_id')
        ->where('product_items.product_id', 5)
        ->join('product_items', 'product_categories.product_item_id', '=', 'product_items.id')
        ->join('operations','operations.id', '=', 'product_categories.operation_id');
        if ($request->has('option')) {
            $selectedOption = $request->input('option');
            // Example: Filter data based on the selected option
            $query->where('product_items.id', $selectedOption);
        }
        $data = $query->get();

        // Return data as JSON response
        return response()->json($data);
    }

    public function deleteClothes(Request $request)
    {
        $id = $request->cloth_id;
        // dd($id);
        try {
            ProductCategory::where('id', $id)->delete();
            return response()->json(['message' => 'Items deleted successfully']);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }

    public function editItems(Request $request)
    {
        // dd($request->all());
        $id = $request->item_id;
        try {
            $items = ProductCategory::findOrFail($id);
            // dd($items);
            $items->name = $request->name;
            $items->operation_id = $request->service;
            $items->price = $request->price;
            $items->save();
            return redirect()->back()->with('success', 'Items updated successfully');
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }

    public function addcategory()
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
        $products = Product::all();
        $services = Service::all();
        return view('admin.category', ['products' => $products, 'services' => $services]);
    }

    public function storeCategory(Request $request)
    {
        $currentTimestamp = Carbon::now();
        $category = $request->category;
        // dd($category);
        foreach ($category as $cat_key=>$cat) {
            $items = $request->item_name[$cat_key];
            // $category = $request->category[$i];
            foreach ($items as $key=>$item) {
                $available_data = ProductItem::where('name', $request->item_name[$cat_key][$key])->first();
                // dd($request->hasFile('image.'.$cat_key.'.'.$key));
                if($available_data){
                    $id = $available_data->id;
                }else{
                    if ($request->hasFile('image.'.$cat_key.'.'.$key)) {
                        $image = $request->file('image.'.$cat_key.'.'.$key);
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        // dd($imageName);
                        $image->move(public_path('images/categories_img'), $imageName);
                        $data['image'] = $imageName;
                    }
                    $data['product_id'] = $cat;
                    $data['name'] = $request->item_name[$cat_key][$key];
                    $data['created_at'] = $currentTimestamp;
                    $data['updated_at'] = $currentTimestamp;
                    $id = ProductItem::insertGetId($data);
                }

                $datas = [];
                $datas['product_item_id'] = $id;
                $datas['operation_id'] = $request->service[$cat_key][$key];
                $datas['name'] = $request->item_type[$cat_key][$key];
                $datas['price'] = $request->price[$cat_key][$key];
                $datas['created_at'] = $currentTimestamp;
                $datas['updated_at'] = $currentTimestamp;

                ProductCategory::insert($datas);
            }
        }
        // return redirect()->back();
        return redirect()->route('categorylist')->with('success', 'Categories and items added successfully');
    }

}
