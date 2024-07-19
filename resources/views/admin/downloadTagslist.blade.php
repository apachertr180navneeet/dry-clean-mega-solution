<!DOCTYPE html>
<html>

<head>
    <style>
        .table-item-container {
            width: 38mm;
            display: inline-block;
        }

        .table-item {
            box-sizing: border-box;
            padding: 10px;
            background: #ffffffb3;
            border: 1px solid #dbdade;
            border-radius: 5px;
            text-align: center;
            vertical-align: top;
        }

        .table-item div {
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            padding: 5px;
            margin: 1rem auto;
            width: 100px;
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
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">Mega Dry Cleaning</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $order->user->name }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <div><span>T {{ $orderItem->quantity }}</span></div>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->opertions->name }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->productItem->name }}</p>
                            <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->productCategory->name }}</p>
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
