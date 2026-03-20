<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <style>
        body {
            color: black;
            font-family: "Georgia", serif;

            /* Set text color for printing */
        }

        /* Add any other styles you want to modify for printing */


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

        /* .invoice-header {
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .invoice-title {
            color: #4CAF50;
            font-weight: bold;
        } */

        .stamp {
            border: 2px solid #000;
            padding: 3px 10px;
            font-weight: bold;
            color: #0047AB;
        }

        table th {
            background-color: #b9c3b9 !important;
            font-size: 14px;
        }

        td {
            font-size: 12px;
        }

        p {
            font-size: 13px;
        }
    </style>
</head>

<body class="p-4">
    <div class="container  p-4">
        <!-- Header -->
        <div class="row invoice-header">
            {{-- <div class="col-2 ">
                @if ($profile)
            @if ($profile->logos)

                    <img src="{{ asset('logos/' . ($profile->logos ?? '')) }}" alt="{{ $profile->name ?? '' }} pos"
                        width="100" height="60">

            @endif

        @endif
            </div> --}}
            {{-- <div class="col-8">
                <h4 class="invoice-title">Golden ZH Co., Ltd.</h4>
                <p class="mb-0"><i class="fa-solid fa-house-chimney"></i>&nbsp;&nbsp;&nbsp;  {{ $profile->address ?? '' }}</p>
                <p class="mb-0"><i class="fa-solid fa-phone-volume"></i>&nbsp;&nbsp;&nbsp;{{ $profile->phno1 ?? '' }}@if ($profile && $profile->phno2) , {{ $profile->phno2 ?? '' }}@endif</p>
                <p class="mb-0"><i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;&nbsp;{{ $profile->email ?? '' }}</p>
            </div> --}}
            <img src="{{ asset('img/golden.png') }}" alt="{{ $profile->name ?? '' }} pos" width="80" height="150">
        </div>

        <!-- Invoice Info -->
        <h5 class="text-center my-1 fw-bold">INVOICE</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <p>Customer: <br>
                            Payment Type: <br>
                            Sales Man:
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0">{{ $invoice->customer_name }}
                            <br>{{ $invoice->category ?? '' }}
                            <br>{{ $invoice->SaleBy->name ?? '' }}
                        </p>
                    </div>
                </div>

            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4 text-end">
                <div class="row">
                    <div class="col-md-6 text-start">
                        <p>Voucher No: <br>
                            Date:<br>
                            Delivery No:</p>
                    </div>
                    <div class="col-md-6">
                        <p>{{ $invoice->invoice_no }} <br>
                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}
                            <br>
                            {{ $invoice->delivery_no ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="table table-bordered mt-1">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Sales Price (MMK)</th>
                    <th>Amount (MMK)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $overallDiscountTotal = $invoice_no->sum('discount_total') ?? 0;

                    // Find firstSell before loops
                    $firstSell = null;
                    foreach ($invoiceWithSells as $invoice_s) {
                        $found = collect($invoice_s['invoice_sells'])->first(function ($sell) {
                            return $sell->super == '0';
                        });
                        if ($found) {
                            $firstSell = $found;
                            break;
                        }
                    }
                @endphp

                @foreach ($invoiceWithSells as $invoice_s)
                    @foreach ($invoice_s['invoice_sells'] as $key => $sell)
                        @if ($firstSell)
                            <tr class="text-center ">
                                <td>{{ $no++ }}.</td>
                                <td class="border text-start py-1">
                                    {{ $sell->product_name }}{{ $sell->GetVar? $sell->GetVar->lot_no : ''}}</td>
                                <td class="text-center pe-1">
                                    {{ $sell->product_qty }} {{ $sell->unit }}
                                </td>

                                <td class="text-end pe-1">
                                    @if ($invoice->sale_price_category == 'Default')
                                        @if ($invoice->type == 'Whole Sale')
                                            {{ number_format($sell->product_price) }}
                                        @else
                                            {{ number_format($sell->retail_price) }}
                                        @endif
                                    @elseif ($invoice->sale_price_category == 'Whole Sale')
                                        {{ number_format($sell->product_price) }}
                                    @elseif ($invoice->sale_price_category == 'Retail')
                                        {{ number_format($sell->retail_price) }}
                                    @else
                                        @foreach ($items as $item)
                                            @if ($item->item_name == $sell->part_number)
                                                @if ($item->name1 == $sell->unit)
                                                    {{ number_format($item->price1) }}
                                                @elseif ($item->name2 == $sell->unit)
                                                    {{ number_format($item->price2) }}
                                                @elseif ($item->name3 == $sell->unit)
                                                    {{ number_format($item->price3) }}
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </td>

                                <td class="text-center pe-1">
                                    @if ($sell->ks_percent == 'Ks')
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
                        @else
                            <tr class="text-center ">
                                <td>{{ $no++ }}.</td>
                                <td class="border text-start py-1">
                                    {{ $sell->product_name }} ({{ $sell->GetVar->lot_no }})</td>
                                <td class="text-center pe-1">
                                    {{ $sell->product_qty }} {{ $sell->unit }}
                                </td>

                                <td class="text-end pe-1">
                                    @if ($invoice->sale_price_category == 'Default')
                                        @if ($invoice->type == 'Whole Sale')
                                            {{ number_format($sell->special_price) }}
                                        @else
                                            {{ number_format($sell->special_price) }}
                                        @endif
                                    @elseif ($invoice->sale_price_category == 'Whole Sale')
                                        {{ number_format($sell->special_price) }}
                                    @elseif ($invoice->sale_price_category == 'Retail')
                                        {{ number_format($sell->special_price) }}
                                    @else
                                        @foreach ($items as $item)
                                            @if ($item->item_name == $sell->part_number)
                                                @if ($item->name1 == $sell->unit)
                                                    {{ number_format($item->price1) }}
                                                @elseif ($item->name2 == $sell->unit)
                                                    {{ number_format($item->price2) }}
                                                @elseif ($item->name3 == $sell->unit)
                                                    {{ number_format($item->price3) }}
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </td>

                                <td class="text-center pe-1">
                                    @if ($sell->ks_percent == 'Ks')
                                        @if ($invoice->sale_price_category == 'Default')
                                            @if ($invoice->type == 'Whole Sale')
                                                {{ number_format($sell->special_price * $sell->product_qty - $sell->super_item_discount) }}
                                            @else
                                                {{ number_format($sell->special_price * $sell->product_qty - $sell->super_item_discount) }}
                                            @endif
                                        @elseif ($invoice->sale_price_category == 'Whole Sale')
                                            {{ number_format($sell->special_price * $sell->product_qty - $sell->super_item_discount) }}
                                        @elseif ($invoice->sale_price_category == 'Retail')
                                            {{ number_format($sell->special_price * $sell->product_qty - $sell->super_item_discount) }}
                                        @else
                                            @foreach ($items as $item)
                                                @if ($item->item_name == $sell->part_number)
                                                    @if ($item->name1 == $sell->unit)
                                                        {{ number_format($item->price1 * $sell->product_qty - $sell->super_item_discount) }}
                                                    @elseif ($item->name2 == $sell->unit)
                                                        {{ number_format($item->price2 * $sell->product_qty - $sell->super_item_discount) }}
                                                    @elseif ($item->name3 == $sell->unit)
                                                        {{ number_format($item->price3 * $sell->product_qty - $sell->super_item_discount) }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @else
                                        @if ($invoice->sale_price_category == 'Default')
                                            @if ($invoice->type == 'Whole Sale')
                                                {{ number_format($sell->special_price * $sell->super_item_discount - ($sell->special_price * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                            @else
                                                {{ number_format($sell->special_price * $sell->product_qty - ($sell->special_price * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                            @endif
                                        @elseif ($invoice->sale_price_category == 'Whole Sale')
                                            {{ number_format($sell->special_price * $sell->product_qty - ($sell->special_price * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                        @elseif ($invoice->sale_price_category == 'Retail')
                                            {{ number_format($sell->special_price * $sell->product_qty - ($sell->special_price * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                        @else
                                            @foreach ($items as $item)
                                                @if ($item->item_name == $sell->part_number)
                                                    @if ($item->name1 == $sell->unit)
                                                        {{ number_format($item->price1 * $sell->product_qty - ($item->price1 * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                                    @elseif ($item->name2 == $sell->unit)
                                                        {{ number_format($item->price2 * $sell->product_qty - ($item->price2 * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                                    @elseif ($item->name3 == $sell->unit)
                                                        {{ number_format($item->price3 * $sell->product_qty - ($item->price3 * $sell->product_qty * $sell->super_item_discount) / 100) }}
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
            @if ($firstSell)
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">ကျသင့်ငွေ</td>
                        <td class="text-center">{{ number_format($invoice_no->sum('net_total')) ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">လျှော့ငွေ</td>
                        <td class="text-center">{{ number_format($invoice->discount_total) ?? 0.0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">စုစုပေါင်း</td>
                        <td class="text-center">{{ number_format($invoice->total) ?? '0.00' }}</td>
                    </tr>
                </tfoot>
            @else
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">ကျသင့်ငွေ</td>
                        <td class="text-center">{{ number_format($invoice_no->sum('super_net_total')) ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">လျှော့ငွေ</td>
                        <td class="text-center">{{ number_format($invoice->super_discount) ?? 0.0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">စုစုပေါင်း</td>
                        <td class="text-center">{{ number_format($invoice->super_total) ?? '0.00' }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Signatures -->
        <div class="row text-center mt-5">
            <div class="col-md-4">
                <p style="font-size: 12px;">Delivered by:</p>
                <p>___________<br></p>
            </div>
            <div class="col-md-4">
                <p style="font-size: 12px;">Received by:</p>
                <p>___________<br></p>
            </div>
            <div class="col-md-4">
                <p style="font-size: 12px;">Cash Received by:</p>
                <p>___________<br></p>

            </div>
        </div>
        <div class="d-flex">
            <a onclick="printPage()" id="printButton" class="col-md-1 mx-3 mt-4 btn btn-primary">Print</a>
            <a href="{{ url('invoice') }}" id="back" class="col-md-1 mt-4 btn btn-danger ml-2">Back</a>
        </div>
    </div>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</body>

</html>
