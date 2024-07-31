<!DOCTYPE html>
<html>

<head>
    <style>
         body, html {
            margin: 5px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;

        }
        .table-item-container {
            padding: 15px 0;
            border: 1px solid #dbdade;
            width: 56mm;
            height: 310px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 5px;

        }

        .table-item {

           margin: 0;
            text-align: center;

        }

        .table-item div {
            color: black;
            border-radius: 5px;
        }

        .print-button {
            display: block;
            width: 100%;
            text-align: center;
            margin: 20px 0;
            /* Margin for the print button */
        }

        .print-button button {
            color: black;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>

<body>


    <div class="table-container">
        @php
            $counter = 0;
        @endphp
        @foreach ($order->orderItems as $orderItem)
            @for ($i = 0; $i < $orderItem->quantity; $i++)
                @if ($counter % 3 == 0)
                    <div class="table-row">
                @endif
                    <div class="table-item-container">
                        <div class="table-item">
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; margin-bottom:25px;margin-top: 10px; color:#000">Mega Dry Cleaning
                            </p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; margin-bottom:25px;margin-top: 10px;">
                                {{ $order->order_number }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; margin-bottom:25px;margin-top: 10px;">
                                {{ $order->user->name }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; margin-bottom:25px;margin-top: 10px;">
                                {{ $order->delivery_date }}</p>
                            <div style="margin-bottom:18px" ><span style="padding:10px 25px; font-weight: 900;font-size: 20px; ">T {{ $orderItem->quantity }}</span></div>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; margin-bottom:25px;margin-top: 10px;">
                                {{ $orderItem->opertions->name }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d; margin-bottom:25px;margin-top: 10px;">
                                {{ $orderItem->productItem->name }}/{{ $orderItem->productCategory->name }}</p>
                        </div>
                    </div>
                    @php
                        $counter++;
                    @endphp
                    @if ($counter % 3 == 0)
                        </div>
                    @endif
            @endfor
        @endforeach
        @if ($counter % 3 != 0)
            </div>
        @endif
    </div>
</body>

</html>
