{{-- <div class="row">
    @foreach ($data as $operationData)
    @php 
    // dd($data);
            // Check if $others has the key for this operation
            $orderItemId = 0;
            if (!empty($others[$operationData->pid])) {
                $orderItemId = $others[$operationData->pid]['id'];
            }

            // Prepare item attributes
            $itemAttr = [
                'op_id' => $operationData->op_id,
                'op_name' => $operationData->op_name,
                'price' => $operationData->price,
                'item_cat_id' => $operationData->item_cat_id,
                'pid' => $operationData->pid,
                'orderItemId' => $orderItemId,
                'isMatch' => false,
            ];
            // dd($itemAttr);
            // // Check if there are operations for this item
            // if (!empty($others[$operationData->pid])) {
            //     foreach ($others[$operationData->pid]['Operations'] as $operation) {
            //         // Check if 'service_id' key exists in the current operation
            //         if ($operation['service_id'] == $itemAttr['op_id'] && $operation['category_id'] == $itemAttr['item_cat_id']) {
            //             $itemAttr['isMatch']=true;
            //            dd($itemAttr['isMatch']);
            //         }
            //     }
            // }
               // Check if there are operations for this item
        if (!empty($others[$operationData->pid]) && isset($others[$operationData->pid]['Operations'])) {
            foreach ($others[$operationData->pid]['Operations'] as $operation) {
                // Check if 'service_id' key exists in the current operation
                if ($operation['service_id'] == $itemAttr['op_id'] && $operation['category_id'] == $itemAttr['item_cat_id']) {
                    $itemAttr['isMatch'] = true;
                    // dd($itemAttr['isMatch']);
                }
            }
        }
        // dd($others[$operationData->pid]['Operations']);
        @endphp
        {{-- <div @if ($orderItemId) id="serviceId{{ $orderItemId }}" @endif class="col-lg-4 mb-2"> --}}
        {{-- <div class="col-lg-4 mb-2">
            
            <div class="category-service badge w-100 @if ($itemAttr['isMatch']) bg-success text-white @else bg-light text-dark @endif mb-2 operationData"
            onclick='categoryPriceItem(@json($itemAttr), this)'>
            <input type="hidden" value='@json($itemAttr)' id="prdSelectId{{ $operationData->pid }}">
            <h6 class="mb-0">{{ $operationData->op_name }}</h6>
            <h6 class="mb-0">New Man</h6>
            
            <h6 class="mb-0 text-dark service-price">{{ $operationData->price }}/pc</h6>
            </div>
            </div>
            @endforeach --}}
            {{-- @dd($others[$operationData->pid]['Operations']); --}}
            {{-- @dd($operationData->pid); --}}
{{-- </div> --}} 
<div class="row">
    @foreach ($data as $operationData)
        @php
        // dd($data);
            $orderItemId = 0;
            if (!empty($others[$operationData->pid])) {
                $orderItemId = $others[$operationData->pid]['id'];
            }

            $itemAttr = [
                'op_id' => $operationData->op_id,
                'op_name' => $operationData->op_name,
                'price' => $operationData->price,
                'item_cat_id' => $operationData->item_cat_id,
                'pid' => $operationData->pid,
                'orderItemId' => $orderItemId,
                'isMatch' => $operationData->isMatch,
            ];
            // dd($itemAttr);
        @endphp
        <div class="col-lg-4 mb-2">
            <div class="category-service badge w-100 @if ($itemAttr['isMatch']) bg-success text-white @else bg-light text-dark @endif mb-2 operationData"
                onclick='categoryPriceItem(@json($itemAttr), this)'>
                <input type="hidden" value='@json($itemAttr)' id="prdSelectId{{ $operationData->pid }}">
                <h6 class="mb-0">{{ $operationData->op_name }}</h6>
                {{-- <h6 class="mb-0">New Man</h6> --}}
                <h6 class="mb-0 text-dark service-price">{{ $operationData->price }}/pc</h6>
            </div>
        </div>
    @endforeach
</div>

