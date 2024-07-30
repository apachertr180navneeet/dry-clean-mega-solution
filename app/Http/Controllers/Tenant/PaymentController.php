<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\SmsService;
use App\Models\User;
class PaymentController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    //
    // public function index()
    // {
    //     $payments = PaymentDetail::paginate(10);
    //     return view('admin.payment', ['payments' => $payments]);
    // }

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
        $query = PaymentDetail::select('payment_details.*', 'users.mobile')
            ->join('orders', 'orders.id', '=', 'payment_details.order_id')
            ->join('users', 'users.id', '=', 'orders.user_id');

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('users.mobile', 'like', '%' . $search . '%');
        }

        $payments = $query->orderBy('payment_details.created_at', 'desc')->paginate(10);

        return view('admin.payment', ['payments' => $payments, 'search' => $request->input('search')]);
    }

    // public function settleOrder(Request $request, $orderId, $paymentType)
    // {
    //     try {
    //         // Find the payment detail by orderId
    //         $payment = PaymentDetail::where('order_id', $orderId)->first();

    //         // Check if the payment exists
    //         if ($payment) {
    //             // Update the status to 'Paid' and set the payment type
    //             $payment->status = 'Paid';
    //             $payment->payment_type = $paymentType;
    //             $payment->save();

    //             // Optionally, you might want to return a success message or redirect
    //             return redirect()->route('viewOrder')->with('success', 'Payment status updated to Paid successfully.');
    //         } else {
    //             // If payment is not found, return an error message
    //             return redirect()->route('viewOrder')->with('error', 'Payment not found.');
    //         }
    //     } catch (Throwable $throwable) {
    //         // Handle any errors
    //         dd($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
    //     }
    // }
    // public function deliverOrder(Request $request, $orderId)
    // {
    //     try {
    //         // Find the order by orderId
    //         $order = Order::findOrFail($orderId);

    //         // Update the order status to 'delivered'
    //         $order->status = 'delivered';
    //         $order->save();

    //         // Update all associated order items to 'delivered'
    //         $order->orderItems()->update(['status' => 'delivered']);

    //         // Optionally, you might want to return a success message or redirect
    //         return redirect()->route('invoice')->with('success', 'Order and order items status updated to delivered successfully.');
    //     } catch (Throwable $throwable) {
    //         // Handle any errors
    //         dd($throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
    //     }
    // }
    private function generateInvoiceNumber()
    {
        // Get the last inserted invoice number
        $lastOrder = Order::where('invoice_number', '!=', '')
            ->orderBy('id', 'desc')
            ->first();
    
        if (!$lastOrder || empty($lastOrder->invoice_number)) {
            // If no invoice number exists or the last one is empty, start with INV-001
            return '001';
        }
    
        // Extract the numeric part of the last invoice number using regular expressions
        preg_match('/(\d+)$/', $lastOrder->invoice_number, $matches);
        $lastNumber = intval($matches[1] ?? 0); // Change matches[0] to matches[1]
    
        // Increment the number by 1
        $nextNumber = $lastNumber + 1;
    
        // Format the new invoice number with leading zeros
        return str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function settleAndDeliverOrder(Request $request, $orderId)
    {
        try {
            // Start a transaction
            DB::beginTransaction();

            // Settle the order
            $payment = PaymentDetail::where('order_id', $orderId)->first();
            if ($payment) {
                $payment->status = 'Paid';
                $payment->payment_type = $request->paymentType;
                $payment->save();
            } else {
                return response()->json(['error' => 'Payment not found.'], 404);
            }

            // Deliver the order
            $order = Order::findOrFail($orderId);
            $order->status = 'delivered';
            $order->invoice_number = $this->generateInvoiceNumber();
            $order->save();
            $order->orderItems()->update(['status' => 'delivered']);

            // Commit the transaction
            DB::commit();

            // Prepare SMS message
            $client = User::findOrFail($order->user_id);
            $message = sprintf(
                "Dear %s, your order (ID: %s) of %s is Delivered. Do visit us again. Regards - Mega Solutions Dry cleaning",
                $client->name,
                $order->id,
                $order->total_price
            );

            // $clientPhoneNumber = '+91' . $client->mobile;
            // $templateId = '1207172128171262962';
            // $variables = ['ordernumber' => $order->order_number, 'name' => $client->name];

            // try {
            //     $this->smsService->sendSms($clientPhoneNumber, $templateId, $variables);
            // } catch (\Exception $e) {
            //     dd($e->getMessage());
            //     Log::error('Error sending SMS: ' . $e->getMessage());
            // }

            return response()->json(['success' => 'Order settled and delivered successfully.']);
            // return redirect()->route('invoice')->with('success', 'Order settled and delivered successfully.');
        } catch (\Throwable $throwable) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }
}
