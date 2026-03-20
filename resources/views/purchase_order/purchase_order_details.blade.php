<!DOCTYPE html>
<html>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>

    <script src="{{ asset('backend/js/moment2103.js') }}"></script>
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
            <div class="mt-2 text-center">
                <img src="{{ asset('logos/' . $profile->logos ?? '') }}" alt="sse pos" width="100" height="60">
            </div>
        @endif
        <div class="row" style="margin-top: 20px;">
            <h5 class="text-center fw-bold">{{ $profile->name ?? '' }}</h5>
            <p class="text-center fw-bold mb-2" style="font-size: 13px;line-height:25px;">
                {{ $profile->address ?? '' }}
                <br>{{ $profile->phno1 ?? '' }} , {{ $profile->phno2 ?? '' }}
            </p>
            <hr style="color: red !important; background-color: red  !important; height: 2px !important;">
            <div class="row">
                <p class="text-center fw-bold" style="font-size: 13px;"><i>*** မှားယွင်းမှုတစ်စုံတစ်ရာရှိပါက (24)
                        နာရီအတွင်းအကြောင်းကြားပေးပါရန် ***</i></p>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="table-responsive">
                <table class="mt-1" style="font-size: 13px;width:100%;">
                    <thead>
                        <tr class="">
                            @if ($purchase_order->balance_due !== 'Sale Return Invoice')
                                <th colspan="4" class="py-1 ps-1 fw-bold"
                                    style="border: 1px solid black !important;">
                                    Supplier Name - {{ $purchase_order->supplier->name ?? 'N/A' }}</th>
                                <th colspan="3" class="py-1 ps-1" style="border: 1px solid black !important;">Date -
                                    {{ $purchase_order->po_date ? $purchase_order->po_date : $purchase_order->created_at->format('Y-m-d') }}
                                </th>
                            @else
                                <th colspan="7" class="py-1 ps-1" style="border: 1px solid black !important;">Date -
                                    {{ $purchase_order->po_date ? $purchase_order->po_date : $purchase_order->created_at->format('Y-m-d') }}
                                </th>
                            @endif


                        </tr>
                        <tr class="text-center">
                            <th class="py-1" style="width: 5%; border: 1px solid black !important;">No.</th>
                            <th class="py-1" style="width: 30%; border: 1px solid black !important;">Item Name.</th>
                            <th class="py-1" style="width: 10%; border: 1px solid black !important;">Qty</th>
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Company Price
                            </th>
                            <th class="py-1" style="width: 10%; border: 1px solid black !important;">Discount</th>
                            <th class="py-1" style="width: 15%; border: 1px solid black !important;">Commercial Tax
                            </th>

                            <th class="py-1" style="width: 20%; border: 1px solid black !important;">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="border: 1px solid black !important;">
                        {{-- $purchaseOrdersWithSells = []; --}}
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($purchaseOrdersWithSells as $purchaseOrder)
                            @foreach ($purchaseOrder['sells'] as $key => $sell)
                                <tr class="text-center fw-bold">
                                    <td style="border: 1px solid black !important;">{{ $no++ }}.</td>
                                    <td class="border py-1" style="border: 1px solid black !important;">
                                        {{ $sell->product_name }}</td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->totalQty }}{{ $sell->unit }}
                                    </td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->company_price }}
                                    </td>

                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->discount }}
                                    </td>
                                    <td class="text-center pe-1" style="border: 1px solid black !important;">
                                        {{ $sell->commercial_tax }}
                                    </td>
                                    <td class="text-end pe-1" style="border: 1px solid black !important;">


                                        {{ $sell->totalQty * $sell->company_price }}


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

                                {{ $purchase_orders->sum('net_total') }}
                            </td>
                        </tr>
                        <tr style="line-height: 25px;">
                            <td colspan="5"></td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Total Discount
                            </td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                                {{ $purchase_orders->sum('discount_total') ?? 0 }}
                            </td>
                        </tr>
                        <tr style="line-height: 25px;">
                            <td colspan="5"></td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Total
                                Discount(%)</td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                                {{ optional($purchase_orders->first())->invoice_category }}

                            </td>

                        </tr>
                        <tr style="line-height: 25px;">
                            <td colspan="5"></td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">Total </td>
                            <td class="text-end fw-bold pe-1" style="border: 1px solid black !important;">
                                {{ $purchase_orders->sum('total') }}
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
        </div>
        <div class="d-flex">
            <a onclick="printPage()" id="printButton" class="col-md-1 mx-3 mt-4 btn btn-primary">Print</a>
            <a href="{{ url('purchase_order_manage ') }}" id="back"
                class="col-md-1 mt-4 btn btn-danger ml-2">Back</a>
        </div>

    </div>

</body>
<script>
    function printPage() {
        window.print();
    }
</script>

</html>
