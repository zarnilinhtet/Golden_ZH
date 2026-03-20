<!DOCTYPE html>
<HTML>

<head>
    <link href="{{ asset('backend/css/bootstrap502.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/js/jquery191.js') }}"></script>
    <script src="{{ asset('backend/js/typehead401.js') }}"></script>

    <script src="{{ asset('backend/js/moment2103.js') }}"></script>


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
    </style>
    </style>
</head>

<body>

    <div class="container-fluid">

        <h1 class="mt-3">
            Transfer Item
        </h1>
        <form action="{{ url('store_transfer_item') }}" method="POST">
            @csrf
            <div class="row">
                <!-- <div class="col-md-6">
                    {{-- <img src="{{ asset('image/prime.png') }}" alt="logo" style="width:200px;"> --}}
                    <div style="font-size: 18px;margin-left: 25px;">
                        <p>
                            <span style="font-weight:bolder">Shwe Mann Pharmacy<br>
                                No.286, Kyaik Ka San Road, Tarmwe Township,<br>
                                Yangon, Myanmar.<br>
                                <span> Phone: 09-740867976</span>
                            </span>

                        </p>
                    </div>
                </div> -->
                <div class="col-md-2"></div>
                <div class="mt-3 col-md-4">









                </div>
            </div>

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

            <div class="content-wrapper">
                <div class="content-body">
                    <div class="card">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="col-sm-6 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">

                                        <div class="form-group row">
                                            <div class="frmSearch col-sm-12">
                                                <div class="row">
                                                    <div class="frmSearch col-sm-3">
                                                        <label for="from" style="font-weight:bolder">From
                                                            Location</label>
                                                        <select name="from_location" id="from_location"
                                                            class="form-control" required>
                                                            <option value="" selected disabled>Choose Location
                                                            </option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}">
                                                                    {{ $warehouse->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="frmSearch col-sm-3">
                                                        <label for="to" style="font-weight:bolder">To
                                                            Location</label>
                                                        <select name="to_location" id="to_location" class="form-control"
                                                            required>
                                                            <option value="" selected disabled>Choose Location
                                                            </option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}">
                                                                    {{ $warehouse->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="frmSearch col-sm-3">
                                                        <label for="invoice_date"
                                                            style="font-weight:bolder">{{ trans(' Date') }}</label>

                                                        <div class="input-group mb-2">
                                                            <div class="input-group-addon"><span class="icon-calendar4"
                                                                    aria-hidden="true"></span>
                                                            </div>

                                                            <input type="date" name="date" id="date"
                                                                class="form-control round " autocomplete="off"
                                                                max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                                                                required>


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
                                <input type="hidden" value="{{ $transfer_id }}" name="transfer_id">

                                <div class="row " style="margin-top:1vh;">
                                    <!-- <table class="table-responsive tfr my_stripe"> -->
                                    <table class="">
                                        <thead style="background-color:#0047aa;color:white; border: 1px solid white;">
                                            <tr class="item_header bg-gradient-directional-blue white"
                                                style="margin-bottom:10px;">
                                                <th width="5%" class="text-center">{{ trans('No') }}</th>
                                                <th width="18%" class="text-center">{{ trans('Item Name') }}
                                                </th>
                                                <th width="15%" class="text-center">{{ trans('Total Quantity') }}
                                                </th>
                                                <th width="10%" class="text-center">{{ trans('Unit') }}</th>

                                                <th width="8%" class="text-center">{{ trans('Quantity') }}
                                                </th>






                                            </tr>

                                        </thead>
                                        <tbody id="showitem123">
                                            <tr>
                                                <td class="text-center" id="count">1</td>
                                                <td><input type="text" class="form-control productname typeahead"
                                                        name="part_number[]"
                                                        placeholder="{{ trans('Enter Part Number') }}"
                                                        id='productname-0' autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_item_name typeahead result_item_name"
                                                        name="result_item_name[]" id="result_item_name-0"
                                                        autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_descriptions typeahead descriptions"
                                                        name="result_descriptions[]" id="result_descriptions-0"
                                                        autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_product_code typeahead result_product_code"
                                                        name="result_product_code[]" id="result_product_code-0"
                                                        autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_id typeahead result_id"
                                                        name="result_id[]" id="result_id-0" autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control item_id typeahead item_id"
                                                        name="item_id[]" id="item_id-0" autocomplete="off">
                                                    <input type="hidden"
                                                        class="form-control result_expired_date typeahead result_expired_date"
                                                        name="result_expired_date[]" id="result_expired_date-0"
                                                        autocomplete="off">
                                                </td>
                                                <td><input type="text" class="form-control total_product_qty"
                                                        name="total_product_qty[]" id="total_amount-0"
                                                        autocomplete="off" readonly>
                                                </td>
                                                <td><select name="unit[]" id="unit-0"
                                                        class="form-control unit"></select></td>
                                                <td><input type="text" class="form-control req amnt"
                                                        name="product_qty[]" id="amount-0" autocomplete="off"
                                                        value="1"><input type="hidden" id="alert-0"
                                                        value="" name="alert[]"></td>
                                                <!-- <td><input type="text" class="form-control unit " name="unit[]" id="unit-0" autocomplete="off">  </td> -->





                                                <input type="hidden" class="form-control vat " name="product_tax[]"
                                                    id="vat-0" value="0">
                                                <input type="hidden" name="total_tax[]" id="taxa-0"
                                                    value="0">
                                                <input type="hidden" name="total_discount[]" id="disca-0"
                                                    value="0">
                                                <input type="hidden" class="ttInput" name="product_subtotal[]"
                                                    id="total-0" value="0">
                                                <input type="hidden" class="pdIn" name="product_id[]"
                                                    id="pid-0" value="0">

                                                <input type="hidden" name="unit_m[]" id="unit_m-0" value="1">
                                                <input type="hidden" name="code[]" id="hsn-0" value="">
                                                <input type="hidden" name="serial[]" id="serial-0" value="">
                                                {{-- <td></td> --}}
                                            </tr>
                                        </tbody>

                                        <tr class="last-item-row sub_c">
                                            <td></td>
                                            <td class="add-row">
                                                <button type="button" class="btn btn-success" id="addproduct"
                                                    style="margin-top:30px;margin-bottom:20px;">
                                                    <i class="fa fa-plus-square"></i> {{ trans('Add row') }}
                                                </button>
                                                <!-- <button type="button" class="btn btn-primary" id="calculate" style="margin-top:30px;margin-bottom:20px;">
                                                    Calculate
                                                </button> -->

                                                {{-- <a href="{{ URL('part') }}" target="_blank"> <button type="button" class="text-white btn btn-secondary">
                                                    <i class="fa fa-plus-square"></i>View Parts
                                                </button></a> --}}
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




                                            </tr>

                                            <!-- <tr class="sub_c " style="display: table-row;">
                                                <td colspan="12"> <label for="remark">Remark</label>
                                                    <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>

                                                </td>
                                            </tr> -->
                                            <tr class="sub_c " style="display: table-row;">


                                                <td align="right" colspan="9">

                                                    <button id="submitButton" class="mt-3 btn btn-danger"
                                                        type="submit">Transfer</button>


                                                    <a href="{{ url('show_transfer_history') }}" type="submit"
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
        $(document).ready(function() {
            $('#item-0').typeahead({
                source: function(query, process) {
                    return $.ajax({
                        url: "{{ route('autocomplete-part-code-invoice') }}",
                        method: 'POST',
                        data: {
                            query: query
                        },
                        dataType: 'json',
                        success: function(data) {
                            process(data);
                        }
                    });
                }
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            let count = 0;

            function initializeTypeahead(count) {
                // $('#productname-' + count).typeahead({
                // source: function(query, process) {
                //     return $.ajax({
                //         url: "{{ route('autocomplete.part-code-invoice') }}",
                //         method: 'POST',
                //         data: {
                //             query: query
                //         },
                //         dataType: 'json',
                //         success: function(data) {
                //             process(data);
                //         }
                //     });
                // }

                // });
                var previousSelection = ''; // Variable to store the previously selected option
                // $('#productname-' + count).typeahead({

                //     source: function(query, process) {
                //         // Get the selected value from the select box
                //         var selectedLocation = $('#from_location').val();

                //         return $.ajax({
                //             url: "{{ url('autocomplete-part-code-location') }}",
                //             method: 'POST',
                //             data: {
                //                 query: query,
                //                 location: selectedLocation // Pass the selected location to the server
                //             },
                //             dataType: 'json',
                //             success: function(data) {
                //                 process(data);
                //             }
                //         });
                //     }
                // });
                $('#productname-' + count).typeahead({
                    source: function(query, process) {

                        var selectedLocation = $('#from_location').val();
                        return $.ajax({
                            url: "{{ url('autocomplete-part-code-invoice') }}",
                            method: 'POST',
                            data: {
                                query: query,
                                location: selectedLocation,
                            },
                            dataType: 'json',
                            success: function(data) {
                                const formattedData = data.map(function(item) {
                                    const itemName = item.item_name || '';
                                    const description = item.description || '';
                                    const productCode = item.product_code || '';
                                    const expiredDate = item.expired_date || '';

                                    const displayText = itemName +
                                        (description || productCode ? ' (' +
                                            description : '') +
                                        (description && productCode ? ' - ' : '') +
                                        (productCode ? productCode : '') +
                                        (description || productCode ? ')' : '') +
                                        (expiredDate ? ' (' + expiredDate + ' )' :
                                            '');

                                    return {
                                        item_name: itemName,
                                        description: description,
                                        product_code: productCode,
                                        id: item.id,
                                        item_id: item.item_id,
                                        expired_date: expiredDate,
                                        display: displayText
                                    };
                                });

                                process(formattedData);
                            }
                        });

                    },
                    displayText: function(item) {
                        return item.display;
                    },
                    afterSelect: function(item) {
                        $('#result_descriptions-' + count).val(item.description);
                        $('#result_product_code-' + count).val(item.product_code);
                        $('#result_item_name-' + count).val(item.item_name);
                        $('#result_id-' + count).val(item.id);
                        $('#item_id-' + count).val(item.item_id);
                        $('#result_expired_date-' + count).val(item.expired_date);
                    },
                    autoSelect: true
                });


                $('#from_location').change(function() {
                    var selectedValue = $(this).val();

                    // Clear the selected value in the 'to_location' select box
                    $('#to_location').val('');

                    // Re-show the previously hidden option (if any)
                    if (previousSelection !== '') {
                        $('#to_location option[value="' + previousSelection + '"]').show();
                    }

                    // Hide the newly selected option
                    $('#to_location option[value="' + selectedValue + '"]').hide();

                    // Update the previous selection to the current one
                    previousSelection = selectedValue;
                });
            }

            function initializeTypeaheads() {
                for (let i = 0; i <= count; i++) {
                    initializeTypeahead(i);
                }
            }

            // function updateItemName(item_name, row) {
            //     let itemNameInput = row.find('.price');
            //     let total_quantity = row.find('.total_product_qty');
            //     var selectedLocation = $('#from_location').val();
            //     var unit = row.find('.unit');

            //     $.ajax({

            //         type: 'POST',
            //         url: "{{ url('get-part-data-location') }}",
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             item_name: item_name,
            //             location: selectedLocation
            //         },
            //         success: function(data) {
            //             itemNameInput.val(data.retail_price);
            //             // Calculate level 1 quantity
            //             var lvl1 = Math.floor((data.quantity ?? 0) / (data.unit2 ?? 1));
            //             var level1qty = lvl1;

            //             // Calculate remaining quantity for level 2
            //             var first_lvl2 = (data.quantity ?? 0) % (data.unit2 ?? 1);
            //             var level2qtyValue = Math.floor(first_lvl2); // Just the numeric value
            //             var level2qty = level2qtyValue;

            //             // Calculate fractional part for level 3
            //             var level3_fractional = (first_lvl2 - level2qtyValue) * (data.unit3 ?? 1);
            //             var level3qty = Math.ceil(level3_fractional - 0.5);

            //             total_quantity.val(
            //                 (level1qty != 0 ? level1qty + " " + data.name1 : "") +
            //                 (level2qty != 0 ? level2qty + " " + data.name2 : "") +
            //                 (level3qty != 0 ? level3qty + " " + data.name3 : "")
            //             );
            //             if (data.name2 != null && data.name3 != null) {
            //                 unitdata = [data.name1, data.name2, data.name3];
            //             } else if (data.name2 != null && data.name3 == null) {
            //                 unitdata = [data.name1, data.name2];
            //             } else if (data.name2 == null && data.name3 != null) {
            //                 unitdata = [data.name1, data.name3];
            //             } else if (data.name2 == null && data.name3 == null) {
            //                 unitdata = [data.name1];
            //             } else {
            //                 unitdata = [data.name1];
            //             }

            //             // Assuming data is an array of items
            //             // e.g., data = ['item1', 'item2', 'item3']
            //             $.each(unitdata, function(index, item) {
            //                 let option = $('<option></option>').val(item).text(item);
            //                 unit.append(option);
            //             });
            //             unit.trigger('change');


            //         },
            //         error: function(error) {
            //             console.error(error);
            //         }
            //     });
            // }

            function updateItemName(item_name, row, description, item_id, cuz_name, expired_date) {
                let itemNameInput = row.find('.price');
                let total_quantity = row.find('.total_product_qty');
                var selectedLocation = $('#from_location').val();
                var unit = row.find('.unit');
                var selectedCategory = $(
                    '#sale_price_category').val();


                var newRowElement = $(
                    "#showitem123 tr:last");

                $.ajax({
                    type: 'POST',
                    url: "{{ url('get-part-data-location') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        result_item_name: item_name,
                        result_descriptions: description,
                        // result_product_code: product_code,
                        item_id: item_id,
                        result_expired_date: expired_date,
                        location: selectedLocation,
                    },
                    success: function(data) {


                        itemNameInput.val(data.retail_price);

                        var unit2 = data.unit2 && data.unit2 > 0 ? data.unit2 :
                            1;
                        var unit3 = data.unit3 && data.unit3 > 0 ? data.unit3 :
                            1;

                        var lvl1 = Math.floor((data.quantity ?? 0) / unit2);
                        var level1qty = lvl1;

                        var first_lvl2 = (data.quantity ?? 0) % unit2;
                        var level2qtyValue = Math.floor(first_lvl2); // Just the numeric value
                        var level2qty = level2qtyValue;

                        var level3_fractional = (first_lvl2 - level2qtyValue) * unit3;
                        var level3qty = Math.ceil(level3_fractional - 0.5);

                        total_quantity.val(
                            (level1qty != 0 ? level1qty + " " + data.name1 + " " : "") +
                            (level2qty != 0 ? level2qty + " " + data.name2 + " " : "") +
                            (level3qty != 0 ? level3qty + " " + data.name3 : "")
                        );


                        if (data.name2 != null && data.name3 != null) {
                            unitdata = [data.name1, data.name2, data.name3];
                        } else if (data.name2 != null && data.name3 == null) {
                            unitdata = [data.name1, data.name2];
                        } else if (data.name2 == null && data.name3 != null) {
                            unitdata = [data.name1, data.name3];
                        } else if (data.name2 == null && data.name3 == null) {
                            unitdata = [data.name1];
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


                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

            }


            $("#addproduct").click(function(e) {
                e.preventDefault();




                count++;
                let rowCount = $("#showitem123 tr").length;
                let newRow = '<tr>' +
                    '<td class="text-center">' + (rowCount + 1) + '</td>' +
                    '<td style="width:30%"><input type="text" class="form-control productname typeahead" name="part_number[]" id="productname-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_item_name typeahead result_item_name" name="result_item_name[]" id="result_item_name-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_descriptions typeahead result_descriptions" name="result_descriptions[]" id="result_descriptions-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_product_code typeahead result_product_code" name="result_product_code[]" id="result_product_code-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_id typeahead result_id" name="result_id[]" id="result_id-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control item_id typeahead item_id" name="item_id[]" id="item_id-' +
                    count +
                    '" autocomplete="off"><input type="hidden" class="form-control result_expired_date typeahead result_expired_date" name="result_expired_date[]" id="result_expired_date-' +
                    count + '" autocomplete="off"></td>' +
                    '<td style=""><input type="text" class="form-control  total_product_qty" name="total_product_qty[]" id="total_amount-' +
                    count +
                    '"   autocomplete="off" ></td>' +
                    '<td style="width:30%"><select  class="form-control unit" name="unit[]" id="unit-' +
                    count + '"  autocomplete="off" ></td>' +

                    '<td style="width:30%"><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                    count +
                    '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +




                    '<td><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton">Remove</button></td>' +
                    '</tr>';

                $("#showitem123").append(newRow);

                initializeTypeahead(count);
            });

            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent();
                $(row_item).remove();

                // Update row numbers
                $('#showitem123 tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });

                initializeTypeaheads();
            });

            $(document).on('click', '.typeahead .dropdown-item', function(e) {
                e.preventDefault();

                if ($("#customer").val()) {} else {
                    const row = $(this).closest('tr');

                    const item_name = row.find('.result_item_name').val();
                    const description = row.find('.result_descriptions').val();
                    // const product_code = row.find('.result_product_code').val();
                    const item_id = row.find('.result_id').val();

                    const expired_date = row.find('.result_expired_date').val();
                    let cuz_name = $("#type").val();
                    updateItemName(item_name, row, description, item_id, cuz_name, expired_date);
                    $('#productname').val('');
                }
            });


            // Initialize typeahead for the first row
            initializeTypeahead(count);





        });
    </script>
    <script>
        $(document).on('click', '.remove_item_btn', function(e) {
            e.preventDefault();
            let row_item = $(this).parent().parent();
            $(row_item).remove();
            count--;
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
        $(document).on('keydown', '.form-control', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addproduct').click();

            }
        });
    </script>


</body>

</HTML>
