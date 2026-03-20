<!DOCTYPE html>
<HTML>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>
    <script src="{{ asset('backend/js/moment2103.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        @media print {
            #thank {
                display: block !important;
            }
        }

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

            #print1 {
                display: none;
            }

            #print2 {
                display: none;
            }

            #print3 {
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


</head>

<body>

    <div class="container mx-auto">
        <div class="row">
            <div class="col-6">
            </div>
            <div class="gap-2 pt-2 col-6 d-flex align-items-center justify-content-end">
                <a onclick="printPage()" id="printButton" class=" btn btn-success" class="btn btn-primary"
                    style="border-radius:10px;"><i class="fa-solid fa-print text-white"></i> Print</a>
                <a id="test" href="{{ url('invoice') }}" class="btn btn-danger" style="border-radius:10px;"><i
                        class="fa-solid fa-backward text-white"></i> Back</a>
            </div>
        </div>

        @if ($profile)
            @if ($profile->logos)
                <div class="mt-2 text-center">
                    <img src="{{ asset('logos/' . ($profile->logos ?? '')) }}" alt="{{ $profile->name ?? '' }} pos"
                        width="100" height="60">
                </div>
            @endif

        @endif
        <div class="row" style="margin-top: 30px;">
            <h6 class="text-center fw-bold">{{ $profile->name ?? '' }}</h6>

            <p class="text-center fw-bold" style="font-size: 10px;">
                {{ $profile->address ?? '' }}
                <br>
                {{ $profile->phno1 ?? '' }} @if (isset($profile->phno2))
                    ,
                    {{ $profile->phno2 ?? '' }}
                @endif


            </p>
        </div>
        {{-- @endif --}}

        <div class="row">
            <p class="text-center" style="font-size: 10px;">Sales Receipt<br>
                <?= $currentDate = date('d-m-Y') ?></p>
        </div>

        <div class="row">
            <p class="fw-bold" style="font-size: 10px;">Sale ID: {{ $invoice->invoice_no }}
                <br>Employee :
                {{ auth()->user()->name }}
                <br>Customer :
                {{ $invoice->customer_name }}


            </p>

            <div class=" table-responsive">
                <table class="" style="font-size: 14px;width:100%">
                    <thead>
                        <tr class="text-left">
                            <th style="width: 40%;font-size: 10px;">Product Name.</th>
                            <th style="width: 10%;font-size: 10px;">Qty</th>


                            <th style="width: 15%;font-size: 10px;">Price</th>
                            <th style="width: 15%;font-size: 10px;">Discount</th>
                            <th class="text-end" style="width: 10%;font-size: 10px;">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="height:30px">
                        @php
                            $overallDiscountTotal = $invoice_no->sum('discount_total') ?? 0;
                        @endphp
                        @foreach ($invoiceWithSells as $invoice_s)
                            @foreach ($invoice_s['invoice_sells'] as $key => $sell)
                                <tr class="text-start">
                                    <td style="font-size: 10px;">{{ $sell->product_name }}</td>
                                    <td style="font-size: 10px;">{{ $sell->product_qty }} {{ $sell->unit }}</td>

                                    <td style="font-size: 10px;">
                                        @if ($invoice->sale_price_category == 'Default')
                                            @if ($invoice->type == 'HD')
                                                {{ number_format($sell->product_price) }}
                                            @else
                                                {{ number_format($sell->retail_price) }}
                                            @endif
                                        @elseif ($invoice->sale_price_category == 'HD')
                                            {{ number_format($sell->product_price) }}
                                        @elseif ($invoice->sale_price_category == 'Clinic')
                                            {{ number_format($sell->retail_price) }}
                                        @else
                                            {{ number_format($sell->retail_price) }}
                                        @endif
                                    </td>
                                    <td style="font-size: 10px;">
                                        {{ number_format($sell->item_discount) }}{{ ' ' }}{{ $sell->ks_percent }}

                                    </td>

                                    <td class="text-end" style="font-size: 10px;">
                                        @if ($sell->ks_percent == 'ks')
                                            @if ($invoice->sale_price_category == 'Default')
                                                @if ($invoice->type == 'Whole Sale')
                                                    {{ number_format($sell->product_price * $sell->product_qty - $sell->item_discount) }}
                                                @else
                                                    {{ number_format($sell->retail_price * $sell->product_qty - $sell->item_discount) }}
                                                @endif
                                            @elseif ($invoice->sale_price_category == 'Whole Sale')
                                                {{ number_format($sell->product_price * $sell->product_qty - $sell->item_discount) }}
                                            @elseif ($invoice->sale_price_category == 'Retail')
                                                {{ number_format($sell->retail_price * $sell->product_qty - $sell->item_discount) }}
                                            @else
                                                @foreach ($items as $item)
                                                    @if ($item->item_name == $sell->part_number)
                                                        @if ($item->name1 == $sell->unit)
                                                            {{ number_format($item->price1 * $sell->product_qty - $sell->item_discount) }}
                                                        @elseif ($item->name2 == $sell->unit)
                                                            {{ number_format($item->price2 * $sell->product_qty - $sell->item_discount) }}
                                                        @elseif ($item->name3 == $sell->unit)
                                                            {{ number_format($item->price3 * $sell->product_qty - $sell->item_discount) }}
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            @if ($invoice->sale_price_category == 'Default')
                                                @if ($invoice->type == 'Whole Sale')
                                                    {{ number_format($sell->product_price * $sell->product_qty - ($sell->product_price * $sell->product_qty * $sell->item_discount) / 100) }}
                                                @else
                                                    {{ number_format($sell->retail_price * $sell->product_qty - ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100) }}
                                                @endif
                                            @elseif ($invoice->sale_price_category == 'Whole Sale')
                                                {{ number_format($sell->product_price * $sell->product_qty - ($sell->product_price * $sell->product_qty * $sell->item_discount) / 100) }}
                                            @elseif ($invoice->sale_price_category == 'Retail')
                                                {{ number_format($sell->retail_price * $sell->product_qty - ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100) }}
                                            @else
                                                @foreach ($items as $item)
                                                    @if ($item->item_name == $sell->part_number)
                                                        @if ($item->name1 == $sell->unit)
                                                            {{ number_format($item->price1 * $sell->product_qty - ($item->price1 * $sell->product_qty * $sell->item_discount) / 100) }}
                                                        @elseif ($item->name2 == $sell->unit)
                                                            {{ number_format($item->price2 * $sell->product_qty - ($item->price2 * $sell->product_qty * $sell->item_discount) / 100) }}
                                                        @elseif ($item->name3 == $sell->unit)
                                                            {{ number_format($item->price3 * $sell->product_qty - ($item->price3 * $sell->product_qty * $sell->item_discount) / 100) }}
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
                    <tfoot style="border-top: 2px solid black !important;font-size: 10px;">
                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Sub Total</td>
                            <td class="text-end fw-bold">{{ $invoice_no->sum('net_total') ?? 0 }}</td>
                        </tr>

                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Item Discount</td>
                            <td class="text-end fw-bold">
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
                                {{ number_format($total_discount) ?? 0 }}
                        </tr>

                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Overall Discount</td>
                            <td class="text-end fw-bold">{{ number_format($invoice->discount_total) ?? 0 }}</td>
                        </tr>
                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">{{ number_format($invoice->total) ?? 0 }}</td>
                        </tr>



                    </tfoot>
                </table>
                <div class="text-center mt-3" id="thank">
                    <p class="text-center fw-bold" style="font-size: 12px;">
                        အားပေးမှု့အတွက် ကျေးဇူးတင်ပါသည်။</p>
                    </p>
                </div>

            </div>

        </div>
    </div>

    <script>
        function printPage() {
            window.print();
        }
    </script>


</body>

</HTML>
