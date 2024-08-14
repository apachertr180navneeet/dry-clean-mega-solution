@extends('backend.layouts.app') @section('content')
<style>
    .table-item-container {
        width: 300px;
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
        color: black;
        border-radius: 5px;
    }

    .print-button {
        display: block;
        width: 100%;
        text-align: center;
        margin: 20px 0; /* Margin for the print button */
    }

    .print-button button {
        color: black;
        border: none;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
    }
</style>
<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp Add_order_page_section">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between mb-3">
                        <div class="col-lg-3">
                            <a type="button" class="text-primary" href="{{ url('/admin/view-order') }}"> <i class="fa-solid fa-arrow-left me-2"></i> Tags </a>
                        </div>
                        <div class="col-lg-1">
                            <a class="btn btn-primary" href="{{ url('/admin/print-taglist/' . $order->id) }}" type="button"><i class="fa-solid fa-print me-2"></i></a>
                        </div>
                    </div>
                    <div class="table-container">
                        @php $counter = 0; @endphp @foreach ($order->orderItems as $orderItem) @for ($i = 0; $i < $orderItem->quantity; $i++) @if ($counter % 3 == 0)
                        <div class="table-row">
                            @endif
                            <div class="table-item-container">
                                <div class="table-item text-center">
                                    <p style="font-weight: bold; font-size: 14px; color: #6c757d;">Mega Dry Cleaning</p>
                                    <p style="font-weight: bold; font-size: 14px; color: #6c757d;text-transform: capitalize;">{{ $order->user->name }}</p>
                                    <p style="font-weight: bolder; font-size: 18px; color: #6c757d;">
                                        {{ $order->order_number }}</p>
                                    <p style="font-weight: bold; font-size: 14px; color: #6c757d;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    <div><span>T {{ $orderItem->quantity }}</span></div>
                                    <p style="font-weight: bold; font-size: 14px; color: #6c757d; border: 1px solid #000; width:35px; height: 30px;display: flex; align-items: center; justify-content: center; border-radius: 5px;margin: 5px auto;">{{ $orderItem->opertions->name }}</p>
                                    <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->productItem->name }}</p>
                                    <p style="font-weight: bold; font-size: 14px; color: #6c757d;">{{ $orderItem->productCategory->name }}</p>
                                </div>
                            </div>
                            @php $counter++; @endphp @if ($counter % 3 == 0)
                        </div>
                        @endif @endfor @endforeach @if ($counter % 3 != 0)
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- <style>
        ul {
            counter-reset: list-counter;
            list-style-type: none;
        }

        li::before {
            content: counter(list-counter);
            counter-increment: list-counter;
            margin-right: 0.5em;
        }
    </style> -->
