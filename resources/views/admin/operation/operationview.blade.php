{{-- <div class="row">
    @foreach ($data as $operationData)
    @php
    $orderItemId=0;
    if (!empty($others[$operationData->pid])) {
        $orderItemId = $others[$operationData->pid]["id"];
    }
    $itemAtrr= $operationData->op_id.'|'.$operationData->op_name.'|'.$operationData->price .'|'.$operationData->item_cat_id.'|'.$operationData->pid.'|'.$orderItemId ;
    @endphp
    <div @if ($orderItemId)
    id="serviceId{{ $orderItemId }}"
@endif class="col-lg-4 mb-2 " >

<div class="category-service badge w-100 @if (!empty($others[$operationData->pid]['category_id']) && $others[$operationData->pid]['category_id'] == $operationData->item_cat_id && $others[$operationData->pid]['service_id'] == $operationData->op_id) bg-success text-white @else bg-light text-dark

    @endif mb-2 oprationData" onclick="categoryPriceItem('{{$itemAtrr}}',this)">
    <input type="hidden" value="{{ $itemAtrr }}" id="prdSelectId{{ $operationData->pid }}">
    <h6 class="mb-0 ">{{ $operationData->op_name }}</h6>
    <h6 class="mb-0 text-dark service-price">{{ $operationData->price }}/pc</h6>
</div>
</div>
@endforeach
</div> --}}

<div class="row">
    @foreach ($data as $operationData)
        @php

            $orderItemId = 0;
            if (!empty($others[$operationData->pid])) {
                $orderItemId = $others[$operationData->pid]['id'];
            }
            $itemAtrr = [
                'op_id' => $operationData->op_id,
                'op_name' => $operationData->op_name,
                'price' => $operationData->price,
                'item_cat_id' => $operationData->item_cat_id,
                'pid' => $operationData->pid,
                'orderItemId' => $orderItemId,
            ];

            // $itemAtrr= $operationData->op_id.'|'.$operationData->op_name.'|'.$operationData->price .'|'.$operationData->item_cat_id.'|'.$operationData->pid.'|'.$orderItemId ;

        @endphp
        <div @if ($orderItemId) id="serviceId{{ $orderItemId }}" @endif class="col-lg-4 mb-2">

            <div class="category-service badge w-100 @if (
                !empty($others[$operationData->pid]['category_id']) &&
                    $others[$operationData->pid]['category_id'] == $operationData->item_cat_id &&
                    $others[$operationData->pid]['service_id'] == $operationData->op_id) bg-success text-white @else bg-light text-dark @endif mb-2 oprationData"
                onclick='categoryPriceItem(@json($itemAtrr), this)'>
                <input type="hidden" value='@json($itemAtrr)' id="prdSelectId{{ $operationData->pid }}">
                <h6 class="mb-0">{{ $operationData->op_name }}</h6>
                <h6 class="mb-0 text-dark service-price">{{ $operationData->price }}/pc</h6>
            </div>
        </div>
    @endforeach
</div>
