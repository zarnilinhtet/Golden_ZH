@include('layouts.header')

<style>
    .big-checkbox {
        width: 20px;
        height: 20px;
    }

    .table-responsive {
        position: relative;
        overflow: visible !important;
    }


    @media (max-width: 768px) {

        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .table {
            font-size: 12px;
            width: 700px !important;
        }
    }
</style>

<link rel="stylesheet" href="{{ asset('locallink/css/bootstrap.min.css') }}">

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="text-white nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="text-white nav-link" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">

                <div class="btn-group">
                    <button type="button" class="text-white btn dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-1 btn changelogout " style="width: 157px">
                                <i class="fa-solid fa-right-from-bracket "></i> Logout</button>

                        </form>


                    </div>
                </div>
            </ul>
        </nav>
        @include('layouts.sidebar')
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>Treatment Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Treatment Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

            </section>
            <div class="content-body">
                <div class="container-fluid justify-content-center d-flex">
                    <div class="card col-md-12" style="background-color: #007bff26">
                        <div class="card-header">
                            <form action="{{ url('treatment_update', $treatment->id) }}" method="POST" id="myForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Choose Location</label>
                                        <select id="branch" class="form-control" required>

                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <span style="font-weight:bolder">
                                            <label for="customer"
                                                class="caption">{{ trans('Search  Customer Name & Phone ') }}</label>

                                        </span>
                                        <div class="form-group d-flex">
                                            <input type="text" id="customer" name="customer"
                                                class="mr-2 form-control round" autocomplete="off"
                                                placeholder="Search.....">
                                            &nbsp;&nbsp;&nbsp; <button type="submit" class="btn btn-primary"
                                                id="customer_search">Add</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="doctor">Doctor <span class="text-danger">*</span></label>
                                        <select name="doctor_id" id="doctor" class="form-control">

                                            @foreach ($doctors as $doctor)
                                                @if ($doctor->id == $treatment->doctor_id)
                                                    <option value="{{ $doctor->id }}" selected>
                                                        {{ $doctor->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->


                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name"
                                        required autofocus name="name" value="{{ $treatment->name }}">
                                    <input type="hidden" class="form-control" id="customer_id" autofocus
                                        name="customer_id" value="{{ $treatment->customer_id }}">
                                </div>
                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="customer_age">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone_number"
                                        placeholder="Enter Customer Phone Number" name="phno"
                                        value="{{ $treatment->phno }}">
                                </div>
                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="customer_age">Age</label>
                                    <input type="text" class="form-control" id="age"
                                        placeholder="Enter Customer Age" name="age"
                                        value="{{ $treatment->customer_age }}">
                                </div>

                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="customer_gender">Gender</label>
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option value="" disabled
                                            {{ $treatment->customer_gender == '' ? 'selected' : '' }}>
                                            Select Choose Gender
                                        </option>
                                        <option value="Male"
                                            {{ $treatment->customer_gender == 'Male' ? 'selected' : '' }}>
                                            Male
                                        </option>
                                        <option value="Female"
                                            {{ $treatment->customer_gender == 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>
                                        <option value="Others"
                                            {{ $treatment->customer_gender == 'Others' ? 'selected' : '' }}>
                                            Others
                                        </option>
                                    </select>
                                </div>




                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="address">Date Of Birth </label>
                                    <input type="date" class="form-control" id="dob"
                                        placeholder="Enter Date of Birth" name="dob"
                                        value="{{ $treatment->dob }}">
                                </div>
                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="address">NRC </label>
                                    <input type="text" class="form-control" id="nrc"
                                        placeholder="Enter NRC" name="nrc" value="{{ $treatment->nrc }}">
                                </div>







                                @php
                                    $patientStatuses = json_decode($treatment->patient_status, true) ?? [];
                                @endphp

                                <div class="form-group col-md-5">
                                    <label>Patient Status</label>
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input big-checkbox"
                                                    name="patient_status[]" id="new_patient" value="New Patient"
                                                    {{ in_array('New Patient', $patientStatuses) ? 'checked' : '' }}>
                                                <label class="form-check-label mx-1" for="new_patient">New Patient
                                                    (အသစ်)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input big-checkbox"
                                                    name="patient_status[]" id="follow_up" value="Follow Up"
                                                    {{ in_array('Follow Up', $patientStatuses) ? 'checked' : '' }}>
                                                <label class="form-check-label mx-1" for="follow_up">Follow Up
                                                    (ရက်ချိန်းပြန်ပြ)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input big-checkbox"
                                                    name="patient_status[]" id="post_op" value="Post Op"
                                                    {{ in_array('Post Op', $patientStatuses) ? 'checked' : '' }}>
                                                <label class="form-check-label mx-1" for="post_op">Post Op
                                                    (ခွဲပြီးလူနာ)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1"></div>

                                @php
                                    $patientType = json_decode($treatment->patient_type, true) ?? [];
                                @endphp

                                <div class="form-group col-md-6">
                                    <label>Patient Type</label>
                                    <div class="row">
                                        <div class="col-12 col-sm-5">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input big-checkbox"
                                                    name="patient_type[]" id="disable" value="Disable"
                                                    {{ in_array('Disable', $patientType) ? 'checked' : '' }}>
                                                <label class="form-check-label mx-1" for="disable">Disable
                                                    (မသန်စွမ်း)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-5">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input big-checkbox"
                                                    name="patient_type[]" id="pregnant_woman" value="Pregnant Woman"
                                                    {{ in_array('Pregnant Woman', $patientType) ? 'checked' : '' }}>
                                                <label class="form-check-label mx-1" for="pregnant_woman">Pregnant
                                                    Woman (ကိုယ်ဝန်သည်)</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="age">Department <span class="text-danger">*</span></label>
                                    <select class="form-control" name="department" id="department" required>
                                        <option value="" selected disabled>Select Service
                                        </option>
                                        <option value="OG" @if ($treatment->department == 'OG') selected @endif>OG
                                        </option>
                                        <option value="OTO" @if ($treatment->department == 'OTO') selected @endif>
                                            OTO</option>
                                        <option value="Surgery" @if ($treatment->department == 'Surgery') selected @endif>
                                            Surgery</option>
                                        <option value="General" @if ($treatment->department == 'General') selected @endif>
                                            General</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="age">IN/OUT Patient <span class="text-danger">*</span>
                                    </label>
                                    <select name="inout_patient" id="inout_patient" class="form-control" required>
                                        <option value="" selected disabled>Select One
                                        </option>
                                        <option value="IN Patient" @if ($treatment->inout_patient == 'IN Patient') selected @endif>IN
                                            Patient</option>
                                        <option value="OUT Patient" @if ($treatment->inout_patient == 'OUT Patient') selected @endif>
                                            OUT Patient</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="deposit">Deposit</label>
                                    <input type="number" class="form-control" id="deposit"
                                        placeholder="Enter Deposit" name="customer_deposit"
                                        value="{{ $treatment->customer_deposit }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">C.D.C Number</label>
                                    <input type="text" name="cdc_no" id="cdc_no" class="form-control"
                                        placeholder="Enter C.D.C Number" value="{{ $treatment->cdc_no }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">Company</label>
                                    <input type="text" name="company" id="company" class="form-control"
                                        placeholder="Enter Company" value="{{ $treatment->company }}">
                                </div>
                                @if (auth()->user()->is_admin == '1')
                                    <div class="form-group col-md-6">
                                        <label for="branch">Location<span class="text-danger">*</span></label>
                                        <select name="branch" id="location" class="form-control">
                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $branch->id == $treatment->location ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="form-group col-md-6">
                                        <label for="branch">Location<span class="text-danger">*</span></label>
                                        <select name="branch" class="form-control" disabled>
                                            @php
                                                $userPermissions = auth()->user()->level
                                                    ? json_decode(auth()->user()->level)
                                                    : [];
                                            @endphp
                                            <option value="" disabled>Select Location</option>
                                            @foreach ($branchs as $branch)
                                                @if (in_array($branch->id, $userPermissions))
                                                    <option value="{{ $branch->id }}"
                                                        {{ $branch->id == $treatment->location ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-md-6" style="display: none;">
                                    <label for="crc">Customer Type </label>

                                    <select name="type" id="type" class="form-control">
                                        <option selected disabled>Select Customer Type</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Whole Sale">Whole Sale</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address" id="address" cols="10" rows="3"
                                        placeholder="Enter Address">{{ $treatment->address }}</textarea>
                                </div>


                            </div>




                            <hr class="my-3">


                            <div class="form-group">
                                <label for="investigations">History</label>
                                <textarea name="history" class="form-control" id="" cols="30" rows="8">{{ $treatment->history }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="investigations">Physical Examination</label>
                                <textarea name="physical_examination" class="form-control" id="" cols="30" rows="8">{{ $treatment->physical_examination }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="investigations">Investigations</label>
                                <textarea name="investigations" class="form-control" id="" cols="30" rows="8">{{ $treatment->investigation }}</textarea>

                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="address">File1 </label>

                                    <input type="file" class="form-control " id="name" autofocus
                                        name="file1" value="">
                                    <span class="text-danger">{{ $treatment->file1 }}</span>

                                </div>
                                <div class="form-group col-md-4">
                                    <label for="address">File2</label>
                                    <input type="file" class="form-control" name="file2">
                                    <span class="text-danger">{{ $treatment->file2 }}</span>

                                </div>


                                <div class="form-group col-md-4">
                                    <label for="address">File3 </label>
                                    <input type="file" class="form-control" name="file3">
                                    <span class="text-danger">{{ $treatment->file3 }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="address">File4 </label>
                                    <input type="file" class="form-control" name="file4">
                                    <span class="text-danger">{{ $treatment->file4 }}</span>

                                </div>

                                <div class="form-group col-md-4">
                                    <label for="address">File5</label>
                                    <input type="file" class="form-control" name="file5">
                                    <span class="text-danger">{{ $treatment->file5 }}</span>

                                </div>

                            </div>
                            <div class="form-group">
                                <label for="diagnosis">Diagnosis</label>
                                <textarea name="diagnosis" id="" class="form-control" rows="8">{{ $treatment->diagnosis }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="treatment">Treatment</label>
                                <textarea name="treatment" id="" cols="30" rows="8" class="form-control">{{ $treatment->treatment }}</textarea>
                            </div>






                        </div>



                        <div class="mb-3 ml-3 d-flex justify-content-end">
                            <a href="{{ url('treatment') }}" class="btn btn-danger mx-1">Back</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>



                    </form>
                </div>

            </div>

        </div>

    </div>



    </div>




    @include('layouts.footer')
    <script src="{{ asset('locallink/js/ajax_jquery.js') }}"></script>
    <script src="{{ asset('locallink/js/moment.min.js') }}"></script>
    <script src="{{ asset('locallink/js/typehead.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#branch').on('change', function() {


                const selectedBranch = $(this).val();
                const doctorSelect = document.getElementById('doctor');
                // Clear the doctor select options
                doctorSelect.innerHTML = '<option value="">Select Doctor</option>';

                // Filter and add the doctors based on the selected branch
                @foreach ($doctors as $doctor)
                    if (selectedBranch === '{{ $doctor->branch }}') {
                        const option = document.createElement('option');
                        option.value = '{{ $doctor->id }}';
                        option.textContent = '{{ $doctor->name }}';
                        doctorSelect.appendChild(option);
                    }
                @endforeach
            });


        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {

            let count = 0;
            // search item name suggestion (get item name from db)
            function initializeTypeahead(count) {
                $('#productname-' + count).typeahead({
                    source: function(query, process) {
                        if (!$('#sell_status-' + count).is(':checked')) {
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
                                    const formattedData = data.map(function(item) {
                                        const itemName = item.item_name || '';
                                        const description = item.description || '';
                                        const productCode = item.product_code || '';
                                        const expiredDate = item.expired_date || '';

                                        const displayText = itemName +
                                            (description || productCode ? ' (' +
                                                description : '') +
                                            (description && productCode ? ' - ' :
                                                '') +
                                            (productCode ? productCode : '') +
                                            (description || productCode ? ')' :
                                                '') +
                                            (expiredDate ? ' (' + expiredDate +
                                                ' )' :
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
                        }
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
            }

            function initializeTypeaheads() {
                for (let i = 0; i <= count; i++) {
                    initializeTypeahead(i);
                }
            }


            function updateItemName(item_name, row, description, product_code, cuz_name, expired_date) {
                let itemNameInput = row.find('.price');
                let partDesc = row.find('.description');
                let exp_date = row.find('.exp_date');
                let radio_category = row.find('.radio_category');
                let item_unit = row.find('.item_unit');
                let retail_price = row.find('.retail_price');
                let warehouse = row.find('.warehouse');
                let stock_and_service = row.find('.stock_and_service');
                let buy_price = row.find('.buy_price');
                var Selectedlocation = $('#location').val();


                $.ajax({
                    type: 'POST',
                    url: "{{ route('get-part-data-invoice') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        result_item_name: item_name,
                        result_descriptions: description,
                        result_product_code: product_code,
                        result_expired_date: expired_date,
                        location: Selectedlocation,
                    },
                    success: function(data) {
                        itemNameInput.val(data.wholesale_price);
                        partDesc.val(data.descriptions);
                        exp_date.val(data.expired_date);
                        radio_category.val(data.radio_category);
                        item_unit.val(data.item_unit);
                        retail_price.val(data.retail_price);
                        warehouse.val(data.warehouse_id);
                        stock_and_service.val(data.stock_and_service);
                        buy_price.val(data.buy_price);

                        // Check reorder level
                        if (parseFloat(data.reorder_level_stock) >= parseFloat(data.quantity)) {
                            alert(data.quantity + " quantity!");
                        }

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
                    '<td style="display:none"><input type="hidden" class="form-control barcode typeahead" name="barcode[]" id="barcode-' +
                    count + '" autocomplete="off"></td>' +
                    '<td>' +
                    '<div class="row align-items-center">' +
                    '<div class="col-auto">' +
                    '<input type="checkbox" id="sell_status-' + count +
                    '" value="1" class="form-check-input sell_status ml-1" style="margin-top: -3px;" />' +
                    '<input type="hidden" name="sell_status[]" id="sell_status_input-' + count +
                    '" value="0" class="form-control sell_status_input" />' +
                    '</div>' +
                    '<div class="col">' +
                    '<input type="text" class="form-control productname typeahead item_name" name="part_number[]" id="productname-' +
                    count +
                    '" autocomplete="off" placeholder="Enter Part Number"><input type="hidden" class="form-control result_item_name typeahead result_item_name" name="result_item_name[]" id="result_item_name-' +
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
                    count + '" autocomplete="off">' +
                    '</div>' +
                    '</div>' +
                    '</td>' +

                    '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' +
                    count +
                    '"   autocomplete="off" value="1"><input type="hidden" id="alert-0" value="" name="alert[]"></td>' +
                    '<td><input type="text" class="form-control item_unit " name="item_unit[]" id="item_unit-' +
                    count +
                    '" autocomplete ="off" required> </td>' +

                    '<td><input type="text" class="form-control retail_price" name="retail_price[]" value="0" id="retail_price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display: none;"><input type="text" class="form-control price" name="product_price[]" value="0" id="price-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display: none;"><input type="text" class="form-control buy_price" name="buy_price[]" value="0" id="buy_price-' +
                    count + '"   autocomplete="off"></td>' +

                    '<td><input type="text" class="form-control exp_date" name="exp_date[]" id="exp_date-' +
                    count + '"   autocomplete="off"></td>' +

                    '<td style="display : none;"><input type="text" class="form-control radio_category" name="radio_category[]" id="radio_category-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control stock_and_service" name="stock_and_service[]" id="stock_and_service-' +
                    count + '"   autocomplete="off"></td>' +
                    '<td style="display : none;"><input type="text" class="form-control warehouse " name="warehouse[]" id="warehouse-' +
                    count +
                    '"   autocomplete="off"></td>' +

                    '<td style="width: 3%;"><button type="submit" class="btn btn-danger remove_item_btn" id="removebutton"><i class="fa-solid fa-minus"></i></button></td>' +
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
                    const product_code = row.find('.result_product_code').val();
                    const expired_date = row.find('.result_expired_date').val();
                    let cuz_name = $("#type").val();
                    updateItemName(item_name, row, description, product_code, cuz_name, expired_date);
                    $('#productname').val('');
                }
            });



            // Initialize typeahead for the first row
            initializeTypeahead(count);


        });

        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('sell_status')) {
                    const checkbox = e.target;
                    const inputId = checkbox.id.replace('sell_status-', 'sell_status_input-');
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.value = checkbox.checked ? '1' : '0';
                    }
                }
            });
        });

        $(document).ready(function() {
            var path = "{{ route('customer_search') }}";
            $('#customer').typeahead({
                source: function(query, process) {
                    var Selectedlocation = $('#location').val();
                    return $.get(path, {
                        query: query,
                        location: Selectedlocation,
                    }, function(data) {
                        var formattedData = [];
                        $.each(data, function(index, customer) {
                            if (customer.name.toLowerCase().indexOf(query
                                    .toLowerCase()) !== -1) {
                                formattedData.push(customer.name);
                            } else if (customer.phno.indexOf(query) !== -1) {
                                formattedData.push(customer.phno);
                            }
                        });
                        return process(formattedData);
                    });
                }
            });


            $(document).on('click', '#customer_search', function(e) {
                e.preventDefault();
                let serialNumber = $("#customer").val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('customer_search_fill') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        model: serialNumber,
                        location: $('#location').val()
                    },
                    success: function(data) {
                        console.log(data);

                        if (data.customer) {
                            $("#name").val(data.customer.name);
                            $("#customer_id").val(data.customer.id);
                            $("#phone_number").val(data.customer.phno);
                            $("#age").val(data.customer.age);
                            $("#gender").val(data.customer.gender);
                            $("#dob").val(data.customer.dob);
                            $("#nrc").val(data.customer.nrc);
                            // $("#status").val(data.customer.patient_status);
                            // $("#patient_type").val(data.customer.patient_type);
                            $("#department").val(data.customer.department);
                            $("#inout_patient").val(data.customer.inout_patient);
                            $("#deposit").val(data.customer.deposit);
                            $("#cdc_no").val(data.customer.cdc_no);
                            $("#company").val(data.customer.company);
                            $("#branch").val(data.customer.branch);
                            // $("#status").val(data.customer.patient_status);
                            $("#address").val(data.customer.address);

                            let patientStatus = JSON.parse(data.customer.patient_status);
                            let patientType = JSON.parse(data.customer.patient_type);

                            $("input[name='patient_status[]']").each(function() {
                                if (patientStatus.includes($(this).val())) {
                                    $(this).prop("checked", true);
                                } else {
                                    $(this).prop("checked", false);
                                }
                            });

                            $("input[name='patient_type[]']").each(function() {
                                if (patientType.includes($(this).val())) {
                                    $(this).prop("checked", true);
                                } else {
                                    $(this).prop("checked", false);
                                }
                            });

                            $("#customer").val('');
                        } else {
                            console.error("Customer not found");
                            $("#customer").val('');
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

            });


            $(document).ready(function() {
                const checkboxDivMappings = [{
                        checkbox: '#preseting_symptom_other',
                        div: '#preseting_symptom_other_div'
                    },
                    {
                        checkbox: '#eye_history_other',
                        div: '#eye_history_other_div'
                    },
                    {
                        checkbox: '#medical_history_other',
                        div: '#medical_history_other_div'
                    },
                    {
                        checkbox: '#diagnosis_other',
                        div: '#diagnosis_other_div'
                    },
                    {
                        checkbox: '#manamement_eye_drops_other',
                        div: '#manamement_eye_drops_other_div'
                    },
                    {
                        checkbox: '#manamement_medicine_other',
                        div: '#manamement_medicine_other_div'
                    },
                    {
                        checkbox: '#manamement_laser_other',
                        div: '#manamement_laser_other_div'
                    },
                    {
                        checkbox: '#manamement_surgery_other',
                        div: '#manamement_surgery_other_div'
                    },
                ];

                function initializeCheckboxDivToggles(mapping) {
                    const checkbox = $(mapping.checkbox);
                    const div = $(mapping.div);

                    if (checkbox.prop('checked')) {
                        div.show();
                    } else {
                        div.hide();
                    }

                    checkbox.change(function() {
                        if ($(this).prop('checked')) {
                            div.show();
                        } else {
                            div.hide();
                        }
                    });
                }

                checkboxDivMappings.forEach(initializeCheckboxDivToggles);
            });



        });
    </script>
