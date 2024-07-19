<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductItem;
use App\Models\ProductCategory;
use App\Models\PaymentDetail;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Throwable;
use App\Services\SmsService;

class OrderController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    // public function index()
    // {
    //     $discounts = DB::table('discounts')->get();
    //     $products = DB::table('products')->get();
    //     return view('admin.EditOrder', compact('discounts','products'));
    // }

    // public function index()
    // {
    //     $productItems = ProductItem::with(['categories','categories.service'])->get();

    //      //dd($productItems->toArray());
    //     $discounts = Discount::all();
    //     return view('admin.EditOrder', compact('productItems', 'discounts'));
    // }

    function generateRandomString($length = 6) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
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
        // $productItems = ProductItem::with('categories')->get();
        $productItems = ProductItem::with(['categories', 'categories.service'])->get();
        // dd($productItems);
        $groupedProductItems = [];

        foreach ($productItems as $productItem) {
            $uniqueCategories = [];

            foreach ($productItem->categories as $category) {
                $uniqueCategories[] = $category->name;
            }

            // Assuming you want to get the first operation ID and price related to the product item
            $operationId = $productItem->categories->first()->operation_id ?? null;
            $price = $productItem->categories->first()->price ?? null;

            $groupedProductItems[] = [
                'product_item' => $productItem,
                'unique_categories' => $uniqueCategories,
                'operation_id' => $operationId,
                'price' => $price,
                // 'services' => $this->getServices($productItem), // Fetch services for each product item
            ];
        }
        // dd($groupedProductItems);
        $discounts = Discount::all();

        $services = Service::all();

        return view('admin.EditOrder', compact('groupedProductItems', 'discounts','services','productItems'));
    }

    public function getOperationData($pid, $pname, $others = [])
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
        $data = DB::table('operations')
            ->select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
            ->where([
                'pc.product_item_id' => $pid,
                'pc.name' => $pname,
            ])
            ->join('product_categories as pc', 'operations.id', '=', 'pc.operation_id')
            ->get();

        // Return the operation view with data and others
        // dd($data);
        return view('admin.operation.operationview', ['data' => $data, "others" => $others])->render();
    }

    public function getServiceData(Request $request)
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
        $pId = $request->id;
        $pname = $request->name;
        $others = $request->others ?? [];
        return $this->getOperationData($pId, $pname, $others);
    }

    public function fetchClientName(Request $request)
    {
        try {
            $request->validate([
                'client_num' => 'required|numeric|digits:10',
            ]);
            $user = User::where('mobile', $request->client_num)->where('is_deleted', 0)->first();
            if ($user) {
                return response()->json([
                    'success' => true,
                    'client_name' => $user->name,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found for the given mobile number.',
                ]);
            }
        } catch (\Throwable $throwable) {
            return response()->json('error', 'Something Went Wrong.');
        }
    }

    //add order function
    public function addOrder(Request $request)
    {
        try {
            $data = $request->all();
            $AddorderItemsData = json_decode($request->input('order_items_add_data'), true);

            $validatedData = $request->validate([
                'client_num' => 'required|numeric',
                'client_name' => 'required|min:2|max:20',
                'booking_date' => 'required|date',
                'booking_time' => 'required|date_format:H:i',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required|date_format:H:i',
                'discount' => 'required',
                'total_qty' => 'required',
            ]);
            // dd($AddorderItemsData);

            // Retrieve client or create new one
            $client = DB::table('users')->where('mobile', $validatedData['client_num'])->first();
            if ($client) {
                $user_id = $client->id;
            } else {
                $user = User::create([
                    'name' => $validatedData['client_name'],
                    'mobile' => $validatedData['client_num'],
                    'role_id' => 2
                ]);
                $user_id = $user->id;
            }

            // Determine discount ID
            $discountId = $this->getDiscountId($request->discount);

             // Check if discountId is valid, otherwise set to null
            if (!DB::table('discounts')->where('id', $discountId)->exists()) {
                $discountId = null;
            }

            // Calculate total price with discount and optional express charge
            list($totalPriceDis, $totalDiscount) = $this->calculateTotalPrice($request);

            // Create the order and save in db
            $order = Order::create([
                'invoice_number' => '',
                'user_id' => $user_id,
                'order_date' => $validatedData['booking_date'],
                'order_time' => $validatedData['booking_time'],
                'delivery_date' => $validatedData['delivery_date'],
                'delivery_time' => $validatedData['delivery_time'],
                'discount_id' => $discountId,
                'service_id' => null, // Will be assigned later
                'status' => 'pending',
                'total_qty' => $validatedData['total_qty'],
                'total_price' => $totalPriceDis,
            ]);

            // Generate and save invoice number
            if ($order) {
                $orderId = $order->id;
                // $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($orderId, 3, '0', STR_PAD_LEFT);
                $invoiceNumber =  $this->generateRandomString();
                $order->invoice_number = $invoiceNumber;
                $order->save();
            }
            // dd($invoiceNumber);

            // Insert order items
            foreach ($AddorderItemsData as $categoryData) {
                $categoryId = $categoryData['category'];

                foreach ($categoryData['types'] as $typeData) {
                    $typeId = $typeData['type'];

                    foreach ($typeData['services'] as $serviceData) {
                        $serviceId = $serviceData['service'];
                        $quantity = $serviceData['quantity'];
                        $price = $serviceData['price'];

                        // Create and save the order item
                        $order->orderItems()->create([
                            'order_id' => $order->id,
                            'product_item_id' => $categoryId, // Assuming product_item_id refers to typeId
                            'product_category_id' => $typeId,
                            'operation_id' => $serviceId,
                            'quantity' => $quantity,
                            'operation_price' => $price,
                            'price' => $quantity * $price,
                            'status' => 'pending'
                        ]);
                    }
                }
            }

            // Create payment details
            PaymentDetail::create([
                'order_id' => $order->id,
                'total_quantity' => $validatedData['total_qty'],
                'total_amount' => $totalPriceDis,
                'discount_amount' => $totalDiscount,
                'service_charge' => $request->express_charge == '1' ? ($totalPriceDis * 50) / 100 : 0,
                'paid_amount' => 0, // Initially no amount paid
                'status' => 'Due',
                'payment_type' => null // Payment type is null initially
            ]);

            // Prepare SMS message
            $message = sprintf(
                "Dear %s, your order (ID: %s) of %s is Received. Estimated delivery: %s. Thank you. Mega Solutions Dry cleaning",
                $validatedData['client_name'],
                $order->id,
                $order->total_price,
                $validatedData['delivery_date']
            );

              // Format the client's phone number
                $clientPhoneNumber = '+91' . $validatedData['client_num'];

                 // Attempt to send SMS and handle any exceptions
                try {
                    $this->smsService->sendSms($clientPhoneNumber, $message);
                } catch (\Exception $e) {
                    // Log the SMS error and continue with order creation
                    Log::error('Error sending SMS: ' . $e->getMessage());
                }

            return redirect()->route('viewOrder');
        } catch (\Exception $exception) {
            dd([
                            'message' => $exception->getMessage(),
                            'line' => $exception->getLine(),
                        ]);
            // return back()->withErrors($exception->getMessage())->withInput();
        }
    }


    private function getDiscountId($discount)
    {
        switch ($discount) {
            case '5':
                return 1;
            case '10':
                return 2;
            case '15':
                return 3;
            case '20':
                return 4;
            default:
                return 0; // Default or no discount
        }
    }

    private function calculateTotalPrice(Request $request)
    {
        $grossPrice = $request->gross_total;
        $totalDiscount = ($grossPrice * ($request->discount ? $request->discount : 0)) / 100;
        if ($request->express_charge == '1') {
            $totalPrice = $grossPrice - $totalDiscount;
            $totalPriceDis = $totalPrice + ($totalPrice * 50) / 100;
        } else {
            $totalPriceDis = $grossPrice - $totalDiscount;
        }

        return [$totalPriceDis, $totalDiscount];
    }




    //     public function editOrder(Request $request, $id)
    //     {
    //         $order = Order::select("users.name", "users.mobile", "orders.*")
    //             ->join('users', 'users.id', '=', 'orders.user_id')
    //             ->findOrFail($id);

    //         $orderItems = OrderItem::where('order_id', $id)
    //             ->join('product_categories', 'product_categories.id', '=', 'order_items.product_category_id')
    //             ->join('operations', 'operations.id', '=', 'order_items.operation_id')
    //             ->select('order_items.*', 'product_categories.name as category_name', 'operations.name as service_name')
    //             ->get();
    //         // ->mapWithKeys(function ($item) {
    //         //     return [
    //         //         $item['product_item_id'] => [
    //         //             'id' => $item['id'],
    //         //             'category_id' => $item['product_category_id'],
    //         //             'service_id' => $item['operation_id'],
    //         //             'product_item_id' => $item['product_item_id'],
    //         //             'qty' => $item['quantity'],
    //         //             'unit_price' => $item['operation_price'],
    //         //             'category_name' => $item['category_name'],
    //         //             'service_name' => $item['service_name'],
    //         //             'Operations' => [
    //         //                 [
    //         //                     'service_id' => $item['operation_id'],
    //         //                     'unit_price' => $item['operation_price'],
    //         //                     'qty' => $item['quantity'],
    //         //                 ]
    //         //             ]
    //         //         ]
    //         //     ];
    //         // })->toArray();

    //         // $productItems = ProductItem::with('categories')->get();
    //         $productItems = ProductItem::with(['categories', 'categories.service'])->get();

    //         // $productItems = ProductItem::whereHas('categories', function ($query) use ($orderItems) {
    //         //     // Filter product items based on order items related to the specific order ID
    //         //     $query->whereIn('id', $orderItems->pluck('product_category_id')->unique());
    //         // })->with('categories')->get();

    //         $groupedProductItems = [];
    //         $others = [];

    //         foreach ($productItems as $productItem) {
    //             $uniqueCategories = [];
    //             $uniqueCategoriesV1 = [];

    //             // Collect unique categories for the current product
    //             foreach ($productItem->categories as $category) {
    //                 $categoryName = $category->name;
    //                 if (!in_array($categoryName, $uniqueCategoriesV1)) {
    //                     $uniqueCategories[$category->id] = $categoryName;
    //                     $uniqueCategoriesV1[] = $categoryName;
    //                 }
    //             }

    //             $categoryItems = [];
    //             foreach ($uniqueCategories as $categoryId => $categoryName) {
    //                 // Debugging: Check current product and category being processed
    //                 // dd('Processing Product ID: ' . $productItem->id . ', Product Name: ' . $productItem->name . ', Category: ' . $categoryName);

    //                 // Filter orderItems to get operations for the current product and category
    //                 $operations = collect($orderItems)->filter(function ($item) use ($productItem, $categoryName) {
    //                 //  dd('Checking Order Item - Product ID: ' . $item->product_id . ', Product Name: ' . $item->product_name . ', Category: ' . $item->category_name);
    //                     return $item->product_item_id == $productItem->id && $item->category_name == $categoryName;
    //                 })->map(function ($item) use ($productItem) {
    //                     return [
    //                         'product_name' => $productItem->name,
    //                         'category_name' => $item->category_name,
    //                         'category_id' => $item->product_category_id,
    //                         'service_id' => $item->operation_id,
    //                         'service_name' => $item->service_name,
    //                         'unit_price' => $item->operation_price,
    //                         'qty' => $item->quantity,
    //                     ];
    //                 })->values()->all();

    //                 // Debugging: Check if operations were found
    //                 // dd('Found Operations: ', $operations);

    //                 $categoryItems[] = [
    //                     'name' => $categoryName,
    //                     'Operations' => $operations
    //                 ];
    //             }

    //             // Build $others array
    //             $others[$productItem->id] = [
    //                 'id' => $productItem->id,
    //                 'name' => $productItem->name,
    //                 'Operations' => array_merge([], ...array_column($categoryItems, 'Operations'))
    //             ];

    //             // Get all operation data for the current product
    //             $operationData = $this->getAllOperationData($productItem->id, $categoryName, $others);

    //             // Group product items with their associated categories and operations
    //             $groupedProductItems[] = [
    //                 'product' => $productItem,
    //                 'image' => $productItem->image,
    //                 'CatItems' => $categoryItems,
    //                 'operationData' => $operationData
    //             ];
    //         }

    //         // Debugging: Check final grouped product items
    //     //    dd('Grouped Product Items: ', $groupedProductItems);


    //         // Debugging: Check final grouped product items
    //         // dd('Grouped Product Items: ', $groupedProductItems);


    //         //new code for operations
    //        // Prepare operationsArray for all product items and categories
    // $operationsArray = [];

    // foreach ($productItems as $productItem) {
    //     $productOperations = [];

    //     // Create a temporary array to group operations by category name
    //     $categoryOperationsMap = [];

    //     foreach ($productItem->categories as $category) {
    //         $service = $category->service;
    //         if ($service) {
    //             // Initialize category in map if not already present
    //             if (!isset($categoryOperationsMap[$category->name])) {
    //                 $categoryOperationsMap[$category->name] = [];
    //             }

    //             // Add operation to the corresponding category name
    //             $categoryOperationsMap[$category->name][] = [
    //                 'service_id' => $service->id ?? null,
    //                 'service_name' => $service->name ?? '',
    //                 'unit_price' => $category->price ?? 0, // Get price from ProductCategory
    //                 'qty' => 1, // Quantity is not provided in your structure, defaulting to 1
    //             ];
    //         }
    //     }

    //     // Convert the category operations map to the required structure
    //     foreach ($categoryOperationsMap as $categoryName => $operations) {
    //         $productOperations[] = [
    //             'category_name' => $categoryName,
    //             'operations' => $operations,
    //         ];
    //     }

    //     $operationsArray[] = [
    //         'product_name' => $productItem->name,
    //         'categories' => $productOperations,
    //     ];
    // }


    //     // dd($operationsArray);
    //         //end code


    //         // dd($groupedProductItems);
    //         $discounts = Discount::all();

    //         return view('admin.orderupdate', compact('groupedProductItems', 'discounts', 'order', 'orderItems', 'others','operationsArray'));
    //     }

    public function getServices(Request $request)
    {
        $item = $request->input('item');
        $type = $request->input('type');

        // Fetch the related product category
        $productCategory = ProductCategory::where('product_item_id', $item)
            ->where('id', $type)
            ->with('service')
            ->first();

        // Get the services associated with the product category
        $services = $productCategory ? $productCategory->service : [];
        // dd($services);

        return response()->json(['services' => $services]);
    }


    public function getPrice(Request $request)
    {
        $item = $request->input('item');
        $type = $request->input('type');
        $service = $request->input('service');

        // Fetch the price based on item, type, and service
        $productCategory = ProductCategory::where('product_item_id', $item)
            ->where('id', $type)
            ->where('operation_id', $service)
            ->first();

        $price = $productCategory ? $productCategory->price : null;

        return response()->json(['price' => $price]);
    }

    public function editOrder(Request $request, $id)
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
        $order = Order::select("users.name", "users.mobile", "orders.*")
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->findOrFail($id);

        $orderItems = OrderItem::where('order_id', $id)
            ->join('product_categories', 'product_categories.id', '=', 'order_items.product_category_id')
            ->join('operations', 'operations.id', '=', 'order_items.operation_id')
            ->select('order_items.*', 'product_categories.name as category_name', 'operations.name as service_name')
            ->get();
        // dd($orderItems);

        $productItems = ProductItem::with(['categories', 'categories.service'])->get();
        $services = Service::all();  // Assuming you have an Operation model to fetch services
        // dd($services);
        // dd($productItems);

        // Add other logic to group product items and categories

        // Prepare operationsArray for all product items and categories
        $operationsArray = [];
        foreach ($productItems as $productItem) {
            $productOperations = [];
            $categoryOperationsMap = [];

            foreach ($productItem->categories as $category) {
                $service = $category->service;
                if ($service) {
                    if (!isset($categoryOperationsMap[$category->name])) {
                        $categoryOperationsMap[$category->name] = [];
                    }

                    $categoryOperationsMap[$category->name][] = [
                        'service_id' => $service->id ?? null,
                        'service_name' => $service->name ?? '',
                        'unit_price' => $category->price ?? 0,
                        'qty' => 1,
                    ];
                }
            }

            foreach ($categoryOperationsMap as $categoryName => $operations) {
                $productOperations[] = [
                    'category_name' => $categoryName,
                    'operations' => $operations,
                ];
            }

            $operationsArray[] = [
                'product_name' => $productItem->name,
                'categories' => $productOperations,
            ];
        }

        $discounts = Discount::all();

        return view('admin.orderupdate', compact('discounts', 'order', 'orderItems', 'operationsArray', 'productItems', 'services'));
    }





    // public function getAllOperationData($pid, $pname, $others = [])
    // {
    //     $data = DB::table('operations')
    //         ->select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
    //         ->where([
    //             'pc.product_item_id' => $pid,
    //             'pc.name' => $pname,
    //         ])
    //         ->join('product_categories as pc', 'operations.id', '=', 'pc.operation_id')
    //         ->get();

    //     // Return the operation view with data and others
    //     // dd($data);
    //     return view('admin.operation.editoperationview', ['data' => $data, "others" => $others])->render();
    // }

    public function getAllOperationData($pid, $pname, $others = [])
    {
        $data = DB::table('operations')
            ->select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
            ->where([
                'pc.product_item_id' => $pid,
                'pc.name' => $pname,
            ])
            ->join('product_categories as pc', 'operations.id', '=', 'pc.operation_id')
            ->get();

        // dd($data);
        foreach ($data as &$operationData) {
            $operationData->isMatch = false;
            if (!empty($others[$operationData->pid]) && isset($others[$operationData->pid]['Operations'])) {
                foreach ($others[$operationData->pid]['Operations'] as $operation) {
                    if ($operation['service_id'] == $operationData->op_id) {
                        $operationData->isMatch = true;
                    }
                }
                // && $operation['category_id'] == $operationData->item_cat_id
            }
            // dd($operationData->isMatch);
            // dd(['operationData' => $operationData, 'others' => $others[$operationData->pid]['Operations']]);
        }
        // dd($others[$operationData->pid]['Operations']);
        // dd($others);
        // dd($data);
        return view('admin.operation.editoperationview', ['data' => $data, "others" => $others])->render();
    }



    public function getAllServiceData(Request $request)
    {
        $pId = $request->id;
        $pname = $request->name;
        $others = $request->others ?? [];
        // dd($others);
        return $this->getAllOperationData($pId, $pname, $others);
    }

    // public function getAllServiceData(Request $request)
    // {
    //     $operationName = $request->input('name');
    //     $PrdId = $request->input('id');
    //     $others = $request->input('others');

    //     // Fetch the operations data
    //     $operations = Service::where('name', $operationName)
    //         ->whereHas('productItem', function ($query) use ($PrdId) {
    //             $query->where('product_id', $PrdId);
    //         })
    //         ->get();

    //     // Prepare the response data
    //     $data = [];
    //     foreach ($operations as $operation) {
    //         $isMatch = false;
    //         if (!empty($others[$operation->productItem->id]) && isset($others[$operation->productItem->id]['Operations'])) {
    //             foreach ($others[$operation->productItem->id]['Operations'] as $op) {
    //                 if ($op['service_id'] == $operation->id && $op['category_id'] == $operation->item_cat_id) {
    //                     $isMatch = true;
    //                 }
    //             }
    //         }
    //         $data[] = [
    //             'pid' => $operation->productItem->id,
    //             'isMatch' => $isMatch
    //         ];
    //     }

    //     // Return JSON response
    //     return response()->json([
    //         'html' => view('partials.operations', compact('operations'))->render(),
    //         'data' => $data
    //     ]);
    // }





    // public function updateOrder(Request $request, $id)
    // {
    //     try {


    //         $order = Order::findOrFail($id);
    //         $existingOrderItems = OrderItem::where('order_id', $id)->get()->keyBy(function ($item) {
    //             return $item->product_item_id . '-' . $item->product_category_id . '-' . $item->operation_id;
    //         });
    //         $formattedItems = [];
    //         $excludeRemoveIds = [];
    //         $rIdIndex = 0;
    //         foreach ($request->items as $productId => $categories) {
    //             foreach ($categories as $categoryId => $services) {
    //                 foreach ($services as $serviceId => $details) {
    //                     $key = $productId . '-' . $categoryId . '-' . $serviceId;
    //                     if (!empty($existingOrderItems[$key]->id)) {
    //                         $excludeRemoveIds[$rIdIndex++] = $existingOrderItems[$key]->id;
    //                     }
    //                     $formattedItems[] = [
    //                         'id' => $existingOrderItems[$key]->id ?? null,
    //                         'product_id' => $productId,
    //                         'category_id' => $categoryId,
    //                         'service_id' => $serviceId,
    //                         'qty' => $details['qty'],
    //                         'unit_price' => $details['unit_price'],
    //                     ];
    //                 }
    //             }
    //         }
    //         //    dd($existingOrderItems->toArray(),$excludeRemoveIds,$formattedItems);
    //         foreach ($existingOrderItems as $existItem) {
    //             if (!in_array($existItem->id, $excludeRemoveIds)) {
    //                 OrderItem::where('id', $existItem->id)->delete();
    //             }
    //         }
    //         $client = DB::table('users')->where('mobile', $request->client_num)->first();
    //         if ($client) {
    //             $user = User::updated([
    //                 'name' => $request->name,
    //                 'mobile' => $request->client_num,
    //                 'role_id' => 2
    //             ]);
    //             $user_id = $client->id;
    //         } else {
    //             $user = User::create([
    //                 'name' => $request->name,
    //                 'mobile' => $request->client_num,
    //                 'role_id' => 2
    //             ]);
    //             $user_id = $user->id;
    //         }

    //         // Calculate discount
    //         $discountId = match ($request->discount) {
    //             '5' => 1,
    //             '10' => 2,
    //             '15' => 3,
    //             '20' => 4,
    //             default => 0
    //         };
    //         $operationId = explode('|', $request->categoryPriceItem);
    //         $service_id = !empty($operationId[0]) ? $operationId[0] : 1;
    //         $grossPrice = $request->gross_total;
    //         $totalDiscount = ($grossPrice * ($request->discount ?? 0)) / 100;
    //         $totalPriceDis = $grossPrice - $totalDiscount;
    //         if ($request->express_charge == '1') {
    //             $totalPriceDis += ($totalPriceDis * 50) / 100;
    //         }

    //         // Update the order
    //         $updateorder = $order->update([
    //             'user_id' => $user_id,
    //             'order_date' => $request->booking_date,
    //             'order_time' => $request->booking_time,
    //             'delivery_date' => $request->delivery_date,
    //             'delivery_time' => $request->delivery_time,
    //             'discount_id' => $discountId,
    //             'service_id' => $service_id,
    //             'total_qty' => $request->total_qty,
    //             'total_price' => $totalPriceDis,
    //         ]);


    //         $updatedItemIds = [];
    //         foreach ($formattedItems as $item) {
    //             if (isset($item['id'])) {
    //                 $orderItem = OrderItem::find($item['id']);
    //                 $orderItem->update([
    //                     'product_item_id' => $item['product_id'],
    //                     'product_category_id' => $item['category_id'],
    //                     'operation_id' => $item['service_id'],
    //                     'quantity' => $item['qty'],
    //                     'operation_price' => $item['unit_price'],
    //                     'price' => $item['qty'] * $item['unit_price'],
    //                 ]);
    //                 $updatedItemIds[] = $item['id'];
    //             } else {
    //                 $orderItem = $order->orderItems()->create([
    //                     'product_item_id' => $item['product_id'],
    //                     'product_category_id' => $item['category_id'],
    //                     'operation_id' => $item['service_id'],
    //                     'quantity' => $item['qty'],
    //                     'operation_price' => $item['unit_price'],
    //                     'price' => $item['qty'] * $item['unit_price'],
    //                 ]);
    //                 $updatedItemIds[] = $orderItem->id;
    //             }
    //         }

    //         // Delete removed items
    //         /*  foreach ($existingOrderItems as $itemId => $item) {
    //             if (!in_array($itemId, $updatedItemIds)) {
    //                 OrderItem::destroy($itemId);
    //             }
    //         } */

    //         return redirect()->route('viewOrder');
    //     } catch (\Exception $exception) {
    //         // dd($exception->getMessage());
    //         return redirect()->back()->with('error', $exception->getMessage());
    //     }
    // }
//new code
public function updateOrder(Request $request, $id)
{
    try {
        // Fetch the order and its items
        $order = Order::findOrFail($id);
        $existingOrderItems = OrderItem::where('order_id', $id)->get()->keyBy(function ($item) {
            return $item->product_item_id . '-' . $item->product_category_id . '-' . $item->operation_id;
        });

        $formattedItems = json_decode($request->input('order_items_data'), true);

        $updatedItemIds = [];

        // Format the incoming request data
        foreach ($formattedItems as $category) {
            $categoryId = $category['category'];

            foreach ($category['types'] as $type) {
                $typeId = $type['type'];

                foreach ($type['services'] as $service) {
                    $serviceId = $service['service'];
                    $qty = $service['quantity'];
                    $unitPrice = $service['price'];

                    $key = $categoryId . '-' . $typeId . '-' . $serviceId;
                    $existingItem = $existingOrderItems[$key] ?? null;

                    if ($existingItem) {
                        // Update existing item
                        $existingItem->update([
                            'quantity' => $qty,
                            'operation_price' => $unitPrice,
                            'price' => $qty * $unitPrice,
                        ]);
                        $updatedItemIds[] = $existingItem->id;
                    } else {
                        // Create new item
                        $newItem = $order->orderItems()->create([
                            'product_item_id' => $categoryId,
                            'product_category_id' => $typeId,
                            'operation_id' => $serviceId,
                            'quantity' => $qty,
                            'operation_price' => $unitPrice,
                            'price' => $qty * $unitPrice,
                            'status' => 'pending'
                        ]);
                        $updatedItemIds[] = $newItem->id;
                    }
                }
            }
        }

        // Delete items that are no longer in the order
        foreach ($existingOrderItems as $existItem) {
            $key = $existItem->product_item_id . '-' . $existItem->product_category_id . '-' . $existItem->operation_id;
            if (!in_array($existItem->id, $updatedItemIds)) {
                $existItem->delete();
            }
        }

        // Handle client user creation or updating
        $client = DB::table('users')->where('mobile', $request->client_num)->first();
        // dd($client);
        if ($client) {
            $user = User::where('id', $client->id)->update([
                'name' => $request->client_name,
                'mobile' => $request->client_num,
                'role_id' => 2
            ]);
            $user_id = $client->id;
        } else {
            $user = User::create([
                'name' => $request->client_name,
                'mobile' => $request->client_num,
                'role_id' => 2
            ]);
            $user_id = $user->id;
        }

        // Calculate discount and total price
        $discountId = match ($request->discount) {
            '5' => 1,
            '10' => 2,
            '15' => 3,
            '20' => 4,
            default => 0
        };

        // Check if discountId is valid, otherwise set to null
        if (!DB::table('discounts')->where('id', $discountId)->exists()) {
            $discountId = null;
        }

        $grossPrice = $request->gross_total;
        $totalDiscount = ($grossPrice * ($request->discount ?? 0)) / 100;
        $totalPriceDis = $grossPrice - $totalDiscount;
        if ($request->express_charge == '1') {
            $totalPriceDis += ($totalPriceDis * 50) / 100;
        }

        // Update the order details
        $order->update([
            'user_id' => $user_id,
            'order_date' => $request->booking_date,
            'order_time' => $request->booking_time,
            'delivery_date' => $request->delivery_date,
            'delivery_time' => $request->delivery_time,
            'discount_id' => $discountId,
            'total_qty' => $request->total_qty,
            'total_price' => $totalPriceDis,
            'status' => 'pending'
        ]);

        // Prepare SMS message
        $message = sprintf(
            "Dear %s, your order (ID: %s) of %s is Updated. Estimated delivery: %s. Thank you. Mega Solutions Dry cleaning",
            $request->client_name,
            $order->id,
            $order->total_price,
            $request->delivery_date
        );

        // Format the client's phone number
        $clientPhoneNumber = '+91' . $request->client_num;

        // Attempt to send SMS and handle any exceptions
        try {
            $this->smsService->sendSms($clientPhoneNumber, $message);
        } catch (\Exception $e) {
            // Log the SMS error and continue with order update
            Log::error('Error sending SMS: ' . $e->getMessage());
        }

        return redirect()->route('viewOrder')->with('success', 'Order updated successfully.');
    } catch (\Exception $exception) {
        dd([
            'message' => $exception->getMessage(),
            'line' => $exception->getLine(),
        ]);
        return redirect()->back()->with('error', $exception->getMessage());
    }
}

//end new code
//start prev//
    // public function updateOrder(Request $request, $id)
    // {
    //     // dd('Request Data: ', $request->all());
    //     try {
    //         // Fetch the order and its items
    //         $order = Order::findOrFail($id);
    //         $existingOrderItems = OrderItem::where('order_id', $id)->get()->keyBy(function ($item) {
    //             return $item->product_item_id . '-' . $item->product_category_id . '-' . $item->operation_id;
    //         });

    //         $formattedItems = json_decode($request->input('order_items_data'), true);
    //         // dd($formattedItems);
    //         $excludeRemoveIds = [];
    //         $updatedItemIds = [];
    //         $rIdIndex = 0;
    //         // dd("hello");

    //         // Format the incoming request data
    //         foreach ($formattedItems as $category) {
    //             $categoryId = $category['category'];

    //             foreach ($category['types'] as $type) {
    //                 $typeId = $type['type'];

    //                 foreach ($type['services'] as $service) {
    //                     $serviceId = $service['service'];
    //                     $qty = $service['quantity'];
    //                     $unitPrice = $service['price'];

    //                     $key = $categoryId . '-' . $typeId . '-' . $serviceId;
    //                     $existingItem = $existingOrderItems[$key] ?? null;

    //                     if ($existingItem) {
    //                         // Update existing item
    //                         $existingItem->update([
    //                             'quantity' => $qty,
    //                             'operation_price' => $unitPrice,
    //                             'price' => $qty * $unitPrice,
    //                         ]);
    //                         $updatedItemIds[] = $existingItem->id;
    //                     } else {
    //                         // Create new item
    //                         $newItem = $order->orderItems()->create([
    //                             'product_item_id' => $categoryId, // Assuming this is product_item_id
    //                             'product_category_id' => $typeId, // Assuming this is product_category_id
    //                             'operation_id' => $serviceId, // Assuming this is operation_id
    //                             'quantity' => $qty,
    //                             'operation_price' => $unitPrice,
    //                             'price' => $qty * $unitPrice,
    //                         ]);
    //                         $updatedItemIds[] = $newItem->id;
    //                     }

    //                     $excludeRemoveIds[] = $key;
    //                 }
    //             }
    //         }
    //         // dd("hello");
    //         dd($formattedItems);

    //        // Delete items that are no longer in the order
    //     foreach ($existingOrderItems as $existItem) {
    //         $key = $existItem->product_item_id . '-' . $existItem->product_category_id . '-' . $existItem->operation_id;
    //         if (!in_array($existItem->id, $updatedItemIds)) {
    //             $existItem->delete();
    //         }
    //     }

    //         // Handle client user creation or updating
    //         $client = DB::table('users')->where('mobile', $request->client_num)->first();
    //         if ($client) {
    //             $user = User::where('id', $client->id)->update([
    //                 'name' => $request->client_name,
    //                 'mobile' => $request->client_num,
    //                 'role_id' => 2
    //             ]);
    //             $user_id = $client->id;
    //         } else {
    //             $user = User::create([
    //                 'name' => $request->client_name,
    //                 'mobile' => $request->client_num,
    //                 'role_id' => 2
    //             ]);
    //             $user_id = $user->id;
    //         }

    //         // Calculate discount and total price
    //         $discountId = match ($request->discount) {
    //             '5' => 1,
    //             '10' => 2,
    //             '15' => 3,
    //             '20' => 4,
    //             default => 0
    //         };
    //         $operationId = explode('|', $request->categoryPriceItem);
    //         $service_id = !empty($operationId[0]) ? $operationId[0] : 1;
    //         $grossPrice = $request->gross_total;
    //         $totalDiscount = ($grossPrice * ($request->discount ?? 0)) / 100;
    //         $totalPriceDis = $grossPrice - $totalDiscount;
    //         if ($request->express_charge == '1') {
    //             $totalPriceDis += ($totalPriceDis * 50) / 100;
    //         }

    //         // Update the order details
    //         $order->update([
    //             'user_id' => $user_id,
    //             'order_date' => $request->booking_date,
    //             'order_time' => $request->booking_time,
    //             'delivery_date' => $request->delivery_date,
    //             'delivery_time' => $request->delivery_time,
    //             'discount_id' => $discountId,
    //             'service_id' => $service_id,
    //             'total_qty' => $request->total_qty,
    //             'total_price' => $totalPriceDis,
    //         ]);

    //         // Update or create order items
    //         $updatedItemIds = [];
    //         foreach ($formattedItems as $item) {
    //             if (isset($item['id'])) {
    //                 $orderItem = OrderItem::find($item['id']);
    //                 $orderItem->update([
    //                     'product_item_id' => $item['category'],
    //                     'product_category_id' => $item['types'],
    //                     'operation_id' => $item['services'],
    //                     'quantity' => $item['qty'],
    //                     'operation_price' => $item['price'],
    //                     'price' => $item['qty'] * $item['price'],
    //                 ]);
    //                 $updatedItemIds[] = $item['id'];
    //             } else {
    //                 $orderItem = $order->orderItems()->create([
    //                     'product_item_id' => $item['category'],
    //                     'product_category_id' => $item['types'],
    //                     'operation_id' => $item['services'],
    //                     'quantity' => $item['qty'],
    //                     'operation_price' => $item['price'],
    //                     'price' => $item['qty'] * $item['price'],
    //                 ]);
    //                 $updatedItemIds[] = $orderItem->id;
    //             }
    //         }

    //         // dd("hello guys", $updatedItemIds[]);
    //         return redirect()->route('viewOrder')->with('success', 'Order updated successfully.');
    //     } catch (\Exception $exception) {
    //         dd([
    //             'message' => $exception->getMessage(),
    //             'line' => $exception->getLine(),
    //         ]);
    //         return redirect()->back()->with('error', $exception->getMessage());
    //     }
    // }


//##### previous code here

    // public function updateOrder(Request $request, $id)
    // {
    //     try {
    //         // Fetch the order and its items
    //         $order = Order::findOrFail($id);
    //         $existingOrderItems = OrderItem::where('order_id', $id)->get()->keyBy(function ($item) {
    //             return $item->product_item_id . '-' . $item->product_category_id . '-' . $item->operation_id;
    //         });

    //         $formattedItems = [];
    //         $excludeRemoveIds = [];
    //         $rIdIndex = 0;
    //         // dd("hello");

    //         // Format the incoming request data
    //         if (is_array($request->items)) {
    //             foreach ($request->items as $productId => $categories) {
    //                 if (is_array($categories)) {
    //                     foreach ($categories as $categoryId => $services) {
    //                         if (is_array($services)) {
    //                             foreach ($services as $serviceId => $details) {
    //                                 $key = $productId . '-' . $categoryId . '-' . $serviceId;
    //                                 if (!empty($existingOrderItems[$key]->id)) {
    //                                     $excludeRemoveIds[] = $existingOrderItems[$key]->id;
    //                                 }
    //                                 $formattedItems[] = [
    //                                     'id' => $existingOrderItems[$key]->id ?? null,
    //                                     'product_id' => $productId,
    //                                     'category_id' => $categoryId,
    //                                     'service_id' => $serviceId,
    //                                     'qty' => $details['qty'],
    //                                     'unit_price' => $details['unit_price'],
    //                                 ];
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         } else {
    //             throw new \Exception("Request items must be an array.");
    //         }
    //         // dd("hello");
    //         // dd($formattedItems);

    //         // Delete items that are no longer in the order
    //         foreach ($existingOrderItems as $existItem) {
    //             if (!in_array($existItem->id, $excludeRemoveIds)) {
    //                 OrderItem::where('id', $existItem->id)->delete();
    //             }
    //         }

    //         // Handle client user creation or updating
    //         $client = DB::table('users')->where('mobile', $request->client_num)->first();
    //         if ($client) {
    //             $user = User::where('id', $client->id)->update([
    //                 'name' => $request->name,
    //                 'mobile' => $request->client_num,
    //                 'role_id' => 2
    //             ]);
    //             $user_id = $client->id;
    //         } else {
    //             $user = User::create([
    //                 'name' => $request->name,
    //                 'mobile' => $request->client_num,
    //                 'role_id' => 2
    //             ]);
    //             $user_id = $user->id;
    //         }

    //         // Calculate discount and total price
    //         $discountId = match ($request->discount) {
    //             '5' => 1,
    //             '10' => 2,
    //             '15' => 3,
    //             '20' => 4,
    //             default => 0
    //         };
    //         $operationId = explode('|', $request->categoryPriceItem);
    //         $service_id = !empty($operationId[0]) ? $operationId[0] : 1;
    //         $grossPrice = $request->gross_total;
    //         $totalDiscount = ($grossPrice * ($request->discount ?? 0)) / 100;
    //         $totalPriceDis = $grossPrice - $totalDiscount;
    //         if ($request->express_charge == '1') {
    //             $totalPriceDis += ($totalPriceDis * 50) / 100;
    //         }

    //         // Update the order details
    //         $order->update([
    //             'user_id' => $user_id,
    //             'order_date' => $request->booking_date,
    //             'order_time' => $request->booking_time,
    //             'delivery_date' => $request->delivery_date,
    //             'delivery_time' => $request->delivery_time,
    //             'discount_id' => $discountId,
    //             'service_id' => $service_id,
    //             'total_qty' => $request->total_qty,
    //             'total_price' => $totalPriceDis,
    //         ]);

    //         // Update or create order items
    //         $updatedItemIds = [];
    //         foreach ($formattedItems as $item) {
    //             if (isset($item['id'])) {
    //                 $orderItem = OrderItem::find($item['id']);
    //                 $orderItem->update([
    //                     'product_item_id' => $item['product_id'],
    //                     'product_category_id' => $item['category_id'],
    //                     'operation_id' => $item['service_id'],
    //                     'quantity' => $item['qty'],
    //                     'operation_price' => $item['unit_price'],
    //                     'price' => $item['qty'] * $item['unit_price'],
    //                 ]);
    //                 $updatedItemIds[] = $item['id'];
    //             } else {
    //                 $orderItem = $order->orderItems()->create([
    //                     'product_item_id' => $item['product_id'],
    //                     'product_category_id' => $item['category_id'],
    //                     'operation_id' => $item['service_id'],
    //                     'quantity' => $item['qty'],
    //                     'operation_price' => $item['unit_price'],
    //                     'price' => $item['qty'] * $item['unit_price'],
    //                 ]);
    //                 $updatedItemIds[] = $orderItem->id;
    //             }
    //         }

    //         // dd("hello guys", $updatedItemIds[]);
    //         return redirect()->route('viewOrder');
    //     } catch (\Exception $exception) {
    //         dd([
    //             'message' => $exception->getMessage(),
    //             'line' => $exception->getLine(),
    //         ]);
    //         return redirect()->back()->with('error', $exception->getMessage());
    //     }
    // }


    public function OrderDetail(Request $request, $orderId)
    {
        try {
            $orders = Order::with('orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'paymentDetail')
                ->findOrFail($orderId);
            $subTotalAmount = 0;
            foreach ($orders->orderItems as $orderItem) {
                $subTotalAmount += $orderItem->quantity * $orderItem->operation_price;
            }
            // $discountPercentage = $orders->discounts->amount;
            // $discountAmount = ($discountPercentage / 100) * $subTotalAmount;
            $discountAmount = 0;
            if ($orders->discounts!== null) {
                $discountPercentage = $orders->discounts->amount;
                $discountAmount = ($discountPercentage / 100) * $subTotalAmount;
            }
            $totalAmount = $subTotalAmount - $discountAmount;

            // Add debug line to see payment status
            // dd($orders->paymentDetail->status, $orders->status);

            return view('admin.OrderDetail', ['orders' => $orders, 'subTotalAmount' => $subTotalAmount, 'discountAmount' => $discountAmount, 'totalAmount' => $totalAmount,'totalAmount']);
        } catch (Throwable $throwable) {
            dd($throwable->getMessage());
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    // public function viewOrder(Request $request)
    // {
    //     try {
    //         $orders = Order::select('orders.id','users.name','users.mobile','orders.total_qty')
    //         ->join('users','users.id','=','orders.user_id')
    //         ->where('orders.is_deleted',0)
    //         ->orderBy('orders.created_at', 'desc')
    //         ->get();
    //         return view('admin.viewOrder', ['orders' => $orders]);
    //     } catch (Throwable $throwable) {
    //         dd($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
    //     }
    // }

    public function viewOrder(Request $request)
    {
        try {
            $orders = Order::select('orders.id', 'users.name','orders.invoice_number', 'users.mobile', 'orders.total_qty', 'payment_details.status as payment_status', DB::raw('MAX(order_items.status) as item_status'))
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->leftJoin('payment_details', 'payment_details.order_id', '=', 'orders.id')
                ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.is_deleted', 0)
                ->groupBy('orders.id', 'users.name','orders.invoice_number', 'users.mobile', 'orders.total_qty', 'payment_details.status')
                ->orderBy('orders.created_at', 'desc')
                // ->get();
                ->paginate(10);

            return view('admin.viewOrder', ['orders' => $orders]);
        } catch (Throwable $throwable) {
            dd($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
        }
    }




    public function deleteOrder($id)
    {
        try {
            DB::table('orders')->where('id', '=', $id)->update(['is_deleted' => 1]);
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }

    public function sendWhMessage(Request $request, WhatsAppService $whatsAppService, $orderId)
    {
        try {
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId); // Assuming 'Order' is your Eloquent model

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            $user = $order->user;
            $name = $user->name;
            $tracking_number = $order->invoice_number;
            $delivery_date = $order->delivery_date;
            $order_id = $order->id;

            // Generate the PDF from the 'admin.pdf' view
            $pdf = PDF::loadView('admin.pdf', compact('order', 'subTotalAmount', 'discountAmount', 'totalAmount', 'discountPercentage'));

            // Define the path to save the PDF
            $pdfPath = public_path("invoices/invoice-{$order_id}.receipt.pdf");

            // Save the PDF to the specified path
            $pdf->save($pdfPath);

            // Create a URL for the PDF file
            $pdfUrl = "https://dryclean.microlent.com//public/invoices/invoice-4.receipt.pdf";

            // Send the WhatsApp message with the PDF URL
            $response = $whatsAppService->sendMessage($name, $tracking_number, $delivery_date, $pdfUrl);

            // Delete the PDF file after sending the message
            if ($response) {
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }

            return back()->with('success', 'Order placed successfully and WhatsApp message sent.');
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return back()->with('error', $throwable->getMessage());
        }
    }


    //for download locally
    public function downloadReceipt(Request $request, $orderId)
    {
        try {
            // Fetch the latest order with related order items, user, and discounts
            // $order = Order::with([
            //     'orderItems.productCategory',
            //     'orderItems.productItem',
            //     'orderItems.opertions',
            //     'user',
            //     'discounts'
            // ])->latest()->firstOrFail();
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            $pdf = PDF::loadView('admin.pdf', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage // Include discountPercentage in the view data
            ]);

            return $pdf->download("invoice-{$order->id}.receipt.pdf");
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function downloadInvoice(Request $request, $orderId)
    {
        try {
            // Fetch the latest order with related order items, user, and discounts
            // $order = Order::with([
            //     'orderItems.productCategory',
            //     'orderItems.productItem',
            //     'orderItems.opertions',
            //     'user',
            //     'discounts'
            // ])->latest()->firstOrFail();
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            $pdf = PDF::loadView('admin.invoiceDetail', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage // Include discountPercentage in the view data
            ]);

            return $pdf->download("invoice-{$order->id}.invoice.pdf");
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }


    public function PrintReceipt(Request $request, $orderId)
    {
        try {
            // Fetch the order with related order items and user (customer) information
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;
            //dd($order->toArray());
            // Pass data to the view
            return view('admin.receipt', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage'=> $discountPercentage
            ]);
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function PrintInvoice(Request $request, $orderId)
    {
        try {
            // Fetch the order with related order items and user (customer) information
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;
            //dd($order->toArray());
            // Pass data to the view
            return view('admin.invoicePdf', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage'=> $discountPercentage
            ]);
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function tagList(Request $request, $orderId)
    {
        try {
            // Fetch the order with related order items and user (customer) information
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            return view('admin.tagslist', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount
            ]);
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    public function printTaglist(Request $request, $orderId)
    {
        try {
            // Fetch the order with related order items and user (customer) information
            $order = Order::with(['orderItems.productCategory', 'orderItems.productItem', 'orderItems.opertions', 'user', 'discounts'])
                ->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Pass data to the view
            $pdf = PDF::loadView('admin.downloadTagslist', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage // Include discountPercentage in the view data
            ]);

            // Return the generated PDF for download
            return $pdf->stream("taglist-{$order->id}.pdf");
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
}
