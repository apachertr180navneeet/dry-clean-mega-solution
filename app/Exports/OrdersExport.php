<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return Order::with('paymentDetail')->get(['id', 'invoice_number', 'payment_type', 'updated_at', 'total_amount'])->map(function ($order) {
    //         return [
    //             'id' => $order->id,
    //             'invoice_number' => $order->invoice_number,
    //             'payment_type' => $order->paymentDetail->payment_type,
    //             'updated_at' => $order->updated_at->format('Y-m-d'), // Format the date
    //             'total_amount' => $order->total_amount,
    //             'taxable_amount' => $order->total_amount,
    //             'tax_amount' => $order->total_amount * 0.18,
    //         ];
    //     });
    // }

    // /**
    //  * @return array
    //  */
    // public function headings(): array
    // {
    //     return [
    //         'ID',
    //         'Invoice Number',
    //         'Payment Type',
    //         'PaidAt (Date: yyyy-mm-dd)',
    //         'Paid Amount',
    //         'Taxable Amount',
    //         'Tax Amount',
    //     ];
    // }

    protected $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    // public function collection(): Collection
    // {
    //     return $this->orders->map(function ($order) {
    //         return [
    //             'id' => $order->id,
    //             'invoice_number' => $order->invoice_number,
    //             'payment_type' => $order->paymentDetail->payment_type,
    //             'updated_at' => $order->updated_at->format('Y-m-d'),
    //             'total_amount' => $order->paymentDetail->total_amount,
    //             'taxable_amount' => $order->paymentDetail->total_amount,
    //             'tax_amount' => $order->paymentDetail->total_amount * 0.18,
    //         ];
    //     });
    // }

    // public function headings(): array
    // {
    //     return [
    //         'ID',
    //         'Invoice Number',
    //         'Payment Type',
    //         'PaidAt (Date: yyyy-mm-dd)',
    //         'Paid Amount',
    //         'Taxable Amount',
    //         'Tax Amount',
    //     ];
    // }

    public function collection(): Collection
{
    return $this->orders->map(function ($order) {
        $totalamount1 = $order->total_price;
        $totalTaxAmount = $totalamount1 / 1.18;
        $finaltaxAmount = $totalamount1 - $totalTaxAmount;
        return [
            'invoice_number' =>  $order->invoice_number,
            'order_number' =>  $order->order_number,
            'customer_name' => $order->user->name, // Assuming there is a relation customer with a name attribute
            'payment_type' => $order->paymentDetail->payment_type,
            'updated_at' => $order->updated_at->format('Y-m-d'),
            'cgst' => number_format($finaltaxAmount / 2, 2),
                'sgst' => number_format($finaltaxAmount / 2, 2),
                'taxable_amount' => number_format($totalTaxAmount, 2),
                'tax_amount' => number_format($finaltaxAmount, 2),
                'total_amount' => number_format($order->paymentDetail->total_amount, 2),
        ];
    });
}

public function headings(): array
{
    return [
        'Invoice Number',
        'Order Number',
        'Customer Name',
        'Payment Type',
        'PaidAt (Date: yyyy-mm-dd)',
        'CGST',
        'SGST',
        'Taxable Amount',
        'Tax Amount',
        'Paid Amount',
    ];
}


}

