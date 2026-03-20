<!DOCTYPE html>
<HTML>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}"" />

    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>

    <script src="{{ asset('backend/js/moment2103.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
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
            <div class="gap-2 pt-4 col-6 d-flex align-items-center justify-content-end ">
                <a onclick="printPage()" id="printButton" class=" btn btn-success" class="btn btn-primary"
                    style="border-radius:10px;"><i class="fa-solid fa-print text-white"></i> Print</a>


                <a href="{{ route('pos_daily_sales') }}" class="no-print btn btn-primary" style="border-radius:10px;"><i
                        class="fa-regular fa-calendar-days"></i> Daily Sales</a>
                <a href="{{ url('pos_register') }}" class="no-print text-white btn btn-primary"
                    style="border-radius:10px;"><i class="fa-solid fa-circle-plus"></i> POS Register</a>
                <a href="{{ url('pos_register') }}" class="no-print btn btn-danger" style="border-radius:10px;"><i
                        class="fa-solid fa-arrow-left"></i> Back</a>
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
        <div class="row mt-2">
            <h6 class="text-center fw-bold">{{ $profile->name ?? '' }}</h6>

            <p class="text-center fw-bold mb-2" style="font-size: 10px;line-height:25px;">
                {{ $profile->address ?? '' }}
                <br>
                {{ $profile->phno1 ?? '' }}@if (!empty($profile->phno2))
                    ,{{ $profile->phno2 ?? '' }}
                @endif
            </p>


        </div>
        <div class="mt-2 row">
            {{-- <h6 class="text-center">Sales Receipt<br>
                {{ $invoice->created_at }}</h6> --}}
            <p class="text-center" style="font-size: 10px"> Sales Receipt<br>{{ $invoice->created_at }}</p>
        </div>

        <div class="mt-2 row">
            <p class="fw-bold" style="font-size: 10px;">Sale ID: {{ $invoice->invoice_no }}<br>Employee : </p>
            <div class="mt-1 table-responsive">
                <table class="mt-1" style="font-size: 10px;width:100%">
                    <thead>
                        <tr class="text-left">
                            <th style="width: 30%;">Item Name.</th>
                            <th style="width: 25%;">Quantity</th>
                            <th style="width: 15%;">Price</th>
                            <th style="width: 15%;font-size: 10px;">Discount</th>
                            <th class="text-end" style="width: 10%;">Total</th>
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
                                    <td style="font-size: 10px;">{{ $sell->product_qty }}</td>

                                    <td style="font-size: 10px;">

                                        {{ $sell->retail_price }}

                                    </td>
                                    <td style="font-size: 10px;">
                                        {{ $sell->item_discount }}{{ ' ' }}{{ $sell->ks_percent }}
                                    </td>
                                    <td class="text-end">
                                        @if ($sell->ks_percent == 'Ks')
                                            {{ $sell->retail_price * $sell->product_qty - $sell->item_discount }}
                                        @else
                                            {{ $sell->retail_price * $sell->product_qty - ($sell->retail_price * $sell->product_qty * $sell->item_discount) / 100 }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    {{-- <tfoot style="border-top: 2px solid black !important;font-size: 10px;">
                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Sub Total</td>
                            <td class="text-end fw-bold">{{ $invoice->net_total }}</td>
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
                                            @php
                                                $total_discount +=
                                                    ($sell->retail_price * $sell->product_qty * $sell->item_discount) /
                                                    100;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endforeach

                                {{ $total_discount }}

                        </tr>

                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Overall Discount</td>
                            <td class="text-end fw-bold">
                                {{ $invoice->discount_total ?? 0 }}
                            </td>
                        </tr>
                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">
                                {{ $invoice->total }}

                            </td>
                        </tr>



                    </tfoot> --}}
                    <tfoot style="border-top: 2px solid black !important;font-size: 10px;">
                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Sub Total</td>
                            <td class="text-end fw-bold">{{ $invoice->net_total }}</td>
                        </tr>

                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Item Discount</td>
                            <td class="text-end fw-bold">
                                @php
                                    $total_discount = 0;
                                @endphp
                                @foreach ($invoiceWithSells as $invoice_s)
                                    @foreach ($invoice_s['invoice_sells'] as $sell)
                                        @php
                                            $discount =
                                                $sell->ks_percent == 'Ks'
                                                    ? $sell->item_discount
                                                    : ($sell->retail_price *
                                                            $sell->product_qty *
                                                            $sell->item_discount) /
                                                        100;
                                            $total_discount += $discount;
                                        @endphp
                                    @endforeach
                                @endforeach

                                {{ $total_discount }}
                            </td>
                        </tr>

                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Overall Discount</td>
                            <td class="text-end fw-bold">
                                {{ $invoice->discount_total ?? 0 }}
                            </td>
                        </tr>

                        <tr style="line-height: 20px;">
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">
                                {{ $invoice->total }}
                            </td>
                        </tr>
                    </tfoot>

                </table>
                <div class="text-center mt-3" id="thank">
                    <p class="text-center fw-bold" style="font-size: 12px;">ဝယ်ပြီးပစ္စည်း ပြန်မလဲပါ။ <br>
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
