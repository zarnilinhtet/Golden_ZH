<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        input {
            position: relative;
            width: 150px;
            height: 40px;
            color: white;
        }

        input:before {
            position: absolute;
            top: 6px;
            left: 6px;
            content: attr(data-date);
            display: inline-block;
            color: black;
        }

        input::-webkit-datetime-edit,
        input::-webkit-inner-spin-button,
        input::-webkit-clear-button {
            display: none;
        }

        input::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 6px;
            right: 12px;
            color: black;
            opacity: 1;
        }

        .dropdown-menu {
            max-height: 200px;
            /* Set a maximum height for the dropdown menu */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        /* CSS for active item in dropdown menu */
        .dropdown-menu .active,
        .dropdown-menu .active:hover {
            background-color: #E2E3E5;
            /* Set background color for active item */
            color: #E2E3E5;
        }
    </style>
    </style>
</head>

<body>

    <div class="container-fluid">

        <h1 class="mt-3">
            Purchase Order
        </h1>
        <form method="post" id="data_form" action=" {{ URL('purchase_order_store') }}" enctype="multipart/form-data">
            @csrf


            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="content-wrapper mt-3" style="background-color:aqua">
                <div class="content-body">
                    <div class="card">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="col-sm-10 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">

                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="po_no" style="font-weight:bolder">Purchase Order
                                                    Number</label>
                                                <input type="text" id="po_no" class="form-control"
                                                    name="po_number" value="PO - {{ $po_no }}" readonly>
                                            </div>
                                            <div class="frmSearch col-sm-4">
                                                <div class="frmSearch col-sm-12">
                                                    <span style="font-weight:bolder">
                                                        <label for="cst"
                                                            class="caption">{{ trans('Supplier Name') }}</label>
                                                    </span>
                                                    <select name="supplier_id" id="" class="form-control">
                                                        <option value="" selected disabled>Choose Supplier
                                                        </option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="frmSearch col-sm-4">
                                                <label for="location" style="font-weight:bolder">Choose
                                                    Location</label>
                                                <select name="location" id="location" class="form-control mb-4"
                                                    required>

                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">
                                                            {{ $warehouse->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="frmSearch col-sm-4">
                                                <div class="frmSearch col-sm-12">
                                                    <div class="frmSearch col-sm-12">
                                                        <span style="font-weight:bolder">
                                                            <label for="cst" class="caption">Receiving Mode
                                                            </label>
                                                        </span>
                                                        <select name="balance_due" id="balance_due"
                                                            class="mb-4 form-control balance_due" required>

                                                            <option value="PO">PO</option>
                                                            <option value="Sale Return">Sale Return</option>

                                                        </select>

                                                        <div id="customer-box-result"></div>
                                                    </div>


                                                </div>



                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6 cmp-pnl">

                                    <div class="inner-cmp-pnl">

                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="invoice" name="status">

                            <div class="row " style="margin-top:1vh;">
                                <table class="" id="myTable">
                                    <thead style="background-color:#0047aa;color:white; border: 1px solid white;">
                                        <tr class="item_header bg-gradient-directional-blue white"
                                            style="margin-bottom:10px;">
                                            <th width="3%" class="text-center" style="display:none">
                                                {{ trans('No') }}</th>
                                            <th width="20%" class="text-center">{{ trans('Item Name') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('Qty') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('Company Price') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('FOC ') }}
                                            </th>
                                            <th width="8%" class="text-center">{{ trans('Unit') }}
                                            </th>
                                            <th width="7%" class="text-center">{{ trans('Total Qty ') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('Discount (%)') }}
                                            </th>
                                            <th width="10%" class="text-center">{{ trans('Commercial Tax ') }}
                                            </th>

                                            <th width="14%" class="text-center">{{ trans('Amount') }}
                                                ({{ config('currency.symbol') }})
                                            </th>
                                        </tr>

                                    </thead>
                                    <tbody id="showitem123">
                                        <tr class="ml-3">
                                            <td class="text-center" id="count" style="display:none">1</td>
                                            <td>
                                                <input type="text" style="background-color: #E9ECEF"
                                                    class="form-control productname typeahead" name="part_number[]"
                                                    placeholder="Enter Part Number" id='productname-0'
                                                    autocomplete="off">
                                            </td>
                                            <input type="hidden" class="form-control description typeahead"
                                                name="part_description[]" id='description-0' autocomplete="off">
                                            <td>
                                                <input type="text" class="form-control req amnt"
                                                    style="background-color: #E9ECEF" name="product_qty[]"
                                                    id="qty-0" autocomplete="off" onkeyup="sumQty(0)"
                                                    value="1">
                                                <input type="hidden" id="alert-0"
                                                    style="background-color: #E9ECEF" value="" name="alert[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control companyPrice"
                                                    name="company_price[]" id="company_price-0" autocomplete="off"
                                                    value="" style="background-color: #E9ECEF">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control foc" name="foc[]"
                                                    onkeyup="sumQty(0)" id="foc-0" autocomplete="off"
                                                    value="" style="background-color: #E9ECEF">
                                            </td>
                                            <td>
                                                <select class="form-control unit" id="unit-0" required
                                                    name="item_unit[]" style="background-color: #E9ECEF">
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control totalQty" name="totalQty[]"
                                                    id="totalQty-0" autocomplete="off" value=""
                                                    style="background-color: #E9ECEF">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control discount" name="discount[]"
                                                    id="discount-0" autocomplete="off" value="0"
                                                    style="background-color: #E9ECEF">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control commercialtax"
                                                    name="commercialtax[]" id="commercialtax-0" autocomplete="off"
                                                    value="0" style="background-color: #E9ECEF">
                                            </td>
                                            <td style="display: none;">
                                                <input type="text" class="form-control warehouse"
                                                    name="warehouse[]" id="warehouse-0" autocomplete="off">
                                            </td>
                                            <td style="text-align:center">
                                                <input type="text" class="form-control amount" id="amount-0"
                                                    name="amount[]" value= "" style="background-color: #E9ECEF">
                                            </td>
                                            <input type="hidden" class="form-control vat" name="product_tax[]"
                                                id="vat-0" value="0">
                                            <input type="hidden" name="total_tax[]" id="taxa-0" value="0">
                                            <input type="hidden" name="total_discount[]" id="disca-0"
                                                value="0">
                                            <input type="hidden" class="ttInput" name="product_subtotal[]"
                                                id="total-0" value="0">
                                            <input type="hidden" class="pdIn" name="product_id[]" id="pid-0"
                                                value="0">
                                            <input type="hidden" name="unit_m[]" id="unit_m-0" value="1">
                                            <input type="hidden" name="code[]" id="hsn-0" value="">
                                            <input type="hidden" name="serial[]" id="serial-0" value="">
                                        </tr>
                                        <tr class="text-center">

                                            <td>Unit</td>
                                            <td>ဝယ်စျေး</td>
                                            <td>လက်ကားစျေး</td>
                                            <td>လက်လီစျေး</td>



                                        </tr>
                                        <tr>

                                            <td><input type="text" class="form-control name1" name="name1[]"
                                                    id="name1-0">
                                            </td>
                                            <td><input type="text" class="form-control price1 " name="price1[]"
                                                    id="price1-0"></td>
                                            <td><input type="text" class="form-control wholesale1"
                                                    name="wholesale1[]" id="wholesale1-0"></td>

                                            <td><input type="text" class="form-control retail1" name="retail1[]"
                                                    id="retail1-0"> </td>


                                        </tr>
                                        <tr>
                                            <!-- <td></td> -->
                                            <td><input type="text" class="form-control name2 " name="name2[]"
                                                    id="name2-0"></td>
                                            <td><input type="text" class="form-control price2" name="price2[]"
                                                    id="price2-0"></td>
                                            <td><input type="text" class="form-control wholesale2"
                                                    name="wholesale2[]" id="wholesale2-0"></td>

                                            <td><input type="text" class="form-control retail2" name="retail2[]"
                                                    id="retail2-0"></td>


                                        </tr>
                                        <tr>

                                            <td><input type="text" class="form-control  name3" name="name3[]"
                                                    id="name3-0"></td>
                                            <td><input type="text" class="form-control  price3 " name="price3[]"
                                                    id="price3-0"></td>
                                            <td><input type="text" class="form-control  wholesale3"
                                                    name="wholesale3[]" id="wholesale3-0"></td>

                                            <td><input type="text" class="form-control retail3" name="retail3[]"
                                                    id="retail3-0"></td>


                                        </tr>
                                    </tbody>

                                    <tr class="last-item-row sub_c">

                                        <td class="add-row">
                                            <button type="button" class="btn btn-success" id="addproduct"
                                                style="margin-top:30px;margin-bottom:20px;display:none;">
                                                <i class="fa fa-plus-square"></i> {{ trans('Add row') }}
                                            </button>
                                            <button type="button" class="btn btn-primary" id="calculate"
                                                style="margin-top:30px;margin-bottom:20px;">
                                                Calculate
                                            </button>


                                        </td>
                                        <td colspan="6"></td>
                                        <br><br>
                                    </tr>
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="2">
                                            @if (isset($employees[0]))
                                                {{ trans('general.employee') }}
                                                <select name="user_id" class="selectpicker form-control">
                                                    <option value="{{ $logged_in_user->id }}">
                                                        {{ $logged_in_user->first_name }}
                                                    </option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->first_name }}
                                                            {{ $employee->last_name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                    <tbody id="showitem">
                                        <tr style="display: table-row;">
                                            <td></td>
                                            <td colspan="">
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="2">
                                                @if (isset($employees[0]))
                                                    {{ trans('general.employee') }}
                                                    <select name="user_id" class="selectpicker form-control">
                                                        <option value="{{ $logged_in_user->id }}">
                                                            {{ $logged_in_user->first_name }}
                                                        </option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">
                                                                {{ $employee->first_name }}
                                                                {{ $employee->last_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td>
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="4">

                                            </td>
                                            <td colspan="3" align="right"><strong>Sub Total
                                                </strong>
                                            </td>
                                            <td align="left" colspan="4" class="col-md-4"><input type="text"
                                                    name="sub_total" class="form-control" id="invoiceyoghtml"
                                                    style="background-color: #E9ECEF">
                                            </td>
                                        </tr>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="4">

                                            </td>
                                            <td colspan="3" align="right"><strong>Total Discount
                                                </strong>
                                            </td>
                                            <td align="left" colspan="4"><input type="text"
                                                    name="discount_total" class="form-control" id="discount_total">
                                            </td>

                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="4">

                                            </td>
                                            <td colspan="3" align="right"><strong>Total
                                                </strong>
                                            </td>
                                            <td align="left" colspan="4"><input type="text" name="total"
                                                    class="form-control" id="total">

                                            </td>
                                        </tr>


                                        <tr class="sub_c " style="display: table-row;">
                                            <td colspan="14"> <label for="remark">Remark</label>
                                                <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                            </td>
                                        </tr>
                                        <tr class="sub_c " style="display: table-row;">


                                            <td align="right" colspan="15">

                                                <button id="submitButton" class="mt-3 btn btn-danger"
                                                    type="submit">Save</button>


                                                <a href="{{ url('purchase_order_manage') }}" type="submit"
                                                    class="mt-3 btn btn-warning">Cancel
                                                </a>

                                            </td>
                                        </tr>
                                    </tbody>
                                    </tbody>
                                </table>
                            </div>


                        </div>

                    </div>
                </div>
            </div>

        </form>
    </div>

    </div>
    <script>
        function handleKeyUp(event) {
            console.log(`Key pressed: ${event.key}`);
            // You can add more logic here to respond to the event
        }
    </script>
    <script>
        document.getElementById('discount_total').addEventListener('input', function() {
            const subTotal = parseFloat(document.getElementById('invoiceyoghtml').value) || 0;
            const discountTotal = parseFloat(this.value) || 0;
            const total = subTotal - discountTotal;
            document.getElementById('total').value = total;
        });
    </script>


    <script>
        function sumQty(count) {
            let qty = document.getElementById("qty-" + count).value;
            let foc = document.getElementById("foc-" + count).value;
            qty = parseFloat(qty) || 0;
            foc = parseFloat(foc) || 0;

            let totalQty = document.getElementById("totalQty-" + count);
            totalQty.value = qty + foc;

        }

        //     <!-- function Price(count) {
        //         let price = document.getElementById("company_price-"+count).value;
        //         let qty = document.getElementById("qty-"+count).value;
        //         qty = parseFloat(qty) || 0;
        //         price = parseFloat(price) || 0;

        //         let amount = document.getElementById("amount-"+count);
        //         amount.value = qty * price;
        //         CommercialTax(count);

        //     }
        //     function CommercialTax(count) {
        //         let tax = document.getElementById("commercialtax-"+count).value;
        //         let amount = document.getElementById("amount-"+count).value;
        //         tax = parseFloat(tax) || 0;
        //         amount = parseFloat(amount) || 0;
        //        let TotalAmount= document.getElementById("amount-"+count);
        //        TotalAmount.value = tax * amount / 100 + amount;

        //        Price(count); Discount(count);
        //     }
        //     function Discount(count) {
        //         let discount = document.getElementById("discount-"+count).value;
        //         let amount = document.getElementById("amount-"+count).value;


        //         discount = parseFloat(discount) || 0;
        //         amount = parseFloat(amount) || 0;
        //         console.log(amount);
        //         let TotalAmount= document.getElementById("amount-"+count);
        //         TotalAmount.value = amount -( discount*amount/100);
        //     }
    </script>
    <script>
        $(document).ready(function() {
            let count = 0;


            function initializeTypeahead(count) {
                $('#productname-' + count).typeahead({
                    source: function(query, process) {
                        var Selectedlocation = $('#location').val();
                        return $.ajax({
                            url: "{{ route('autocomplete-part-code-invoice') }}",
                            method: 'POST',
                            data: {
                                query: query,
                                location: Selectedlocation,
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                process(data);
                            }
                        });
                    }
                });
            }

            function initializeTypeaheads() {
                for (let i = 0; i <= count; i++) {
                    initializeTypeahead(i);
                }
            }

            function updateItemName(item_name, row, cuz_name) {
                let buyprice = row.find('.buy_price');
                let retail = row.find('.retail_price');
                let wholesale = row.find('.wholesale_price');

                let partDesc = row.find('.description');
                let exp_date = row.find('.exp_date');

                var Selectedlocation = $('#location').val();
                let warehouse = row.find('.warehouse');
                let unit = row.find('.unit');

                //start for unit and price



                //end for unit and price

                $.ajax({
                    type: 'POST',
                    url: "{{ route('get-part-data-invoice') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        item_name: item_name,
                        location: Selectedlocation,
                    },
                    success: function(data) {
                        //  itemNameInput.val(data.retail_price);
                        buyprice.val(data.buy_price);
                        $(unit).on('change', function() {
                            let selectedUnit = unit.val();
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('unit_search_withName') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    unit: selectedUnit,
                                    item_name: item_name,
                                    // Adjusted to match server-side parameter name
                                },
                                success: function(data) {
                                    console.log(data);
                                    wholesale.val(data.wholesale);
                                    retail.val(data.retail);
                                    buyprice.val(data.buyprice);

                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });

                        });
                        // itemNameInput.val(data.wholesale_price);
                        // retail.val(data.retail_price);
                        unit.empty();

                        if (data.name2 != null && data.name3 != null) {
                            unitdata = [data.name1, data.name2, data.name3];
                        }
                        if (data.name2 != null && data.name3 == null) {
                            unitdata = [data.name1, data.name2];
                        }
                        if (data.name2 == null && data.name3 != null) {
                            unitdata = [data.name1, data.name3];
                        } else {
                            unitdata = [data.name1];
                        }
                        // Assuming data is an array of items
                        // e.g., data = ['item1', 'item2', 'item3']
                        $.each(unitdata, function(index, item) {
                            let option = $('<option></option>').val(item).text(item);
                            unit.append(option);
                        });
                        unit.trigger('change');
                        partDesc.val(data.descriptions);
                        exp_date.val(data.expired_date);
                        warehouse.val(data.warehouse_id);

                        //start set value for unit and price


                        // Example data object, replace with your actual data

                        console.log(count);
                        // Assign data to the fields in the newly added row
                        $('#name1-' + count).val(data.name1);
                        $('#price1-' + count).val(data.price1);
                        $('#retail1-' + count).val(data.retail1);
                        $('#wholesale1-' + count).val(data.wholesale1);

                        $('#name2-' + count).val(data.name2);
                        $('#price2-' + count).val(data.price2);
                        $('#retail2-' + count).val(data.retail2);
                        $('#wholesale2-' + count).val(data.wholesale2);

                        $('#name3-' + count).val(data.name3);
                        $('#price3-' + count).val(data.price3);
                        $('#retail3-' + count).val(data.retail3);
                        $('#wholesale3-' + count).val(data.wholesale3);




                        //end set value for unit and price
                        var qq = Math.floor(data.quantity / data.unit2);

                        if (parseFloat(data.reorder_level_stock) >= qq) {
                            alert(qq + "  " + data.name1);
                        }

                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }


            $("#addproduct").click(function(e) {
                e.preventDefault();

                $.ajax({

                    type: 'GET',
                    url: "{{ route('get.part.data-unit') }}",
                    data: {
                        // _token: "{{ csrf_token() }}",

                    },
                    success: function(data) {
                        // itemNameInput.val(data.retail_price);
                        // partDesc.val(data.descriptions);
                        // exp_date.val(data.expired_date);

                        var selectBox = document.getElementById("unit-" + count);

                        // Loop through the data array
                        data.forEach(function(item) {
                            // Create an option element
                            var option = document.createElement("option");

                            // Set the value attribute to the unit id
                            option.value = item.unit;

                            // Set the text of the option to the unit name
                            option.text = item.unit;

                            // Append the option to the select element
                            selectBox.appendChild(option);
                        });


                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

                // Assuming {{ $units }} is a string representation of an array

                var jobs = <?php echo json_encode($units); ?>

                count++;
                let rowCount = $("#showitem123 tr").length;
                // let newRow = '<tr>' +
                //     '<td class="text-center">' + (rowCount - 3 ) + '</td>' +
                //     '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                //     count + '" autocomplete="off"></td>' +
                //     '<input type="hidden" class="form-control description typeahead" name="part_description[]" required id="description-' +
                //     count + '" autocomplete="off">' +
                //     // '<td><input type="text" class="form-control description typeahead" name="part_description[]" required id="description-' +
                //     // count + '" autocomplete="off"></td>' +
                //     '<td><input type="text" class="form-control req amnt" name="product_qty[]"  onchange="sumQty(' + count + ')" id="qty-' +
                //     count +
                //     '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                //     '<td><input type="text" class="form-control companyprice amnt" name="company_price[]" id="company_price-' +
                //     count +
                //     '"   autocomplete="off" ></td>' +
                //     '<td><input type="text" class="form-control foc amnt" name="foc[]" onchange="sumQty(' + count + ')" id="foc-' +
                //     count +
                //     '"   autocomplete="off" ></td>' +
                //     '<td><select class="form-control unit " name="item_unit[]" id="unit-' +
                //     count +
                //     '" autocomplete ="off" required></select><span class="mt-0" id="uniterror" style="display: none; color: red;">Please Choose A Unit.</span> </td>' +
                //     '<td><input type="text" class="form-control totalQty " name="totalQty[]" id="totalQty-' +
                //     count +
                //     '"   autocomplete="off" ></td>' +
                //     '<td><input type="text" class="form-control discount " name="discount[]" id="discount-' +
                //     count +
                //     '"   autocomplete="off" ></td>' +
                //     '<td><input type="text" class="form-control commercialtax " name="commercialtax[]" id="commercialtax-' +
                //     count +
                //     '"   autocomplete="off" ></td>' +

                //     '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                //     count +
                //     '"   autocomplete="off"></td>' +

                //     '<td style="text-align:center"><input type="text" class="form-control amount" name="amount[]" id="amount-' + count + '" autocomplete="off"></td>' +
                //     '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                //     '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                //     '<input type="hidden" class="ttInput" name="product_subtotal[] value="0">' +
                //     '<input type="hidden" class="pdIn" name="product_id[]" id="pid-0" value="0">' +

                //     '<input type="hidden" name="unit_m[]" id="unit_m-0" value="1">' +
                //     '<input type="hidden" name="code[]" id="hsn-0" value="">' +
                //     '<input type="hidden" name="serial[]" id="serial-0" value="">' +
                //       '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>'+
                //     '</tr>'+
                //     '<tr ><td></td><td class="text-center"> unit</td> <td class="text-center"> ဝယ်စျေး</td><td class="text-center"> လက်လီစျေး</td><td class="text-center"> လက်ကားစျေး</td> <td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td></tr><tr>'+
                //     '<tr><td></td><td><input type="text" class="form-control name1" name="name1[]" id="name1-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control price1" name="price1[]" id="price1-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control retail1" name="retail1[]" id="retail1-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control wholesale1" name="wholesale1[]" id="wholesale1-' +count + '" autocomplete="off"></td>'+
                //       '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>'+'</tr>'+
                //     '<tr><td></td><td><input type="text" class="form-control name2" name="name2[]" id="name2-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control price2" name="price2[]" id="price2-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control retail2" name="retail2[]" id="retail2-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control wholesale2" name="wholesale2[]" id="wholesale2-' +count + '" autocomplete="off"></td>'+
                //       '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>'+'</tr>'+
                //     '<tr><td></td><td><input type="text" class="form-control name3" name="name3[]" id="name3-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control price3" name="price3[]" id="price3-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control retail3" name="retail3[]" id="retail3-' +count + '" autocomplete="off"></td>'+
                //     '<td><input type="text" class="form-control wholesale3" name="wholesale3[]" id="wholesale3-' +count + '" autocomplete="off"></td>'+
                //     '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +'</tr>' ;

                // $("#showitem123").append(newRow);
                let newRow = '<tr>' +
                    '<td class="text-center" style="display:none">' + (rowCount - 3) + '</td>' +
                    '<td><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                    count +
                    '" autocomplete="off" placeholder="Enter Part Number" style="background-color: #E9ECEF"></td>' +
                    '<input type="hidden" class="form-control description typeahead" name="part_description[]" required id="description-' +
                    count + '" autocomplete="off">' +
                    '<td><input style="background-color: #E9ECEF" type="text" class="form-control req amnt" name="product_qty[]" onkeyup="sumQty(' +
                    count + ')" id="qty-' +
                    count +
                    '" autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                    '<td><input type="text" style="background-color: #E9ECEF" class="form-control companyprice amnt" name="company_price[]" id="company_price-' +
                    count + '" autocomplete="off" ></td>' +
                    '<td><input type="text" class="form-control foc amnt"style="background-color: #E9ECEF" name="foc[]" onkeyup="sumQty(' +
                    count + ')" id="foc-' +
                    count + '" autocomplete="off" ></td>' +
                    '<td><select class="form-control unit" style="background-color: #E9ECEF" name="item_unit[]" id="unit-' +
                    count +
                    '" autocomplete="off" required></select><span class="mt-0" id="uniterror" style="display: none; color: red;">Please Choose A Unit.</span></td>' +
                    '<td><input type="text" style="background-color: #E9ECEF" class="form-control totalQty" name="totalQty[]" id="totalQty-' +
                    count + '" autocomplete="off" ></td>' +
                    '<td><input type="text" class="form-control discount" style="background-color: #E9ECEF" name="discount[]" id="discount-' +
                    count + '" autocomplete="off" value="0"></td>' +
                    '<td><input type="text" class="form-control commercialtax" style="background-color: #E9ECEF" name="commercialtax[]" id="commercialtax-' +
                    count + '" autocomplete="off" value="0" ></td>' +
                    '<td style="display:none;"><input type="text" class="form-control warehouse" name="warehouse[]" id="warehouse-' +
                    count + '" autocomplete="off"></td>' +
                    '<td style="text-align:center"><input type="text" style="background-color: #E9ECEF" class="form-control amount" name="amount[]" id="amount-' +
                    count + '" autocomplete="off"></td>' +
                    '<input type="hidden" name="total_tax[]" id="taxa-' + count + '" value="0">' +
                    '<input type="hidden" name="total_discount[]" id="disca-' + count + '" value="0">' +
                    '<input type="hidden" class="ttInput" name="product_subtotal[]" value="0">' +
                    '<input type="hidden" class="pdIn" name="product_id[]" id="pid-' + count +
                    '" value="0">' +
                    '<input type="hidden" name="unit_m[]" id="unit_m-' + count + '" value="1">' +
                    '<input type="hidden" name="code[]" id="hsn-' + count + '" value="">' +
                    '<input type="hidden" name="serial[]" id="serial-' + count + '" value="">' +
                    '<td><button  type="button" class="btn btn-danger remove_item_btn" >Remove</button></td>' +
                    '</tr>' +
                    '<tr><td class="text-center">unit</td><td class="text-center">ဝယ်စျေး</td><td class="text-center">လက်ကားစျေး</td><td class="text-center">လက်လီစျေး</td>' +
                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>' +
                    '<tr><td><input type="text" class="form-control name1" name="name1[]" id="name1-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control price1" name="price1[]" id="price1-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control wholesale1" name="wholesale1[]" id="wholesale1-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail1" name="retail1[]" id="retail1-' +
                    count + '" autocomplete="off"></td>' +

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>' +
                    '<tr><td><input type="text" class="form-control name2" name="name2[]" id="name2-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control price2" name="price2[]" id="price2-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control wholesale2" name="wholesale2[]" id="wholesale2-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail2" name="retail2[]" id="retail2-' +
                    count + '" autocomplete="off"></td>' +

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>' +
                    '<tr><td><input type="text" class="form-control name3" name="name3[]" id="name3-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control price3" name="price3[]" id="price3-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control wholesale3" name="wholesale3[]" id="wholesale3-' +
                    count + '" autocomplete="off"></td>' +
                    '<td><input type="text" class="form-control retail3" name="retail3[]" id="retail3-' +
                    count + '" autocomplete="off"></td>' +

                    '<td><button type="button" class="btn btn-danger remove_item_btn" style="display:none">Remove</button></td></tr>';

                $("#showitem123").append(newRow);
                $('#showitem123').on('click', '.remove_item_btn', function() {
                    // $(this).closest('tr').nextUntil(':not(:has(button.remove_item_btn))').addBack().remove();
                    $(this).closest('tr').nextAll('tr:lt(4)').addBack().remove();
                    count--;
                });
                initializeTypeahead(count);
            });

            // $(document).on('click', '.remove_item_btn', function(e) {
            // e.preventDefault();
            // let row_item = $(this).closest('tr'); // Find the closest <tr> parent
            // $(row_item).remove();
            // // let row_item = $(this).parent().parent();
            // // $(row_item).remove();


            //             $('#showitem123').on('click', '.remove_item_btn', function() {
            //     $(this).closest('tr').nextUntil(':not(:has(button.remove_item_btn))').addBack().remove();
            //     initializeTypeaheads();
            // });
            // Update row numbers
            // $('#showitem123 tr').each(function(index) {
            //     $(this).find('td:first').text(index + 1);
            // });


            // });

            $(document).on('change', '.productname', function() {
                let itemCode = $(this).val();
                let row = $(this).closest('tr');
                updateItemName(itemCode, row);
            });

            // Initialize typeahead for the first row
            initializeTypeahead(count);

            $(document).on("click", '#calculate', function(e) {
                e.preventDefault();
                let total = 0;
                let totalTax = 0;

                for (let i = 0; i < (count + 1); i++) {

                    var qty = $('#qty-' + i).val();
                    var discount = $('#discount-' + i).val();

                    let price = parseInt($('#company_price-' + i).val());
                    let taxRate = parseFloat($('#commercialtax-' + i).val());
                    console.log(amount);

                    var item_amount = document.getElementById('amount-' + i);

                    if (taxRate >= 0) {
                        let itemTax = (price * qty * taxRate) / 100;
                        totalTax += itemTax;
                    }

                    if (taxRate > 0 && discount == 0) {

                        let tax = (price * qty * taxRate) / 100;
                        item_amount.value = parseFloat((price * qty) + tax);
                    } else if (!isNaN(discount) && discount > 0 && taxRate == 0) {
                        let item_discount = (price * qty * discount) / 100;
                        item_amount.value = parseFloat((price * qty) - item_discount);
                    } else if (discount > 0 && taxRate > 0) {

                        let tax = (price * qty * taxRate) / 100 + (price * qty);
                        let item_discount = (tax * discount) / 100;
                        item_amount.value = tax - item_discount;
                    } else {
                        item_amount.value = parseFloat(price * qty);
                    }
                    // Add the amount
                    var amount = document.getElementById('amount-' + i).value;
                    total += parseInt(amount);
                }






                $("#invoiceyoghtml").val(total);
                $("#discount_total").val('');

                $("#total").val(total);

            });


            document.getElementById('submitButton').addEventListener('click', function() {
                var serviceType = document.getElementById('unit-' + count).value;
                var serviceTypeError = document.getElementById('uniterror');
                if (serviceType === 'Choose Unit') {
                    serviceTypeError.style.display = 'block';
                } else {
                    serviceTypeError.style.display = 'none';
                    // Add your code to handle the submission without a form
                }
            });

        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", '#calculate', function(e) {
            e.preventDefault();
            let total = 0;

            for (let i = 0; i < (count + 1); i++) {
                var qty = parseInt($('#amount-' + i).val()) || 0;

                total += qty;
            }

            $("#invoiceyoghtml").val(total);
            $("#total").val(total);
            $("#discount_total").val('');

            //set  (amount*price)  per invoice  subtotal


        });
    </script>
    <script>
        $("input").on("change", function() {
            if (this.value && moment(this.value, "YYYY-MM-DD").isValid()) {
                this.setAttribute(
                    "data-date",
                    moment(this.value, "YYYY-MM-DD").format("DD/MM/YYYY")
                );
            } else {
                this.setAttribute("data-date", "dd/mm/yyyy");
            }
        }).trigger("change");
    </script>

    <!--Enter Key click add row-->
    <script>
        //Enter Key click add row
        $(document).on('keydown', '.form-control', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                $('#addproduct').click();
            }
        });
    </script>


</body>

</HTML>
