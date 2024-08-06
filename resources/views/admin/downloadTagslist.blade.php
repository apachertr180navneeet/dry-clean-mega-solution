<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 144pt 187pt; /* Ensure this matches your label size */
            margin: 0; /* Remove all margins */
        }
    
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            width: 144pt; /* Label width */
            height: 187pt; /* Label height */
        }
    
        .table-item-container {
            width: 144pt; /* Match label width */
            height: 187pt; /* Match label height */
            box-sizing: border-box;
            border: 1px solid #dbdade;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 5pt; /* Ensure this is not causing extra space */
            margin: 0; /* Ensure there is no additional space between tags */
            border-radius: 5pt; /* Optional: Adjust or remove if necessary */
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

    <div class="table-container">
        @php
            $counter = 0;
        @endphp

        @foreach ($order->orderItems as $orderItem)
            @for ($i = 0; $i < $orderItem->quantity; $i++)
                @if ($counter % 3 == 0)
                    @if ($counter > 0)
                        </div> <!-- Close the previous row div -->
                    @endif
                    <div class="table-row">
                @endif

                <div class="table-item-container">
                    <div class="table-item">
                        <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">Mega Dry Cleaning</p>
                        <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->order_number }}</p>
                        <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->user->name }}</p>
                        <p style="font-weight: bold; font-size: 12px; color: black; margin-bottom:10px; margin-top: 10px;">{{ $order->delivery_date }}</p>
                        <div style="margin-bottom:5px">
                            <span style="padding:10px 25px; font-weight: 900; font-size: 12px;">T {{ $orderItem->quantity }}</span>
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

                @php
                    $counter++;
                @endphp

                @if ($counter % 3 == 0)
                    </div> <!-- Close the current row div -->
                @endif
            @endfor
        @endforeach

        @if ($counter % 3 != 0)
            </div> <!-- Close the last row div if not already closed -->
        @endif

    </div>
</body>

</html>
