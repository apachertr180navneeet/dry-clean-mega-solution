<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tenant;
use Throwable;
use Illuminate\Support\Facades\{ // Grouped imports for facades
    Session,
    DB,
    Log,
    Validator,
    Auth
};
use Carbon\Carbon; // Date and time manipulation

class InvoiceController extends Controller
{
    public function index()
    {
        try {
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

            $orders = Order::select('orders.id', 'orders.invoice_number','orders.order_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->where('orders.is_deleted', 0)
                ->orderBy('orders.created_at', 'desc')
                ->where('orders.status', 'delivered')
                ->paginate(10);

             // Calculate total taxable amount and total amount
        $totalTaxableAmount = $orders->sum(function($order) {
            return $order->total_price - ($order->total_price * 0.18);
        });

        $totalAmount = $orders->sum('total_price');

        return view('admin.invoice', [
            'orders' => $orders,
            'totalTaxableAmount' => $totalTaxableAmount,
            'totalAmount' => $totalAmount,
        ]);

        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine()
            ], 500);
        }
    }

    public function indexfilter(Request $request)
    {
        try {
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

            $ordersQuery = Order::select('orders.id', 'orders.invoice_number','orders.order_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->where('orders.is_deleted', 0)
                ->where('orders.status', 'delivered');

            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $endDate = Carbon::parse($endDate)->endOfDay();
                $ordersQuery->whereBetween('orders.created_at', [$startDate, $endDate]);
            }

            $orders = $ordersQuery->orderBy('orders.created_at', 'desc')->get();

            $totalTaxableAmount = $orders->sum(function($order) {
                return  $order->total_price / 1.18;
            });

            $totalAmount = $orders->sum('total_price');

            if ($request->ajax()) {
                return response()->json([
                    'orders' => $orders,
                    'totalTaxableAmount' => $totalTaxableAmount,
                    'totalAmount' => $totalAmount
                ]);
            }

            return view('admin.invoice', [
                'orders' => $orders,
                'totalTaxableAmount' => $totalTaxableAmount,
                'totalAmount' => $totalAmount,
                'startDate' => $request->input('startDate'),
                'endDate' => $request->input('endDate')
            ]);

        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine()
            ], 500);
        }
    }
    public function export(Request $request)
    {
        $dateRange = $request->input('date_range');

        // If a date range is provided, filter orders accordingly
        if ($dateRange) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            $orders = Order::with('paymentDetail')->whereBetween('updated_at', [date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)) . ' 23:59:59' // Add time to include today's orders
            ])->where('is_deleted', 0)
            ->where('status', 'delivered')->get();
        } else {
            // Otherwise, fetch all orders
            $orders = Order::all();
        }

        if ($orders->isEmpty()) {
            // Return an error message if no orders are found
            return redirect()->back()->with('error', 'No orders found for the selected date range.');
        } else {
            // Pass the collection of orders to the OrdersExport class
            return Excel::download(new OrdersExport($orders), 'orders.xlsx');
        }
        // Pass the collection of orders to the OrdersExport class
        // return Excel::download(new OrdersExport($orders), 'orders.xlsx');
    }

    public function analitices(){

        // Retrieve order statistics
        $ordersDataCount = Order::select([
            DB::raw('COUNT(*) as totalOrders'), // Total number of orders
            DB::raw('COUNT(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pendingOrders'), // Total pending orders
            DB::raw('COUNT(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as deliveredOrders'), // Total delivered orders
            DB::raw('SUM(total_price) as totalOrdersAmount') // Total amount of all orders
        ])
        ->where('is_deleted', '!=', 1) // Exclude deleted orders
        ->first();

        // Retrieve detailed orders data along with customer names
        $totalOrderByCustomers = Order::select([
            'orders.invoice_number', // Invoice number of the order
            'orders.order_number', // Order number
            'orders.user_id', // ID of the user who placed the order
            'orders.order_date', // Date of the order
            'orders.status', // Current status of the order
            'orders.total_price', // Total price of the order
            'users.name' // Name of the user who placed the order
        ])
        ->where('orders.is_deleted', '!=', 1) // Exclude deleted orders
        ->join('users', 'orders.user_id', '=', 'users.id') // Join with users table to get user details
        ->get();

        // Return the view with the retrieved data
        return view('admin.detail', [
            'totalOrders' => $ordersDataCount->totalOrders, // Pass total orders count to the view
            'pendingOrders' => $ordersDataCount->pendingOrders, // Pass pending orders count to the view
            'deliveredOrders' => $ordersDataCount->deliveredOrders, // Pass delivered orders count to the view
            'totalOrdersAmount' => $ordersDataCount->totalOrdersAmount, // Pass total orders amount to the view
            'totalOrderByCustomers' => $totalOrderByCustomers, // Pass detailed order data to the view
        ]);
    }
}
