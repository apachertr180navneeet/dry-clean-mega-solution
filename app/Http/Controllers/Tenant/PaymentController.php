<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use App\Services\SmsService;
use App\Models\User;
class PaymentController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

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
        $query = PaymentDetail::select('payment_details.updated_at', 'payment_details.payment_type','users.mobile','orders.order_number','orders.total_price')
            ->join('orders', 'orders.id', '=', 'payment_details.order_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.is_deleted', 0)
            ->where('orders.status', 'delivered');

            if ($request->ajax()) {
                $search = $request->input('search');
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('orders.order_number', 'like', '%' . $search . '%');
                        // ->orWhereRaw("DATE_FORMAT(orders.updated_at, '%e %M, %Y') LIKE ?", ['%' . $search . '%']);
                    });
                }

                $payments = $query->orderBy('payment_details.updated_at', 'desc')->paginate(10);
                $formattedPayments = $payments->map(function($payment) {
                    $timestamp = strtotime($payment->updated_at);
                    $payment->updated_at = date('j F, Y', $timestamp);
                    return $payment;
                });

                return response()->json([
                    'payments' => $formattedPayments->all(),
                    'pagination' => (string) $payments->links()
                ]);
            }

        $payments = $query->orderBy('payment_details.updated_at', 'desc')->paginate(10);

        return view('admin.payment', ['payments' => $payments]);
    }
    private function generateInvoiceNumber()
    {
        // Get the last inserted invoice number
        $lastOrder = Order::where('invoice_number', '!=', '')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastOrder || empty($lastOrder->invoice_number)) {
            // If no invoice number exists or the last one is empty, start with 001
            return '001';
        }

        // Extract the numeric part of the last invoice number
        preg_match('/(\d+)$/', $lastOrder->invoice_number, $matches);
        $lastNumber = intval($matches[1] ?? 0);

        // Increment the number by 1 and format it with leading zeros
        return str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function settleAndDeliverOrder(Request $request, $orderId)
    {
        try {
            // Start a transaction
            DB::beginTransaction();

            // Settle the order
            $payment = PaymentDetail::where('order_id', $orderId)->get();
            //dd('Order ID:', $orderId, 'Payment:', $payment, 'All Payments:', PaymentDetail::all());
            if ($payment) {
                PaymentDetail::where('order_id', $orderId)->update([
                    'status' => 'Paid',
                    'payment_type' => $request->paymentType,
                ]);
            } else {
                return response()->json([
                    'error' => 'Payment not found.',
                    'order_id' => $orderId,
                    'payments' => PaymentDetail::all() // Debug: Show all payments
                ], 404);
            }

            // Deliver the order
            $order = Order::findOrFail($orderId);
            $order->status = 'delivered';
            $invoiceNumberCheck = Invoice::where('order_id', $orderId)->first();
            if(!$invoiceNumberCheck){

                $lastInvoiceNumber = Invoice::orderBy('id', 'desc')->value('invoice_number');
                //dd($lastInvoiceNumber);
                // Generate a new invoice number
                $newInvoiceNumber = $this->generateInvoiceNumber();
                // Create a new invoice and associate it with the order
                $invoice = Invoice::create([
                    'order_id' => $orderId,
                    'invoice_number' => $lastInvoiceNumber + 1
                ]); // Automatically saves the invoice


                // $order->invoice_number = $newInvoiceNumber;
                // $order->save(); // Save the order with updated status and invoice number

                Order::where('id', $orderId)->update([
                    'invoice_number' => $newInvoiceNumber,
                    'status' => 'delivered'
                ]);
            }else{
                Invoice::where('order_id',$orderId,)->update([
                    'order_id' => $orderId,
                ]);
            }
            
            $order->orderItems()->update(['status' => 'delivered']);

            // Commit the transaction
            DB::commit();

            // Prepare SMS message
            $client = User::findOrFail($order->user_id);
            $clientPhoneNumber = '+91' . $client->mobile;

            $curl = curl_init();
            $message = $order->order_number.' '.'of amount'.' '.$order->total_price;
            $payload = json_encode([
                "template_id" => "66cf2853d6fc05549845c672",
                "recipients" => [
                    [
                        "mobiles" => $clientPhoneNumber,
                        "ordernumber" => $message,
                        "name" => $client->name,
                    ]
                ]
            ]);

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
                'Error:' . curl_error($curl);
            } else {
                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                "HTTP Status Code: $http_code\n";
                "Response: $response\n";
            }
            curl_close($curl);

            return response()->json(['success' => 'Order settled and delivered successfully.']);
            // return redirect()->route('invoice')->with('success', 'Order settled and delivered successfully.');
        } catch (\Throwable $throwable) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }
}
