<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class DashboardController extends Controller
{ 
    public function index(Request $request)
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
        
        $clientCounts = User::where(['is_deleted' => 0, 'role_id' => 2])->count();
        $orderCounts = Order::where(['is_deleted' => 0])->count();
        
        $today = Carbon::today();
        
        $orderCounts = Order::where('is_deleted', 0)
        ->where('status', 'pending') // Replace 'pending' with the actual status value for pending orders
        ->where('created_at', '>=', $today)
        ->count();

        $orders = Order::select('orders.id', 'users.name','orders.invoice_number', 'orders.total_price', 'orders.total_qty', 'payment_details.status as payment_status', DB::raw('MAX(order_items.status) as item_status'))
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->leftJoin('payment_details', 'payment_details.order_id', '=', 'orders.id')
        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.is_deleted', 0)
        ->whereDate('orders.created_at', $today)
        ->groupBy('orders.id', 'users.name','orders.invoice_number', 'orders.total_price', 'orders.total_qty', 'payment_details.status')
        ->orderBy('orders.created_at', 'desc')
        // ->get();
        ->paginate(10);

        // Get the count of pending orders in the last 10 days
        $tenDaysAgo = Carbon::now()->subDays(10);
        $pendingOrderCounts = Order::where('is_deleted', 0)
            ->where('status', 'pending') // Replace 'pending' with the actual status value for pending orders
            ->where('created_at', '>=', $tenDaysAgo)
            ->count();

        // Get pending orders from the last 10 days
    $pendingOrders = Order::select('orders.id', 'users.name','orders.invoice_number', 'orders.total_price', 'orders.total_qty', 'payment_details.status as payment_status', DB::raw('MAX(order_items.status) as item_status'))
    ->join('users', 'users.id', '=', 'orders.user_id')
    ->leftJoin('payment_details', 'payment_details.order_id', '=', 'orders.id')
    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
    ->where('orders.is_deleted', 0)
    ->where('orders.status', 'pending') // Replace 'pending' with the actual status value for pending orders
    ->where('orders.created_at', '>=', $tenDaysAgo)
    ->groupBy('orders.id', 'users.name','orders.invoice_number', 'orders.total_price', 'orders.total_qty', 'payment_details.status')
    ->orderBy('orders.created_at', 'desc')
    ->paginate(10);

        return view('app/dashboard', compact('orders','clientCounts', 'orderCounts', 'pendingOrderCounts', 'pendingOrders'));
    }

}
