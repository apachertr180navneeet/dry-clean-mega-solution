<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tenant;
use Throwable;
use Carbon\Carbon;

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

            $orders = Order::select('orders.id', 'orders.invoice_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty')
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

    //     public function index(Request $request)
// {
//     try {
//         $tenantId = tenant('id');

//         $tenant = Tenant::where('tenants.id', $tenantId)
//                         ->join('subscriptions', 'tenants.id', '=', 'subscriptions.tenant_id')
//                         ->select('tenants.*', 'subscriptions.starting_date', 'subscriptions.end_date')
//                         ->first();

//         if (!$tenant) {
//             Auth::logout();
//             return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
//         }

//         $date = Carbon::now()->format("Y-m-d");

//         if ($tenant->is_active == 0 || !($tenant->starting_date <= $date && $tenant->end_date >= $date)) {
//             Auth::logout();
//             return redirect()->route('login')->withErrors(['Your tenant is inactive. Please contact your Super Admin.']);
//         }

//         $ordersQuery = Order::select('orders.id', 'orders.invoice_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty')
//             ->join('users', 'users.id', '=', 'orders.user_id')
//             ->where('orders.is_deleted', 0);

//         if ($request->has('filterDate')) {
//             $filterDate = $request->input('filterDate');
//             $ordersQuery->whereDate('orders.created_at', $filterDate);
//         }

//         $orders = $ordersQuery->orderBy('orders.created_at', 'desc')->paginate(10);

//         $totalTaxableAmount = $orders->sum(function($order) {
//             return $order->total_price - ($order->total_price * 0.18);
//         });

//         $totalAmount = $orders->sum('total_price');

//         return view('admin.invoice', [
//             'orders' => $orders,
//             'totalTaxableAmount' => $totalTaxableAmount,
//             'totalAmount' => $totalAmount,
//             'filterDate' => $request->input('filterDate')
//         ]);

//     } catch (Throwable $throwable) {
//         return response()->json([
//             'message' => $throwable->getMessage(),
//             'file' => $throwable->getFile(),
//             'line' => $throwable->getLine()
//         ], 500);
//     }
// }

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

        $ordersQuery = Order::select('orders.id', 'orders.invoice_number', 'orders.total_price', 'orders.status', 'users.name', 'users.mobile', 'orders.total_qty')
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
            $orders = Order::with('paymentDetail')->whereBetween('updated_at', [date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))])->get();
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
}
