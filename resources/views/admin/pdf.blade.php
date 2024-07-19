<div class="row justify-content-between mb-3"
    style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
    <!-- <img src="{{ url('theam/Images/logo.png') }}"  class="mt-0 mb-3" style="width: 200px;">      -->
    {{-- <img src="https://fastly.picsum.photos/id/142/200/200.jpg?hmac=L8yY8tFPavTj32ZpuPiqsLsfWgDvW1jvoJ0ETDOUMGg"
        class="mt-0 mb-3" style="width: 200px;"> --}}
    {{-- <img src="{{ public_path() . '\theam\Images\logo.png' }}" class="mt-0 mb-3" style="width: 200px;"> --}}
    {{-- <img src="/theam/Images/logo.png" class="mt-0 mb-3" style="width: 200px;"> --}}
    <h6 class="mb-0" style="color: #5d596c; font-weight: 600; margin-bottom:0; font-size:18px;margin-top:0;">RECEIPT
    </h6>
    <p class="mb-0" style="color: #5d596c;margin-bottom:0;font-size:16px; margin-top:0;">Mega Solutions</p>
    <p class="mb-0" style="color: #5d596c;margin-bottom:0; font-size:16px;margin-top:0;"> 373, Block B, C Road, Jodhpur, Jodhpur,
        Rajasthan, 342003
    </p>
    <p class="mb-0" style="color: #5d596c;margin-bottom:0; font-size:16px;margin-top:0;">GST: 22AAAAA0000A1Z5</p>
    <hr style="margin-bottom:10px;margin-top:10px;"/>
    <h6 class="mb-0" style="color: #5d596c;margin-bottom:0; font-size:18px;margin-top:0;">Order Id: {{ $order->id }}</h6>
    <p class="mb-0" style="color: #5d596c;margin-bottom:0;font-size:16px; margin-top:0;">Date & Time: {{ $order->order_date }}
        {{ $order->order_time }}
    </p>
    <hr style="margin-bottom:10px;margin-top:10px;"/>
    <p class="mb-0" style="color: #5d596c;margin-bottom:0;font-size:16px; margin-top:0;">Bill To:</p>
    <p  style="color: #5d596c; font-weight: 600; font-size:16px;margin-bottom:0;margin-top:0;">{{ $order->user->name }}</p>
    <p  style="color: #5d596c; font-weight: 600; font-size:16px;margin-bottom:0;margin-top:0;">Mo: {{ $order->user->mobile }}</p>
    <hr style="margin-bottom:10px;margin-top:10px;"/>
    <div style="clear: both;"></div>
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border-bottom:0.5px solid #5d596c !important ; text-align: left; padding-bottom:10px; padding-top:10px">
                        Code</th>
                    <th style="border-bottom:0.5px solid #5d596c !important ; text-align: left; padding-bottom:10px; padding-top:10px">Pc
                    </th>
                    {{-- <th style="min-width: auto;">Qnt.</th> --}}
                    <th style="border-bottom:0.5px solid #5d596c !important ; text-align: left; padding-bottom:10px; padding-top:10px">
                        Description</th>
                    <th style="border-bottom:0.5px solid #5d596c !important ; text-align: left; padding-bottom:10px; padding-top:10px">
                        Rate</th>
                    {{-- <th style="min-width: auto;">Discount</th> --}}
                    <th style="border-bottom:0.5px solid #5d596c !important ; text-align: left; padding-bottom:10px; padding-top:10px">
                        Price</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td style=" border-bottom:0.5px solid #5d596c; text-align: left; padding-bottom:10px; padding-top:10px">
                            {{ $loop->iteration }}N
                        </td>
                        <td style=" border-bottom:0.5px solid #5d596c; text-align: left; padding-bottom:10px; padding-top:10px">
                            {{ $item->quantity }}
                        </td>
                        {{-- <td style="min-width: auto;">{{ $item->quantity }}</td> --}}
                        <td style=" border-bottom:0.5px solid #5d596c; text-align: left; padding-bottom:10px; padding-top:10px">
                            {{ $item->productItem->name }}
                            [{{ $item->productCategory->name }}]
                        </td>
                        <td style=" border-bottom:0.5px solid #5d596c; text-align: left; padding-bottom:10px; padding-top:10px">
                            {{ $item->operation_price }}
                        </td>
                        {{-- <td style=""></td> --}}
                        <td style=" border-bottom:0.5px solid #5d596c; text-align: left; padding-bottom:10px; padding-top:10px">
                            {{ $item->quantity * $item->operation_price }}
                        </td>
                    </tr>
                @endforeach
                        </tbody>
        </table>

    </div>

</div>
<div>
    <div style="width:70%;float:left;"></div>
    <table style="width: 30%;float:right;">
        <tr style="margin:0;padding: 0;">
            <td style="margin:0;padding: 0;margin-bottom: 0px; margin-top: 10px; padding:0; padding-bottom: 10px; border-bottom:0.5px solid #5d596c;">
                <h6 style="margin:0;padding: 0;color: #5d596c; font-weight: 600; margin-bottom: 10px; margin-top:10px; font-size: 14px;  ">
                    Total Pcs</h6>
            </td>
            <td style="margin:0;padding: 0;margin-bottom: 0px; margin-top: 10px; padding:0; padding-bottom: 10px; border-bottom:0.5px solid #5d596c;">
                <h6 style="margin:0;padding: 0;color: #5d596c; font-weight: 600; margin-bottom: 10px; margin-top:10px; font-size: 14px; ">
                    {{ $order->orderItems->sum('quantity') }}
                </h6>
            </td>
        </tr>


       <tr style="margin:0;padding: 0;">
            <td style="margin:0;padding: 0;margin-bottom: 0px; margin-top: 10px; padding:0; padding-bottom: 10px; border-bottom:0.5px solid #5d596c;">
                <h6 style="margin:0;padding: 0;color: #5d596c; font-weight: 600; margin-bottom: 10px; margin-top:10px; font-size: 14px;  ">
                    Total Discount
                    ({{$discountPercentage}} %)</h6>
            </td>
            <td style="margin:0;padding: 0;margin-bottom: 0px; margin-top: 10px; padding:0; padding-bottom: 10px; border-bottom:0.5px solid #5d596c;">
                <h6 style="margin:0;padding: 0;color: #5d596c; font-weight: 600; margin-bottom: 10px; margin-top:10px; font-size: 14px; ">
                    {{ $discountAmount }}
                </h6>
            </td>
        </tr>
       <tr style="margin:0;padding: 0;">
            <td style="margin:0;padding: 0;margin-bottom: 0px; margin-top: 10px; padding:0; padding-bottom: 10px; border-bottom:0.5px solid #5d596c;">
                <h6 style="margin:0;padding: 0;color: #5d596c; font-weight: 600; margin-bottom: 10px; margin-top:10px; font-size: 14px;  ">
                    Total Price
                </h6>
            </td>
            <td style="margin:0;padding: 0;margin-bottom: 0px; margin-top: 10px; padding:0; padding-bottom: 10px; border-bottom:0.5px solid #5d596c;">
                <h6 style="margin:0;padding: 0;color: #5d596c; font-weight: 600; margin-bottom: 10px; margin-top:10px; font-size: 14px; ">
                    INR
                    {{ $totalAmount }}
                </h6>
            </td>
        </tr>


    </table>
</div>

<div style="clear: both;"></div>
<div style="width:100%; ">
    <hr style="margin-bottom:10px;margin-top:10px;"/>
    <h6 style="color: #5d596c; font-weight: 600; font-size:18px;margin-bottom:0;">Terms and Conditions </h6>
    <ul>
        <li style="color: #5d596c;">Customer shall examine articles for damage at the time of delivery, and notify the same
            with in 24 hours from the date of delivery
            and company shall not be responsible for any claims afterwards.</li>
        <li style="color: #5d596c;">Company assures the warranty of 2 days from the date of delivery for the articles, for
            any quality related issues with washing or
            dry-cleaning of articles (only if the article has not been used by the customer after
            service). Any quality related claim after the
            stipulated time shall not be entertained</li>
        <li style="color: #5d596c;">Company is not responsible for any article which is left beyond 15Days from the date of
            delivery. After the completion of 15 days
            company will charge 25% of total bill for next 15 days. After 30 days from the date of
            delivery store will not be liable for loss or
            damages</li>
        <li style="color: #5d596c;">Removal of stain is a part of the process but, complete removal of stains can not be
            guaranteed and will be processed at
            customer's risk.</li>
        <li style="color: #5d596c;">We handle all garments, linen, and fabrics with utmost care, but please be aware that
            due to the condition of the items or unseen
            material defects, there's a risk of discoloration or shrinkage. These items are cleaned
            at the owner's risk, and we accept no liability
            for such occurrences</li>
    </ul>
</div>
</div>