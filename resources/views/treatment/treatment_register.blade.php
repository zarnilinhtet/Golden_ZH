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
                                <h1>Treatment Register</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Treatment Register
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

            </section>
            <div class="content-body">
                <div class="container-fluid justify-content-center d-flex">
                    <div class="card col-md-12 mx-auto" style="background-color: #007bff26">
                        <form action="{{ url('treatment_store') }}" method="POST" id="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
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
                                        <select name="doctor_id" id="doctor" class="form-control" required>
                                            <option value="" selected disabled>Select Doctor</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->


                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" name="customer_id" id="customer_id">
                                    <div class="form-group col-md-4">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Name" required autofocus name="name">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="phno">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone_number"
                                            placeholder="Enter Phone Number" name="phno" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="age">Age </label>
                                        <input type="number" class="form-control" id="age"
                                            placeholder="Enter Age" name="age">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="gender">Gender</label><br>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label for="address">Date Of Birth </label>
                                        <input type="date" class="form-control" id="dob"
                                            placeholder="Enter Date of Birth" name="dob">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="address">NRC </label>
                                        <input type="text" class="form-control" id="nrc"
                                            placeholder="Enter NRC" name="nrc">
                                    </div>
                                </div>

                                <hr class="my-4">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Patient Status</label>
                                        <div class="row">
                                            <div class="col-12 col-sm-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_status[]" id="new_patient" value="New Patient">
                                                    <label class="form-check-label mx-1" for="new_patient">New
                                                        Patient (အသစ်)</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_status[]" id="follow_up" value="Follow Up">
                                                    <label class="form-check-label mx-1" for="follow_up">Follow
                                                        Up (ရက်ချိန်းပြန်ပြ)</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_status[]" id="post_op" value="Post Op">
                                                    <label class="form-check-label mx-1" for="post_op">Post
                                                        Op (ခွဲပြီးလူနာ)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="status">Patient Type <span class="text-danger">*</span> </label>
                                        <div class="row">
                                            <div class="col-12 col-sm-5">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_type[]" id="disable" value="Disable">
                                                    <label class="form-check-label mx-1" for="disable">Disable
                                                        (မသန်စွမ်း)</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-5">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_type[]" id="pregnant_woman"
                                                        value="Pregnant Woman">
                                                    <label class="form-check-label mx-1" for="pregnant_woman">Pregnant
                                                        Woman (ကိုယ်ဝန်သည်)</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>





                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="age">Department <span class="text-danger">*</span></label>
                                        <select class="form-control" name="department" id="department" required>
                                            <option value="" selected disabled>Select Service
                                            </option>
                                            <option value="OG">OG</option>
                                            <option value="OTO">OTO</option>
                                            <option value="Surgery">Surgery</option>
                                            <option value="General">General</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="age">IN/OUT Patient <span class="text-danger">*</span>
                                        </label>
                                        <select name="inout_patient" id="inout_patient" class="form-control"
                                            required>
                                            <option value="" selected disabled>Select One
                                            </option>
                                            <option value="IN Patient">IN Patient</option>
                                            <option value="OUT Patient">OUT Patient</option>
                                        </select>
                                    </div>

                                </div>




                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="deposit">Deposit</label>
                                        <input type="number" class="form-control" id="deposit"
                                            placeholder="Enter Deposit" name="customer_deposit">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">C.D.C Number</label>
                                        <input type="text" name="cdc_no" id="cdc_no" class="form-control"
                                            placeholder="Enter C.D.C Number">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="address">Company</label>
                                        <input type="text" name="company" id="company" class="form-control"
                                            placeholder="Enter Company">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="branch">Location<span class="text-danger">*</span></label>
                                        <select name="branch" class="form-control" required>

                                            @foreach ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address </label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                                {{-- <hr class="my-3"> --}}


                                <div class="form-group">
                                    <label for="investigations">History</label>
                                    <textarea name="history" class="form-control" id="" cols="30" rows="8"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="investigations">Physical Examination</label>
                                    <textarea name="physical_examination" class="form-control" id="" cols="30" rows="8"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="investigations">Investigations</label>
                                    <textarea name="investigations" class="form-control" id="" cols="30" rows="8"></textarea>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="file1">File 1</label>
                                        <input type="file" name="file1" class="form-control" id="file1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="file2">File 2</label>
                                        <input type="file" name="file2" class="form-control" id="file2">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="file3">File 3</label>
                                        <input type="file" name="file3" class="form-control" id="file3">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="file4">File 4</label>
                                        <input type="file" name="file4" class="form-control" id="file4">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="file5">File 5</label>
                                        <input type="file" name="file5" class="form-control" id="file5">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="diagnosis">Diagnosis</label>
                                    <textarea name="diagnosis" id="" class="form-control" rows="8"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="treatment">Treatment</label>
                                    <textarea name="treatment" id="" cols="30" rows="8" class="form-control"></textarea>
                                </div>








                            </div>






                            <div class="mb-3 ml-3 d-flex justify-content-end">
                                <a href="{{ url('treatment') }}" class="btn btn-danger mx-1">Back</a>
                                <button type="submit" class="btn btn-primary">Save</button>
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
            $('#branch').trigger('change');

        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var path = "{{ route('customer_search') }}";
            $('#customer').typeahead({
                source: function(query, process) {
                    var Selectedlocation = $('#branch').val();
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
                        location: $('#branch').val()
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
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
