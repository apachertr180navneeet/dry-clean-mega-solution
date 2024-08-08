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
            padding: 5pt; /* Ensure this is not causing extra space */
            margin: 0; /* Ensure there is no additional space between tags */
            border-radius: 5pt; /* Optional: Adjust or remove if necessary */
            page-break-inside: avoid;
        }

        .table-item {
            text-align: center;
        }

        .table-item p {
            margin: 2pt 0; /* Adjust margins as needed */
            font-size: 10pt; /* Ensure font size fits well within tag */
            color: black;
        }
    </style>
</head>

<body>
    @foreach ($order->orderItems as $orderItem)
        @for ($i = 0; $i < $orderItem->quantity; $i++)
            <div class="table-item-container">
                <div class="table-item">
                    <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">Mega Dry Cleaning</p>
                    <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->order_number }}</p>
                    <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->user->name }}</p>
                    <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->delivery_date }}</p>
                    <div style="margin-bottom:5px">
                        <span style="padding:10px 25px; font-weight: 900; font-size: 12px;">T {{ $subTotalqty }}</span>
                    </div>
                    <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">
                        @if($orderItem->opertions)
                            {{ $orderItem->opertions->name }}
                        @else
                            Operation data missing
                        @endif
                    </p>
                    <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">
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
