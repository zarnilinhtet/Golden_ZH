<!DOCTYPE html>
<html>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
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

    @media print {
        #thank {
            display: block !important;
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
        #back,
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
        @if ($profile)
            @if ($profile->logos)
                <div class="mt-2 text-center">
                    <img src="{{ asset('logos/' . ($profile->logos ?? '')) }}" alt="{{ $profile->name ?? '' }} pos"
                        width="100" height="60">
                </div>
            @endif

        @endif
        <div class="row" style="margin-top: 20px;">
            <h5 class="text-center fw-bold">{{ $profile->name ?? '' }}</h5>

            <p class="text-center fw-bold" style="font-size: 14px;">
                {{ $profile->address ?? '' }}
                <br>
                {{ $profile->phno1 ?? '' }}, {{ $profile->phno2 ?? '' }}
            </p>


            <div class="row">
                <div class="col-md-6 offset-8">
                    <p class="text-center fw-bold mb-2">Voucher No. : {{ $invoice->invoice_no }} <br> Date :
                        {{ $invoice->created_at->format('d-M-Y') }}</p>
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
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Unit</th>
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Unit Price</th>
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Discount</th>
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="text-center" style="border: 1px solid black !important;">
                        @php
                            $no = 1;
                            $overallDiscountTotal = $invoice_no->sum('discount_total') ?? 0;

                        @endphp
                        @foreach ($invoiceWithSells as $invoice_s)
                            @foreach ($invoice_s['invoice_sells'] as $key => $sell)
                                <tr class="text-center fw-bold">
                                    <td style="border: 1px solid black !important;">{{ $no++ }}.</td>
                                    <td class="border py-1" style="border: 1px solid black !important;">
                                        {{ $sell->product_name }}</td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->product_qty }}
                                    </td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->unit }}
                                    </td>
                                    <td class="text-end pe-1" style="border: 1px solid black !important;">

                                        @if ($invoice->sale_price_category == 'Default')
                                            @if ($invoice->type == 'Whole Sale')
                                                {{ $sell->product_price }}
                                            @else
                                                {{ $sell->retail_price }}
                                            @endif
                                        @elseif ($invoice->sale_price_category == 'Whole Sale')
                                            {{ $sell->product_price }}
                                        @elseif ($invoice->sale_price_category == 'Retail')
                                            {{ $sell->retail_price }}
                                        @else
                                            @foreach ($items as $item)
                                                @if ($item->item_name == $sell->part_number)
                                                    @if ($item->name1 == $sell->unit)
                                                        {{ $item->price1 }}
                                                    @elseif ($item->name2 == $sell->unit)
                                                        {{ $item->price2 }}
                                                    @elseif ($item->name3 == $sell->unit)
                                                        {{ $item->price3 }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif


                                    </td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->item_discount }}{{ ' ' }}{{ $sell->ks_percent }}
                                    </td>
                                    <td class="text-end pe-1" style="border: 1px solid black !important;">
                                        @if ($sell->ks_percent == 'ks')
                                            @if ($invoice->sale_price_category == 'Default')
                                                @if ($invoice->type == 'Whole Sale')
                                                    {{ $sell->product_price * $sell->product_qty - $sell->item_discount }}
                                                @else
                                                    {{ $sell->retail_price * $sell->product_qty - $sell->item_discount }}
                                                @endif
                                            @elseif ($invoice->sale_price_category == 'Whole Sale')
                                                {{ $sell->product_price * $sell->product_qty - $sell->item_discount }}
                                            @elseif ($invoice->sale_price_category == 'Retail')
                                                {{ $sell->retail_price * $sell->product_qty - $sell->item_discount }}
                                            @else
                                                @foreach ($items as $item)
                                                    @if ($item->item_name == $sell->part_number)
                                                        @if ($item->name1 == $sell->unit)
                                                            {{ $item->price1 * $sell->product_qty - $sell->item_discount }}
                                                        @elseif ($item->name2 == $sell->unit)
                                                            {{ $item->price2 * $sell->product_qty - $sell->item_discount }}
                                                        @elseif ($item->name3 == $sell->unit)
                                                            {{ $item->price3 * $sell->product_qty - $sell->item_discount }}
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            @if ($invoice->sale_price_category == 'Default')
                                                @if ($invoice->type == 'Whole Sale')
                                                    {{ $sell->product_price * $sell->product_qty - ($sell->product_price * $sell->product_qty * $sell->item_discount) / 100 }}
                                                @else
                                                    {{ $sell->retail_price * $sell->product_qty - ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100 }}
                                                @endif
                                            @elseif ($invoice->sale_price_category == 'Whole Sale')
                                                {{ $sell->product_price * $sell->product_qty - ($sell->product_price * $sell->product_qty * $sell->item_discount) / 100 }}
                                            @elseif ($invoice->sale_price_category == 'Retail')
                                                {{ $sell->retail_price * $sell->product_qty - ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100 }}
                                            @else
                                                @foreach ($items as $item)
                                                    @if ($item->item_name == $sell->part_number)
                                                        @if ($item->name1 == $sell->unit)
                                                            {{ $item->price1 * $sell->product_qty - ($item->price1 * $sell->product_qty * $sell->item_discount) / 100 }}
                                                        @elseif ($item->name2 == $sell->unit)
                                                            {{ $item->price2 * $sell->product_qty - ($item->price2 * $sell->product_qty * $sell->item_discount) / 100 }}
                                                        @elseif ($item->name3 == $sell->unit)
                                                            {{ $item->price3 * $sell->product_qty - ($item->price3 * $sell->product_qty * $sell->item_discount) / 100 }}
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    {{-- @dd($invoice_no->sum('net_total')); --}}

                    <tfoot>
                        <tr style="line-height: 25px;">
                            <td colspan="5"></td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Sub Total</td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                                {{ $invoice_no->sum('net_total') ?? 0 }}</td>
                        </tr>
                        <tr style="line-height: 25px;">
                            <td colspan="5"></td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Item Discount
                            </td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                                @php
                                    $total_discount = 0;
                                @endphp
                                @foreach ($invoices as $key => $invoice)
                                    @foreach ($invoice->sells as $key => $sell)
                                        @if ($sell->ks_percent == 'ks')
                                            @php
                                                $total_discount += $sell->item_discount;
                                            @endphp
                                        @else
                                            @if ($invoice->sale_price_category == 'Default')
                                                @if ($invoice->type == 'Whole Sale')
                                                    @php
                                                        $total_discount +=
                                                            ($sell->product_price *
                                                                $sell->product_qty *
                                                                $sell->item_discount) /
                                                            100;
                                                    @endphp
                                                @else
                                                    @php
                                                        $total_discount +=
                                                            ($sell->retail_price *
                                                                $sell->product_qty *
                                                                $sell->item_discount) /
                                                            100;
                                                    @endphp
                                                @endif
                                            @elseif ($invoice->sale_price_category == 'Whole Sale')
                                                @php
                                                    $total_discount +=
                                                        ($sell->product_price *
                                                            $sell->product_qty *
                                                            $sell->item_discount) /
                                                        100;
                                                @endphp
                                            @elseif ($invoice->sale_price_category == 'Retail')
                                                @php
                                                    $total_discount +=
                                                        ($sell->retail_price *
                                                            $sell->product_qty *
                                                            $sell->item_discount) /
                                                        100;
                                                @endphp
                                            @else
                                                @foreach ($items as $item)
                                                    @if ($item->item_name == $sell->part_number)
                                                        @if ($item->name1 == $sell->unit)
                                                            @php
                                                                $total_discount +=
                                                                    ($item->price1 *
                                                                        $sell->product_qty *
                                                                        $sell->item_discount) /
                                                                    100;
                                                            @endphp
                                                        @elseif ($item->name2 == $sell->unit)
                                                            @php
                                                                $total_discount +=
                                                                    ($item->price2 *
                                                                        $sell->product_qty *
                                                                        $sell->item_discount) /
                                                                    100;
                                                            @endphp
                                                        @elseif ($item->name3 == $sell->unit)
                                                            @php
                                                                $total_discount +=
                                                                    ($item->price3 *
                                                                        $sell->product_qty *
                                                                        $sell->item_discount) /
                                                                    100;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                                {{ $total_discount ?? 0 }}
                            </td>
                        </tr>
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
            <div class="d-flex">
                <a onclick="printPage()" id="printButton" class="col-md-1 mx-3 mt-4 btn btn-primary">Print</a>
                <a href="{{ url('invoice') }}" id="back" class="col-md-1 mt-4 btn btn-danger ml-2">Back</a>
            </div>

        </div>
    </div>


</body>
<script>
    function printPage() {
        window.print();
    }
</script>

</html>
