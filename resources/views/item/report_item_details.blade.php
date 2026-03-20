<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Part Details</title>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<style>
    @media print {
        #calculate {
            display: none;
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

<style>
    @media print {
        body {
            font-size: 12px;
            color: #333;
            text-align: center;
            /* Center the content horizontally */
        }

        .container {
            width: 100%;
            margin: 0 auto;
            /* Center the container horizontally */
            padding: 0;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: none;
            text-align: left;
            /* Reset text alignment for card content */
        }

        .card-header {
            background-color: #f0f0f0;
            border-bottom: 1px solid #ccc;
            padding: 10px 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .btn {
            display: none;
        }
    }

    .user-name {
        /* Add your styling here */
        color: red;
        /* For example, set the text color to red */
        font-weight: bold;
        /* Set the font weight to bold */
        /* Add more styles as needed */
    }

    .fw-large {
        /* font-weight: bold; */
        font-size: larger;
        /* Add any other styles you want */
    }
</style>




<body>
    <div class="container mt-3">
        <div class="card p-4">
            <div class="card-header" style="">
                <h4 style="font-size: 18px" class="fw-semibold">Items Details</h4>
            </div>

            <div class="">
                <h3 class="col-md-12 mt-2">
                    @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                        <div class="frmSearch col-sm-4">
                            <label for="" style="font-size: 20px;">Item Location</label>
                            <select name="item_location" id="item_location" class="form-control">

                                <option value="{{ $items->warehouse_id }}"">{{ $items->warehouse->name ?? 'N/A' }}
                                </option>

                            </select>
                        </div>
                    @else
                        <div class="frmSearch col-sm-4" style="display: none;">
                            <label for="" style="font-size: 20px;">Item Location</label>
                            <select name="item_location" id="item_location" class="form-control">
                                @foreach ($all_items as $item)
                                    @if ($item->warehouse_id == auth()->user()->level)
                                        <option value="{{ $item->warehouse_id }}" selected>
                                            {{ $item->warehouse->name ?? 'N/A' }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                    @endif

                </h3>
            </div>
            {{-- <a href="{{ route('show2', $item->id) }}">
                <button type="button" class="btn btn-success" id="calculate"
                    style="margin-top:20px;margin-bottom:20px;margin-left:20px;">
                    In/out History
                </button>
            </a> --}}
            <div class="items-container">

                <div style="flex: 4;" data-warehouse-id="{{ $items->warehouse_id }}" class="item">

                    <table class='table table-bordered mt-4' style="font-size: 20">

                        @if ($items->status == 'item_kit')
                            <form action="{{ url('item_kit_edit', $items->id) }}" method="GET" class="mr-2">
                                <input type="hidden" name="item_name" value="{{ $items->item_name }}">
                                <button type="submit" class="btn btn-success btn-sm mt-3">
                                    Edit
                                </button>
                            @else
                                <form action="{{ url('item_edit', $items->id) }}" method="GET" class="mr-2">
                                    <input type="hidden" name="item_name" value="{{ $items->item_name }}">
                                    <button type="submit" class="btn btn-success btn-sm mt-3">
                                        Edit
                                    </button>
                                </form>
                        @endif
                        {{-- <a href="{{ url('in_out', $items->id) }}" class="btn btn-info btn-sm mt-3 mx-1">In/Out
                                History </a> --}}
                        <tr>
                        <tr>
                            <td class="fw-light" style="width: 200px;">Item Name</td>
                            <td class="fw-normal" style="width: 200px;">{{ $items['item_name'] }}</td>
                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">Item Descriptions
                            </td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items['descriptions'] }}
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">Item Category</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items['category'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Location</td>
                            <td class="fw-normal" style="width: 200px;" id>
                                {{ $items->warehouse->name ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Type</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items->market }}

                            </td>
                        </tr>
                        @foreach ($variations as $variation)
                            @if ($variation->item_id == $items->id)
                                <tr>
                                    <td class="fw-light" style="width:200px">
                                        {{ Str::ucfirst($variation->product_code) }} Quantity</td>
                                    <td class="fw-normal" style="width: 200px;">

                                        @php

                                            $level1qty = 0;
                                            $level2qty = 0;
                                            $level3qty = 0;

                                            // Calculate level 1 quantity
                                            $lvl1 = floor(
                                                intval($variation->quantity) /
                                                    (intval($variation->unit2) !== 0 ? intval($variation->unit2) : 1),
                                            );

                                            $level1qty = $lvl1;

                                            // Calculate remaining quantity for level 2
                                            $first_lvl2 = fmod($variation->quantity, (float) ($variation->unit2 ?? 1));
                                            $level2qty = floor($first_lvl2);

                                            // Calculate fractional part for level 3
                                            $level3_fractional = ($first_lvl2 - $level2qty) * (float) $variation->unit3;
                                            $level3qty = ceil($level3_fractional - 0.5);
                                        @endphp
                                        @if ($level1qty != 0)
                                            {{ $level1qty }}{{ ' ' }} {{ $variation->name1 }}
                                        @endif
                                        @if ($level2qty != 0 && $level2qty > 0)
                                            {{ $level2qty }}{{ ' ' }} {{ $variation->name2 }}
                                        @endif
                                        @if ($level3qty != 0 && $level3qty > 0)
                                            {{ $level3qty }}{{ ' ' }} {{ $variation->name3 }}
                                        @endif

                                        @if ($level1qty == 0 && is_numeric($level2qty) && is_numeric($level3qty))
                                            0 {{ $variation->name1 }}
                                        @endif


                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        {{-- <tr>
                                <td class="fw-light" style="width:200px">Total Quantity</td>
                                <td class="fw-normal" style="width: 200px;">
                                    @if ($items->variations->isNotEmpty())
                                        {{ $items->variations->sum(fn($variation) => $variation->quantity / $variation->unit2) }}
                                    @else
                                        '0'
                                    @endif
                                </td>
                            </tr> --}}
                    </table>


                    <hr style="border: 2px solid black;">

                    <h6 class="mt-5">Item Variations</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-2">
                            <thead>
                                <tr class="font-bold text-black">
                                    <th>Expired Date</th>
                                    <th>Descriptions</th>
                                    <th>Product Code</th>
                                    <th>Barcode</th>

                                    <th>Stock Alert</th>
                                    <th>Generate</th>
                                    <th>Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($variations as $variation)
                                    @if ($variation->item_id == $items->id)
                                        <tr class="font-bold text-black" style="background-color: gainsboro">
                                            <td>{{ $variation->expired_date }}</td>
                                            <td>{{ $variation->descriptions }}</td>
                                            <td>{{ $variation->product_code }}</td>
                                            <td>{{ $variation->variations_barcode }}</td>

                                            <td>{{ $variation->reorder_level_stock }}</td>
                                            <td>
                                                <a href="{{ url('barcode', $variation->id) }}"
                                                    class="mt-1 text-white btn btn-warning btn-sm">Generate</a>
                                            </td>
                                            <td> <a href="{{ url('in_out', $variation->id) }}"
                                                    class="mt-1 btn btn-info btn-sm">In/Out
                                                    History </a></td>
                                        </tr>
                                        <tr class="font-bold text-black">
                                            <td colspan="8">
                                                <table class="table mt-2">
                                                    <thead>
                                                        <tr>
                                                            <th>Level</th>
                                                            <th>Unit</th>
                                                            <th>Name</th>
                                                            <th>Purchase Price</th>
                                                            <th>Wholesale Price</th>
                                                            <th>Retail Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>{{ $variation->unit1 ?? 'N/A' }}</td>
                                                            <td>{{ $variation->name1 ?? 'N/A' }}</td>
                                                            <td>{{ $variation->price1 ?? 'N/A' }}</td>
                                                            <td>{{ $variation->wholesale1 ?? 'N/A' }}</td>
                                                            <td>{{ $variation->retail1 ?? 'N/A' }}</td>
                                                        </tr>
                                                        @if ($variation->name2 != null)
                                                            <tr>
                                                                <td>2</td>

                                                                <td>{{ $variation->unit2 ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ $variation->name2 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->price2 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->wholesale2 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->retail2 ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endif
                                                        @if ($variation->name3 != null)
                                                            <tr>
                                                                <td>3</td>
                                                                <td>{{ $variation->unit3 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->name3 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->price3 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->wholesale3 ?? 'N/A' }}</td>
                                                                <td>{{ $variation->retail3 ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    @if ($items['status'] == 'item_kit')
                        <hr style="border: 2px solid black;">
                        <h6 class="mt-5">Collaborated
                            Items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-2">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Unit</th>
                                        <th>Barcode</th>
                                        <th>Buy Price</th>
                                        <th>Wholesale Price</th>
                                        <th>Retail Price</th>
                                        <th>Generate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($items->itemKits as $item_kit)
                                    <tr>
                                        <td>{{ $item_kit->item_name }}</td>
                                        <td>{{ $item_kit->item_unit }}</td>



                                        @if ($item_kit->item_unit == $item_kit->item->name1)
                                            <td>{{ $item_kit->item->price1 }}</td>
                                            <td>{{ $item_kit->item->retail1 }}</td>
                                            <td>{{ $item_kit->item->wholesale1 }}</td>
                                        @endif
                                        @if ($item_kit->item_unit == $item_kit->item->name2)
                                            <td>{{ $item_kit->item->price2 }}</td>
                                            <td>{{ $item_kit->item->retail2 }}</td>
                                            <td>{{ $item_kit->item->wholesale2 }}</td>
                                        @endif
                                        @if ($item_kit->item_unit == $item_kit->item->name3)
                                            <td>{{ $item_kit->item->price3 }}</td>
                                            <td>{{ $item_kit->item->retail3 }}</td>
                                            <td>{{ $item_kit->item->wholesale3 }}</td>
                                        @endif
                                    </tr>
                                @endforeach --}}
                                    @foreach ($items->itemKits as $item_kit)
                                        <tr>
                                            <td>{{ $item_kit->product_name }}</td>
                                            <td>{{ $item_kit->item_unit }}</td>
                                            @foreach ($item_kit->variations as $variation)
                                                <td>{{ $variation->variations_barcode }}</td>
                                                @if ($item_kit->item_unit == $variation->name1)
                                                    <td>{{ $variation->price1 }}</td>
                                                    <td>{{ $variation->retail1 }}</td>
                                                    <td>{{ $variation->wholesale1 }}</td>
                                                @elseif ($item_kit->item_unit == $variation->name2)
                                                    <td>{{ $variation->price2 }}</td>
                                                    <td>{{ $variation->retail2 }}</td>
                                                    <td>{{ $variation->wholesale2 }}</td>
                                                @elseif ($item_kit->item_unit == $variation->name3)
                                                    <td>{{ $variation->price3 }}</td>
                                                    <td>{{ $variation->retail3 }}</td>
                                                    <td>{{ $variation->wholesale3 }}</td>
                                                @endif
                                                <td> <a href="{{ url('barcode', $variation->id) }}"
                                                        class="mt-1 text-white btn btn-warning btn-sm">Generate</a>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
            <div class="mt-3 d-flex justify-content-end">
                <a type="button" id="printButton" class="btn btn-primary m-2" onclick="printPage()">Print</a>
                @if ($item_status == 'item_kit')
                    <a type="button" id="printButton" class="m-2 excelButton btn btn-danger"
                        href="{{ url('item_kits') }}">Back</a>
                @else
                    <a type="button" id="printButton" class="m-2 excelButton btn btn-danger"
                        href="{{ url('items') }}">Back</a>
                @endif
            </div>
        </div>
    </div>

    </div>
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script>
        function printPage() {
            window.print();
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectedLocation = document.getElementById('item_location').value;
            var items = document.querySelectorAll('.item');


            function filterItems() {
                var selectedLocation = document.getElementById('item_location').value;
                items.forEach(function(item) {

                    var warehouseId = item.getAttribute('data-warehouse-id');
                    console.log(selectedLocation);
                    console.log(warehouseId);
                    if (selectedLocation === "" || warehouseId == selectedLocation) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            document.getElementById('item_location').addEventListener('change', filterItems);
            filterItems();
        });
    </script>
</body>

</html>
