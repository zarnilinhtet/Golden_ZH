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
    <link rel="stylesheet" href="{{ asset('backend/css/fontawesome642.css') }}">
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
            {{-- <a href="{{ route('show2', $item->id) }}">
                <button type="button" class="btn btn-success" id="calculate"
                    style="margin-top:20px;margin-bottom:20px;margin-left:20px;">
                    In/out History
                </button>
            </a> --}}
            <div style="display: flex;">
                <div style="flex: 4;">
                    <table class='table table-bordered mt-5' style="font-size: 20">
                        <a href="{{ url('item_edit', $items->id) }}" class="btn btn-success mt-3"><i
                                class="fa-solid fa-pen-to-square"></i>Edit</a>

                        <a href="{{ url('in_out', $items->id) }}" class="btn btn-info mt-3 mx-1">In/Out
                            History </a>
                        <tr>
                        <tr>

                            <td class="fw-light" style="width: 200px;">Item Name</td>
                            <td class="fw-normal" style="width: 200px;">{{ $items['item_name'] }}</td>

                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">Barcode</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items['barcode'] }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Descriptions
                            </td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items['descriptions'] }}
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-light" style="width:200px">Expired Date</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items['expired_date'] }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Item Category</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items['category'] }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Warehouse</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items->warehouse->name }}


                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Market</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items['market'] }}

                            </td>
                        </tr>
                        <tr>
                            <td class="fw-light" style="width:200px">Quantity</td>
                            <td class="fw-normal" style="width: 200px;">
                                @php

                                    $level1qty = 0;
                                    $level2qty = 0;
                                    $level3qty = 0;

                                    // Calculate level 1 quantity
                                    $lvl1 = floor($items['quantity'] / ($items->unit2 ?? 1));
                                    $level1qty = $lvl1;

                                    // Calculate remaining quantity for level 2
                                    $first_lvl2 = fmod($items['quantity'], $items->unit2);
                                    $level2qty = floor($first_lvl2);

                                    // Calculate fractional part for level 3
                                    $level3_fractional = ($first_lvl2 - $level2qty) * $items->unit3;
                                    $level3qty = ceil($level3_fractional - 0.5);
                                @endphp
                                @if ($level1qty != 0)
                                    {{ $level1qty . $items->name1 }}
                                @endif
                                @if ($level2qty != 0 && $level2qty > 0)
                                    {{ $level2qty . $items->name2 }}
                                @endif
                                @if ($level3qty != 0 && $level3qty > 0)
                                    {{ $level3qty . $items->name3 }}
                                @endif
                                @if ($level1qty == 0 && $level2qty == 0 && $level3qty == 0)
                                    0{{ $items->name1 }}
                                @endif

                            </td>
                        </tr>


                        <tr>
                            <td class="fw-light" style="width:200px">Reorder Level Stock</td>
                            <td class="fw-normal" style="width: 200px;">
                                {{ $items['reorder_level_stock'] }}

                            </td>
                        </tr>

                        {{-- <tr>
                            <td class="fw-light" style="width:200px">ဝယ်စျေး</td>
                            <td class="fw-normal" style="width: 200px;">

                                {{ $items['price1'] }}


                            </td>
                        </tr> --}}
                    </table>

                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Category</th>
                                <th scope="col">ဝယ်စျေး</th>
                                <th scope="col">လက်ကားစျေး</th>
                                <th scope="col">လက်လီ‌စျေး</th>
                                <th> Profit</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $items['name1'] }}</td>
                                <td>{{ $items['price1'] }}</td>
                                <td>{{ $items['wholesale1'] }}</td>
                                <td>{{ $items['retail1'] }}</td>
                                <td>{{ $items['retail'] - $items['price1'] }}</td>


                            </tr>
                            <tr>
                                <td>{{ $items['name2'] }}</td>
                                <td>{{ $items['price2'] }}</td>
                                <td>{{ $items['wholesale2'] }}</td>
                                <td>{{ $items['retail2'] }}</td>
                                <td>{{ $items['retail2'] - $items['pirce2'] }}</td>

                            </tr>
                            <tr>
                                <td>{{ $items['name3'] }}</td>
                                <td>{{ $items['price3'] }}</td>
                                <td>{{ $items['wholesale3'] }}</td>
                                <td>{{ $items['retail3'] }}</td>
                                <td>{{ $items['retail3'] - $items['price3'] }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <a type="button" id="printButton" class="btn btn-primary m-2" onclick="printPage()">Print</a>
                <a type="button" id="printButton" class="m-2 excelButton btn btn-danger"
                    href="{{ url('items') }}">Back</a>
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

</html>
