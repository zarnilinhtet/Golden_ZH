<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Details</title>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            border: none;
        }

        .card-header {
            background: linear-gradient(90deg, #e93939 0%, #f7faff 100%);
            border-radius: 1rem 1rem 0 0;
            border-bottom: 1px solid #e0e0e0;
            color: #fff
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #e40d0d;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .table {
            background: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table thead th {
            background: #f1f5f9;
            color: #374151;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-success {
            background: linear-gradient(90deg, #38d39f 0%, #38a4d3 100%);
            border: none;
        }

        .btn-info {
            background: linear-gradient(90deg, #5b86e5 100%);
            border: none;
            color: #fff
        }

        .btn-warning {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
            border: none;
            color: #222;
        }

        .btn-danger {
            background: linear-gradient(90deg, #ff5858 0%, #f09819 100%);
            border: none;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
        }

        .frmSearch label {
            font-weight: 500;
            color: #374151;
        }

        .fw-large {
            font-size: 1.1rem;
        }

        .item {
            margin-bottom: 2.5rem;
        }

        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: #e0e0e0 !important;
        }

        .table th,
        .table td {
            padding: 0.75rem 1rem;
        }

        .hr-bold {
            border: 0;
            height: 2px;
            background: linear-gradient(90deg, #e21010 0%, #dc0707 100%);
            margin: 2rem 0 1.5rem 0;
        }

        @media print {

            #calculate,
            #print_none,
            #printButton,
            .excelButton,
            .btn,
            .hr-bold {
                display: none !important;
            }

            body {
                color: black;
                background: #fff;
            }

            .card,
            .container-fluid {
                box-shadow: none !important;
                border: none !important;
            }

            .table {
                font-size: 12px;
            }

            .section-title {
                color: #222 !important;
            }

            @page {
                size: auto;
                margin: 0;
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluidmb-4">
        <div class="card p-4">
            {{-- <div class="card-header d-flex align-items-center justify-content-between" style="background-color: red">
                <h4 class="fw-semibold mb-0" style="font-size: 1.5rem;">Product Details</h4>
            </div> --}}

            <div id="print_none" class="mb-4 mt-5">
                <div class="row">
                    <div class="col-md-6">
                        @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                            <div class="frmSearch">
                                <label for="item_location" class="mb-2" style="font-size: 1.1rem;">Product
                                    Location</label>
                                <select name="item_location" id="item_location" class="form-select">
                                    @foreach ($all_items as $item)
                                        @if ($item->parent_id == '0')
                                            <option value="{{ $item->warehouse_id }}" selected>
                                                {{ $item->warehouse->name ?? 'N/A' }}
                                            </option>
                                        @else
                                            <option value="{{ $item->warehouse_id }}">
                                                {{ $item->warehouse->name ?? 'N/A' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="frmSearch" style="display: none;">
                                <label for="item_location" class="mb-2" style="font-size: 1.1rem;">Item
                                    Location</label>
                                <select name="item_location" id="item_location" class="form-select">
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
                    </div>
                </div>
            </div>

            @php
                $userPermissions = [];
                if (auth()->user()->permission) {
                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $userPermissions = $decodedPermissions;
                    }
                }
            @endphp

            <div class="items-container">
                @foreach ($all_items as $items)
                    <div class="item" data-warehouse-id="{{ $items->warehouse_id }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                {{-- @if (in_array('Item Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                    <form action="{{ url('item_edit', $items->id) }}" method="GET" class="mb-3">
                                        <input type="hidden" name="item_id" value="{{ $items->id }}">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                    </form>
                                @endif --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped item-table">
                                        <thead>
                                            <tr class="font-bold text-white bg-dark">
                                                <th class="font-bold text-white bg-dark">Product Name</th>
                                                <th class="font-bold text-white bg-dark">Brand</th>
                                                <th class="font-bold text-white bg-dark">Product Description</th>
                                                <th class="font-bold text-white bg-dark">Warehouse</th>
                                                <th class="font-bold text-white bg-dark">Product Type</th>
                                                <th class="font-bold text-white bg-dark">Product Category</th>
                                                <th class="font-bold text-white bg-dark">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $items['item_name'] }}</td>
                                                <td>{{ $items->BrandName->name ?? '' }}</td>
                                                <td>{{ $items['descriptions'] }}</td>
                                                <td>{{ $items->warehouse->name ?? 'N/A' }}</td>
                                                <td>{{ $items->product_type }}</td>
                                                <td>{{ $items->product_category }}</td>
                                                <td>
                                                    @foreach ($items->variations as $variation)
                                                        @php
                                                            $level1qty = 0;
                                                            $level2qty = 0;
                                                            $level3qty = 0;

                                                            $lvl1 = floor(
                                                                intval($variation->quantity) /
                                                                    (intval($variation->unit2) !== 0
                                                                        ? intval($variation->unit2)
                                                                        : 1),
                                                            );
                                                            $level1qty = $lvl1;

                                                            $first_lvl2 = fmod(
                                                                $variation->quantity,
                                                                (float) ($variation->unit2 ?? 1),
                                                            );
                                                            $level2qty = floor($first_lvl2);

                                                            $level3_fractional =
                                                                ($first_lvl2 - $level2qty) * (float) $variation->unit3;
                                                            $level3qty = ceil($level3_fractional - 0.5);
                                                        @endphp
                                                        @if ($level1qty != 0)
                                                            {{ $level1qty }} {{ $variation->name1 }}
                                                        @endif
                                                        @if ($level2qty != 0 && $level2qty > 0)
                                                            {{ $level2qty }} {{ $variation->name2 }}
                                                        @endif
                                                        @if ($level3qty != 0 && $level3qty > 0)
                                                            {{ $level3qty }} {{ $variation->name3 }}
                                                        @endif
                                                        @if ($level1qty == 0 && $level2qty == 0 && $level3qty == 0)
                                                            0 {{ $variation->name1 }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <hr class="hr-bold">

                                <h3 class="section-title">Product's Variations</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="font-bold text-black">
                                                <th class="font-bold text-white bg-dark">Lot No</th>
                                                <th class="font-bold text-white bg-dark">Warehouse In Date</th>
                                                <th class="font-bold text-white bg-dark">Muf Date</th>
                                                <th class="font-bold text-white bg-dark">Expired Date</th>
                                                <th class="font-bold text-white bg-dark">Estd Test</th>
                                                <th class="font-bold text-white bg-dark">Country Of Origin</th>
                                                <th class="font-bold text-white bg-dark">Distributor</th>
                                                <th class="font-bold text-white bg-dark">Quantity</th>
                                                <th class="font-bold text-white bg-dark">Reorder Level</th>
                                                <th class="font-bold text-white bg-dark" id="tools">Tools</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items->variations as $variation)
                                                <tr class="font-bold text-black" style="background-color: #f1f5f9">
                                                    <td>{{ $variation->lot_no }}</td>
                                                    <td>{{ $variation->arrival_date }}</td>
                                                    <td>{{ $variation->muf_date }}</td>
                                                    <td>{{ $variation->expired_date }}</td>
                                                    <td>{{ $variation->estd_test }}</td>
                                                    <td>{{ $variation->country_of_origin }}</td>
                                                    <td>{{ $variation->distributor }}</td>
                                                    <td>
                                                        @php
                                                            $level1qty = 0;
                                                            $level2qty = 0;
                                                            $level3qty = 0;

                                                            $lvl1 = floor(
                                                                intval($variation->quantity) /
                                                                    (intval($variation->unit2) ?: 1),
                                                            );
                                                            $level1qty = $lvl1;

                                                            $first_lvl2 = fmod(
                                                                floatval($variation->quantity),
                                                                floatval($variation->unit2 ?? 1),
                                                            );
                                                            $level2qty = floor($first_lvl2);

                                                            $level3_fractional =
                                                                ($first_lvl2 - $level2qty) * (float) $variation->unit3;
                                                            $level3qty = ceil($level3_fractional - 0.5);
                                                        @endphp

                                                        @if ($level1qty != 0)
                                                            {{ $level1qty . ' ' . $variation->name1 }}
                                                        @endif
                                                        @if ($level2qty != 0 && $level2qty > 0)
                                                            {{ $level2qty . ' ' . $variation->name2 }}
                                                        @endif
                                                        @if ($level3qty != 0 && $level3qty > 0)
                                                            {{ $level3qty . ' ' . $variation->name3 }}
                                                        @endif
                                                        @if ($level1qty == 0 && $level2qty == 0 && $level3qty == 0)
                                                            0 {{ $variation->name1 }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $variation->reorder_level_stock }}</td>
                                                    <td id="tools-1">
                                                        @if (in_array('Item In/Out', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('in_out', $variation->id) }}"
                                                                class="mt-1 btn btn-info btn-sm">In/Out History </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="font-bold text-black">
                                                    <td colspan="10">
                                                        <table class="table mt-2 mb-0 table-sm">
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
                                                                @if (
                                                                    !empty($variation->unit1) ||
                                                                        !empty($variation->price1 || !empty($variation->wholesale1) || !empty($variation->retail1)))
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>{{ $variation->unit1 ?? '' }}</td>
                                                                        <td>{{ $variation->name1 ?? '' }}</td>
                                                                        <td>{{ $variation->price1 ?? '' }}</td>
                                                                        <td>
                                                                            @if ($variation->item)
                                                                                @if ($variation->item->pricePercent)
                                                                                    {{ ($variation->wholesale1 * $variation->item->pricePercent->percent) / 100 + $variation->wholesale1 }}
                                                                                @else
                                                                                    {{ $variation->wholesale1 ?? '' }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($variation->item)
                                                                                @if ($variation->item->pricePercent)
                                                                                    {{ ($variation->retail1 * $variation->item->pricePercent->percent) / 100 + $variation->retail1 }}
                                                                                @else
                                                                                    {{ $variation->retail1 ?? '' }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                                @if (
                                                                    !empty($variation->unit2) ||
                                                                        !empty($variation->price2 || !empty($variation->wholesale2) || !empty($variation->retail2)))
                                                                    <tr>
                                                                        <td>2</td>
                                                                        <td>{{ $variation->unit2 ?? '' }}</td>
                                                                        <td>{{ $variation->name2 ?? '' }}</td>
                                                                        <td>{{ $variation->price2 ?? '' }}</td>
                                                                        <td>
                                                                            @if ($variation->item)
                                                                                @if ($variation->item->pricePercent)
                                                                                    {{ ($variation->wholesale2 * $variation->item->pricePercent->percent) / 100 + $variation->wholesale2 }}
                                                                                @else
                                                                                    {{ $variation->wholesale2 ?? '' }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($variation->item)
                                                                                @if ($variation->item->pricePercent)
                                                                                    {{ ($variation->retail2 * $variation->item->pricePercent->percent) / 100 + $variation->retail2 }}
                                                                                @else
                                                                                    {{ $variation->retail2 ?? '' }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                                @if (
                                                                    !empty($variation->unit3) ||
                                                                        !empty($variation->price3 || !empty($variation->wholesale3) || !empty($variation->retail3)))
                                                                    <tr>
                                                                        <td>3</td>
                                                                        <td>{{ $variation->unit3 ?? '' }}</td>
                                                                        <td>{{ $variation->name3 ?? '' }}</td>
                                                                        <td>{{ $variation->price3 ?? '' }}</td>
                                                                        <td>
                                                                            @if ($variation->item)
                                                                                @if ($variation->item->pricePercent)
                                                                                    {{ ($variation->wholesale3 * $variation->item->pricePercent->percent) / 100 + $variation->wholesale3 }}
                                                                                @else
                                                                                    {{ $variation->wholesale3 ?? '' }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($variation->item)
                                                                                @if ($variation->item->pricePercent)
                                                                                    {{ ($variation->retail3 * $variation->item->pricePercent->percent) / 100 + $variation->retail3 }}
                                                                                @else
                                                                                    {{ $variation->retail3 ?? '' }}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($items['status'] == 'item_kit')
                                    <hr class="hr-bold">
                                    <div class="section-title">Collaborated Items</div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
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
                                                            <td>
                                                                <a href="{{ url('barcode', $variation->id) }}"
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
                    </div>
                @endforeach
            </div>
            <div class="mt-3 d-flex justify-content-end gap-2">
                <a type="button" id="printButton" class="btn btn-primary m-2" onclick="printPage()">
                    <i class="bi bi-printer"></i> Print
                </a>
                @if ($item_status->status == 'item_kit')
                    <a type="button" id="printButton" class="excelButton btn btn-danger m-2"
                        href="{{ url('item_kits') }}">Back</a>
                @else
                    <a type="button" id="printButton" class="excelButton btn btn-danger m-2"
                        href="{{ url('items') }}">Back</a>
                @endif
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
            var items = document.querySelectorAll('.item');

            function filterItems() {
                var selectedLocation = document.getElementById('item_location').value;
                items.forEach(function(item) {
                    var warehouseId = item.getAttribute('data-warehouse-id');
                    item.style.display = (selectedLocation === "" || warehouseId == selectedLocation) ?
                        'block' : 'none';
                });
            }

            document.getElementById('item_location').addEventListener('change', filterItems);
            filterItems();
        });
    </script>
    <!-- Optionally include Bootstrap Icons for button icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>

</html>
