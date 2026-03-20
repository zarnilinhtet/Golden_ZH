<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>

    <script src="{{ asset('backend/js/moment2103.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<style>
    @media print {
        hr {
            display: block;
            /* Ensure <hr> is visible when printing */
            border: 1px solid red;
            /* Example: Add border for visibility */
        }
    }
</style>
<style>
    @media print {
        body {
            color: black;
            /* Set text color for printing */
        }

        /* Add any other styles you want to modify for printing */
    }

    @media print {

        #test,
        #printButton,
        .excelButton {
            display: none;
        }

        @page {
            size: auto;
            margin: 0;
        }
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<body>
    <div class="container mx-auto">
        <div class="mt-2 text-center">
            <img src="{{ asset('logos/' . ($profile->logos ?? '')) }}" alt="{{ $profile->name ?? '' }} pos" width="100"
                height="60">
        </div>
        <div class="row" style="margin-top: 20px;">
            <h5 class="text-center fw-bold">{{ $profile->name ?? '' }}</h5>

            <p class="mb-2 text-center fw-bold" style="font-size: 13px;line-height:25px;">
                {{ $profile->address ?? '' }}
                <br>
                {{ $profile->phno1 ?? '' }}, {{ $profile->phno2 ?? '' }}
            </p>

            <hr style="color: red !important; background-color: red  !important; height: 2px !important;">
            <div class="row">
                <p class="text-center fw-bold" style="font-size: 13px;"><i>*** မှားယွင်းမှုတစ်စုံတစ်ရာရှိပါက (24)
                        နာရီအတွင်းအကြောင်းကြားပေးပါရန် ***</i></p>
            </div>
            <div class="row">
                <div class="col-md-6 offset-8">
                    <p class="mb-2 text-center fw-bold">Quotation No. : {{ $invoice->quote_no }} <br> Date :
                        {{ $invoice->created_at->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-1 row">
            <!-- <p class="fw-bold" style="font-size: 12px;">Sale ID: {{ $invoice->invoice_no }}<br>Employee : </p> -->
            <div class="table-responsive">
                <table class="mt-1" style="font-size: 13px;width:100%;">
                    <thead>
                        {{-- <tr class="">
                            <th colspan="4" class="py-1 ps-1 fw-bold" style="border: 1px solid black !important;">Name - {{$invoice->customer_name}}</th>
                            <th colspan="2" class="py-1 ps-1" style="border: 1px solid black !important;">Date - {{$invoice->created_at->format('d-m-Y')}}</th>
                        </tr> --}}
                        <tr class="text-center">
                            <th class="py-1" style="width: 5%; border: 1px solid black !important;">Sr.</th>
                            <th class="py-1" style="width: 30%; border: 1px solid black !important;">Product Name</th>
                            <th class="py-1" style="width: 10%; border: 1px solid black !important;">Qty</th>
                            <th class="py-1" style="width: 10%; border: 1px solid black !important;">Unit</th>
                            <th class="py-1" style="width: 10%; border: 1px solid black !important;">Unit Price</th>
                            <th class="py-1" style="width: 10%; border: 1px solid black !important;">Discount</th>
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="border: 1px solid black !important;">
                        @foreach ($invoices as $key => $invoice)
                            @foreach ($invoice->sells as $key => $sell)
                                <tr class="text-center fw-bold">
                                    <td style="border: 1px solid black !important;">{{ $key + 1 }}.</td>
                                    <td class="py-1 border" style="border: 1px solid black !important;">
                                        {{ $sell->part_number }}</td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->product_qty }}
                                    </td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->unit }}
                                    </td>
                                    <td class="text-end pe-1" style="border: 1px solid black !important;">
                                        @if ($invoice->sale_price_category == 'HD')
                                            {{ $sell->product_price }}
                                        @elseif ($invoice->sale_price_category == 'Clinic')
                                            {{ $sell->retail_price }}
                                        @elseif ($invoice->sale_price_category == 'Default')
                                            {{ $sell->retail_price }}
                                        @else
                                            @foreach ($items as $item)
                                                @if ($item->item_name === $sell->part_number)
                                                    {{ $item->buy_price }}
                                                @break
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-end pe-1" style="border: 1px solid black !important;">
                                    {{ $sell->item_discount }}{{ ' ' }}{{ $sell->ks_percent }}</td>
                                <td class="text-end pe-1" style="border: 1px solid black !important;">
                                    @if ($invoice->sale_price_category == 'HD')
                                        {{ $sell->product_price * $sell->product_qty }}
                                    @elseif ($invoice->sale_price_category == 'Clinic')
                                        {{ $sell->retail_price * $sell->product_qty }}
                                    @elseif ($invoice->sale_price_category == 'Default')
                                        {{ $sell->retail_price * $sell->product_qty }}
                                    @else
                                        @foreach ($items as $item)
                                            @if ($item->item_name === $sell->part_number)
                                                {{ $item->buy_price * $sell->product_qty }}
                                            @break
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr style="line-height: 25px;">
                    <td colspan="5"></td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Sub Total</td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                        {{ $invoice->net_total ?? 0 }}</td>
                </tr>
                {{-- <tr style="line-height: 25px;">
                    <td colspan="5"></td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Item Discount
                    </td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                        @php
                            $total_discount = 0;
                        @endphp

                        @foreach ($invoice->sells as $key => $sell)
                            @if ($sell->ks_percent == 'ks')
                                @php
                                    $total_discount = $sell->item_discount;
                                @endphp
                            @else
                                @if ($invoice->sale_price_category == 'Default')
                                    @if ($invoice->type == 'Whole Sale')
                                        @php
                                            $total_discount =
                                                ($sell->product_price * $sell->product_qty * $sell->item_discount) /
                                                100;
                                        @endphp
                                    @else
                                        @php
                                            $total_discount =
                                                ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100;
                                        @endphp
                                    @endif
                                @elseif ($invoice->sale_price_category == 'Whole Sale')
                                    @php
                                        $total_discount =
                                            ($sell->product_price * $sell->product_qty * $sell->item_discount) / 100;
                                    @endphp
                                @elseif ($invoice->sale_price_category == 'Retail')
                                    @php
                                        $total_discount =
                                            ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100;
                                    @endphp
                                @endif
                            @endif
                        @endforeach

                        {{ $total_discount ?? 0 }}
                    </td>
                </tr> --}}
                <tr style="line-height: 25px;">
                    <td colspan="5"></td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Overall
                        Discount</td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                        {{ $invoice->discount_total ?? 0.0 }}</td>
                </tr>
                <tr style="line-height: 25px;">
                    <td colspan="5"></td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Total</td>
                    <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                        {{ $invoice->total ?? '0.00' }}
                    </td>
                </tr>

            </tfoot>
        </table>
        <div class="text-center mt-3" id="thank">
            <p class="text-center fw-bold" style="font-size: 12px;">
                အားပေးမှု့အတွက် ကျေးဇူးတင်ပါသည်။</p>
            </p>
        </div>
    </div>
    <a onclick="printPage()" id="printButton" class="col-md-1
    mt-4 btn btn-primary">Print</a>
</div>
</div>
<script>
    function printPage() {
        window.print();
    }
</script>

</body>

</html>
