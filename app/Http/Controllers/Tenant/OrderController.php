<?php

namespace App\Http\Controllers\Tenant;

// Importing necessary classes and models
use App\Http\Controllers\Controller; // Base controller
use App\Models\{ // Grouped imports for models
    Order,
    User,
    ProductItem,
    ProductCategory,
    PaymentDetail,
    Discount,
    OrderItem,
    Service,
    Tenant,
    Operations
};

// Importing necessary services and facades
use Illuminate\Http\Request; // Handling HTTP requests
use Barryvdh\DomPDF\Facade\Pdf; // PDF generation using DomPDF
use App\Services\WhatsAppService; // Custom WhatsApp service
use Illuminate\Support\Facades\{ // Grouped imports for facades
    Session,
    DB,
    Log,
    Validator,
    Auth
};
use Carbon\Carbon; // Date and time manipulation
use Throwable; // Exception handling
use App\Services\SmsService; // Custom SMS service


class OrderController extends Controller
{

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Generates a random string of the specified length.
     *
     * @param int $length Length of the random string to generate. Default is 6.
     * @return string
     */
    private function generateRandomString($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Generates an array of time slots for appointment scheduling.
     *
     * @return array
     */
    private function generateTimeSlots() {
        $times = [];
        $timesingle = [];
        $hours = range(9, 19); // Hours from 9 AM to 8 PM

        foreach ($hours as $hour) {
            // Format start time
            if ($hour < 12) {
                $start_time = sprintf('%d:00 AM', $hour);
            } elseif ($hour == 12) {
                $start_time = '12:00 PM';
            } else {
                $start_time = sprintf('%d:00 PM', $hour - 12);
            }

            // Calculate next hour for end time
            $next_hour = $hour + 1;
            if ($next_hour < 12) {
                $end_time = sprintf('%d:00 AM', $next_hour);
            } elseif ($next_hour == 12) {
                $end_time = '12:00 PM';
            } else {
                $end_time = sprintf('%d:00 PM', $next_hour - 12);
            }

            // Add to the times array
            $times[] = [
                'start' => $start_time,
                'end' => $end_time,
                'range' => sprintf('%s - %s', $start_time, $end_time)
            ];

            // Add to the timesingle array
            $timesingle[] = $start_time;
        }

        return [
            'time_ranges' => $times,
            'single_times' => $timesingle
        ];
    }



    /**
     * Sends an SMS notification using the provided payload.
     *
     * @param string $payload JSON-encoded payload for the SMS API
     * @return void
     */
    private function sendSmsNotification($payload)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://control.msg91.com/api/v5/flow',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'authkey: 426794Akjeezy8u669e32f2P1',
                'content-type: application/json',
                'Cookie: PHPSESSID=kgm8ohaofmr3v04i9gruu0kjs6'
            ],
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('SMS sending failed: ' . curl_error($curl));
        } else {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            Log::info("SMS sent successfully. HTTP Status Code: $http_code. Response: $response");
        }

        curl_close($curl);
    }

    /**
     * Handles adding a new order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addOrder(Request $request)
    {
        try {
            // Validate and retrieve request data
            $validatedData = $request->validate([
                'client_num' => 'required|numeric',
                'client_name' => 'required|min:2|max:20',
                'booking_date' => 'required|date',
                'booking_time' => 'required|date_format:H:i',
                'delivery_date' => 'required|date',
                'delivery_time' => 'required',
                'discount' => 'required',
                'total_qty' => 'required',
            ]);

            // Combine delivery time and period, then convert to 24-hour format
            $combinedDeliveryTime = $validatedData['delivery_time'];
            $deliveryTime24Hour = Carbon::createFromFormat('g:i A', $combinedDeliveryTime)->format('H:i:s');

            // Retrieve or create client
            $client = User::where('mobile', $validatedData['client_num'])->first();
            $user_id = $client ? $client->id : User::create([
                'name' => $validatedData['client_name'],
                'mobile' => $validatedData['client_num'],
                'role_id' => 2
            ])->id;

            // Determine discount ID
            $discountId = $this->getDiscountId($request->discount);

            // Calculate total price with discount and optional express charge
            list($totalPriceDis, $totalDiscount) = $this->calculateTotalPrice($request);

            // Create the order and save in database
            $order = Order::create([
                'invoice_number' => '',
                'user_id' => $user_id,
                'order_date' => $validatedData['booking_date'],
                'order_time' => $validatedData['booking_time'],
                'delivery_date' => $validatedData['delivery_date'],
                'delivery_time' => $deliveryTime24Hour,
                'discount_id' => $discountId,
                'service_id' => null,
                'status' => 'pending',
                'total_qty' => $validatedData['total_qty'],
                'total_price' => $totalPriceDis,
                'express_charge' => $request->express_charge
            ]);

            // Generate and save order number
            if ($order) {
                $orderNumber = 'ORD-' . $this->generateRandomString();
                $order->order_number = $orderNumber;
                $order->save();
            }

            // Insert order items
            $orderItemsData = json_decode($request->input('order_items_add_data'), true);
            foreach ($orderItemsData as $categoryData) {
                foreach ($categoryData['types'] as $typeData) {
                    foreach ($typeData['services'] as $serviceData) {
                        $order->orderItems()->create([
                            'order_id' => $order->id,
                            'product_item_id' => $categoryData['category'],
                            'product_category_id' => $typeData['type'],
                            'operation_id' => $serviceData['service'],
                            'quantity' => $serviceData['quantity'],
                            'operation_price' => $serviceData['price'],
                            'price' => $serviceData['quantity'] * $serviceData['price'],
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
                'paid_amount' => 0,
                'status' => 'Due',
                'payment_type' => null
            ]);

            // Prepare and send SMS notification
            $clientPhoneNumber = '91' . $validatedData['client_num'];
            $message = $orderNumber . ' of amount ' . $totalPriceDis;
            $payload = json_encode([
                "template_id" => "669e364ad6fc052bf21c7312",
                "recipients" => [
                    [
                        "mobiles" => $clientPhoneNumber,
                        "ordernumber" => $message,
                        "name" => $validatedData['client_name'],
                    ]
                ]
            ]);

            //$this->sendSmsNotification($payload);

            return redirect()->route('viewOrder');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            // Log exception and provide feedback
            Log::error('Order creation failed: ' . $exception->getMessage());
            return back()->withErrors($exception->getMessage())->withInput();
        }
    }


    /**
     * Handles updating an existing order.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id Order ID
     * @return \Illuminate\Http\RedirectResponse
     */
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

            // Iterate through the incoming request data and process items
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
                if (!in_array($existItem->id, $updatedItemIds)) {
                    $existItem->delete();
                }
            }

            // Calculate discount and total price
            $discountId = $this->getDiscountId($request->discount);

            $grossPrice = $request->gross_total;
            $totalDiscount = ($grossPrice * ($request->discount ?? 0)) / 100;
            $totalPriceDis = $grossPrice - $totalDiscount;
            if ($request->express_charge == '1') {
                $totalPriceDis += ($totalPriceDis * 50) / 100;
            }

            // Combine delivery time and period, then convert to 24-hour format
            $combinedDeliveryTime = $request->delivery_time;
            $deliveryTime24Hour = Carbon::createFromFormat('g:i A', $combinedDeliveryTime)->format('H:i:s');

            // Update the order details
            $order->update([
                'order_date' => $request->booking_date,
                'order_time' => $request->booking_time,
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $deliveryTime24Hour,
                'discount_id' => $discountId,
                'total_qty' => $request->total_qty,
                'total_price' => $totalPriceDis,
                'status' => 'pending'
            ]);

            // Prepare and send SMS notification
            $clientPhoneNumber = '+91' . $request->client_num;
            $message = $order->order_number . ' of amount ' . $totalPriceDis;
            $payload = json_encode([
                "template_id" => "669e3596d6fc0569d040c232",
                "recipients" => [
                    [
                        "mobiles" => $clientPhoneNumber,
                        "ordernumber" => $message,
                        "name" => $request->client_name,
                    ]
                ]
            ]);

            //$this->sendSmsNotification($payload);

            return redirect()->route('viewOrder')->with('success', 'Order updated successfully.');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            // Log and display exception details
            Log::error('Order update failed: ' . $exception->getMessage());
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    /**
     * Displays the Edit Order page with relevant data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tenantId = tenant('id');

        // Retrieve tenant and subscription details
        $tenant = Tenant::where('tenants.id', $tenantId)
                        ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                        ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
                        ->first();

        // Check if tenant exists and is active
        if (!$tenant || $tenant->is_active == 0 || !(Carbon::now()->between($tenant->starting_date, $tenant->end_date))) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        }

        // Retrieve all product items with their categories and services
        $productItems = ProductItem::with(['categories', 'categories.service'])->get();
        $groupedProductItems = [];

        // Group product items with unique categories and related details
        foreach ($productItems as $productItem) {
            $uniqueCategories = $productItem->categories->pluck('name')->unique();

            $firstCategory = $productItem->categories->first();
            $operationId = $firstCategory->operation_id ?? null;
            $price = $firstCategory->price ?? null;

            $groupedProductItems[] = [
                'product_item' => $productItem,
                'unique_categories' => $uniqueCategories,
                'operation_id' => $operationId,
                'price' => $price,
            ];
        }

        // Retrieve all discounts and services
        $discounts = Discount::all();
        $services = Service::all();
        $timeSlots = $this->generateTimeSlots();

        // Return the view with the gathered data
        return view('admin.EditOrder', compact('groupedProductItems', 'discounts', 'services', 'productItems', 'timeSlots'));
    }

    public function getOperationData($pid, $pname, $others = [])
    {
        // Get the current tenant ID
        $tenantId = tenant('id');

        // Retrieve the tenant and subscription details
        $tenant = Tenant::where('tenants.id', $tenantId)
                        ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                        ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
                        ->first();

        // Check if tenant exists and is active
        if (!$tenant || $tenant->is_active == 0 || !($tenant->starting_date <= Carbon::now()->format("Y-m-d") && $tenant->end_date >= Carbon::now()->format("Y-m-d"))) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        }

        // Retrieve operation data based on provided product ID and name
        $data = Operations::select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
            ->where([
                'pc.product_item_id' => $pid,
                'pc.name' => $pname,
            ])
            ->join('product_categories as pc', 'operations.id', '=', 'pc.operation_id')
            ->get();

        // Return the operation view with data and additional parameters
        return view('admin.operation.operationview', ['data' => $data, "others" => $others])->render();
    }

    public function getServiceData(Request $request)
    {
        // Retrieve and validate tenant information
        $tenantId = tenant('id');
        $tenant = Tenant::where('tenants.id', $tenantId)
                        ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
                        ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
                        ->first();

        if (!$tenant || $tenant->is_active == 0 || !($tenant->starting_date <= Carbon::now()->format("Y-m-d") && $tenant->end_date >= Carbon::now()->format("Y-m-d"))) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
        }

        // Retrieve parameters from request and call getOperationData
        $pId = $request->id;
        $pname = $request->name;
        $others = $request->others ?? [];
        return $this->getOperationData($pId, $pname, $others);
    }

    public function fetchClientName(Request $request)
    {
        try {
            // Validate request input
            $request->validate([
                'client_num' => 'required|numeric|digits:10',
            ]);

            // Find the user by mobile number
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
            // Handle and log exception
            return response()->json('error', 'Something Went Wrong.');
        }
    }



    private function getDiscountId($discount)
    {
        // Map discount values to discount IDs
        $discountMapping = [
            '5' => 1,
            '10' => 2,
            '15' => 3,
            '20' => 4
        ];
        return $discountMapping[$discount] ?? null;
    }

    private function calculateTotalPrice(Request $request)
    {
        $grossPrice = $request->gross_total;
        $totalDiscount = ($grossPrice * ($request->discount ? $request->discount : 0)) / 100;
        $totalPrice = $grossPrice - $totalDiscount;

        if ($request->express_charge == '1') {
            $totalPrice += ($totalPrice * 50) / 100; // Add express charge
        }

        return [$totalPrice, $totalDiscount];
    }


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
            // Convert the delivery time to 12-hour format
        $deliveryTime = Carbon::parse($order->delivery_time);
        $ditime = $deliveryTime->format('g:i A');

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
        $timeSlots = $this->generateTimeSlots();

        return view('admin.orderupdate', compact('discounts', 'order', 'orderItems', 'operationsArray', 'productItems', 'services', 'ditime','timeSlots'));
    }

    public function getAllOperationData($pid, $pname, $others = [])
    {
        $data = Operations::select('operations.id as op_id', 'operations.name as op_name', 'pc.price', 'pc.id as item_cat_id', 'pc.product_item_id as pid')
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
            }
        }
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

    public function viewOrder(Request $request)
    {
        try {

            $orders = Order::select(
                        'orders.id',
                        'orders.order_number',
                        'orders.total_qty',
                        'payment_details.status as payment_status',
                        DB::raw('users.name as name'),
                        DB::raw('users.mobile as mobile'),
                        DB::raw('(SELECT MAX(order_items.status) FROM order_items WHERE order_items.order_id = orders.id) as item_status')
                    )
                    ->where('orders.is_deleted', '!=', 1)
                    ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                    ->join('payment_details', 'payment_details.order_id', '=', 'orders.id')
                    ->distinct()
                    ->orderBy('orders.id', 'desc')
                    ->paginate(10);


                return view('admin.viewOrder', ['orders' => $orders]);
        } catch (Throwable $throwable) {
            dd($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
        }
    }




    public function deleteOrder($id)
    {
        try {
            Order::where('id', '=', $id)->update(['is_deleted' => 1]);
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

    public function RecieptPrint(Request $request, $orderId)
    {
        try {
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
            return $pdf->stream("invoice-{$order->id}.receipt.pdf");
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

    public function InvoicePrint(Request $request, $orderId)
    {
        try {
            // Fetch the latest order with related order items, user, and discounts
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

             return $pdf->stream("invoice-{$order->id}.invoice.pdf");
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
            $order = Order::with([
                'orderItems.productCategory',
                'orderItems.productItem',
                'orderItems.opertions',
                'user',
                'discounts'
            ])->findOrFail($orderId);

            // Calculate the subtotal amount
            $subTotalAmount = $order->orderItems->sum(function ($orderItem) {
                return $orderItem->quantity * $orderItem->operation_price;
            });

            // Calculate the discount amount
            $discountPercentage = $order->discounts->amount ?? 0; // Default to 0 if no discount
            $discountAmount = ($discountPercentage / 100) * $subTotalAmount;

            // Calculate the total amount
            $totalAmount = $subTotalAmount - $discountAmount;

            // Define custom paper size (2x4 inches in points)
            $customPaper = [0, 0, 144, 288]; // 2x4 inches in points

            // Pass data to the view
            $pdf = PDF::loadView('admin.downloadTagslist', [
                'order' => $order,
                'subTotalAmount' => $subTotalAmount,
                'discountAmount' => $discountAmount,
                'totalAmount' => $totalAmount,
                'discountPercentage' => $discountPercentage // Include discountPercentage in the view data
            ])->setPaper($customPaper, 'portrait');

            // Return the generated PDF for download
            return $pdf->stream("taglist-{$order->id}.pdf");
        } catch (Throwable $throwable) {
            // Handle the exception and redirect with an error message
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

}
