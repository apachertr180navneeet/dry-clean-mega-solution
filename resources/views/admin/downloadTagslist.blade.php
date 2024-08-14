<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: 144pt 187pt; /* 2x2.6 inches */
            margin: 0; /* Remove all margins */
        }

        body {
            margin: 0;
            padding: 0;
            width: 144pt; /* 2 inches */
            height: 187pt; /* 2.6 inches */
        }

        .table-item-container {
            width: 144pt; /* Match label width */
            height: 187pt; /* Match label height */
            box-sizing: border-box;
            border: 1px solid #dbdade;
            display: block;
            padding: 10pt 5pt 5pt 0pt; /* Ensure this is not causing extra space */
            margin: 0; /* Ensure there is no additional space between tags */
            border-radius: 5pt; /* Optional: Adjust or remove if necessary */
            page-break-inside: avoid;
            text-align: center;
        }

        .table-item {
            text-align: center;
        }
        
        .table-item p {
            margin: 2pt auto; /* Adjust margins as needed */
            font-size: 10pt; /* Ensure font size fits well within tag */
            color: black;            
            width: 100%;
        }
    </style>
</head>

<body>
    @foreach ($order->orderItems as $orderItem)
        @for ($i = 0; $i < $orderItem->quantity; $i++)
            <div class="table-item-container"  style=" border: 2px dashed #000; border-radius: 5px; margin: 2px;width:47mm; height: 59mm;">
                <div class="table-item text-center" 5px">
                    <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;">Mega Dry Cleaning</p>
                    <p style="font-weight: bolder; font-size: 18px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->order_number }}</p>
                    <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;text-transform: capitalize;">{{ $order->user->name }}</p>
                    <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->delivery_date }}</p>
                    <div style="margin-bottom:5px">
                        <span style="padding:10px 25px; font-weight: 900; font-size: 14px;">T {{ $subTotalqty }}</span>
                    </div>
                    <p style="font-weight: bold; font-size: 14px; color: black; border: 1px solid #000;width:32px; padding: 5px 0 ;border-radius: 5px;margin: 7px auto;">
                        @if($orderItem->opertions)
                            {{ $orderItem->opertions->name }}
                        @else
                            Operation data missing
                        @endif
                    </p>
                    <p style="font-weight: bold; font-size: 14px; color: black; margin-bottom:10px; margin-top: 10px;">
                        @if($orderItem->productItem && $orderItem->productCategory)
                            {{ $orderItem->productItem->name }}/{{ $orderItem->productCategory->name }}
                        @else
                            Product or Category data missing
                        @endif
                    </p>
                </div>
            </div>
        @endfor
    @endforeach
</body>
</html>