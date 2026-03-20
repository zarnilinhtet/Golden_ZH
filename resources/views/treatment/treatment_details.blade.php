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
                                <h1>Treatment Details</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Treatment Details
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

                            <h4>Treatment Details</h4>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ url('treatment_update', $treatment->id) }}" method="POST" id="myForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4 col-lg-4">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter Name" required autofocus name="name"
                                            value="{{ $treatment->name }}" disabled>
                                        <input type="hidden" class="form-control" id="customer_id" autofocus
                                            name="customer_id" value="{{ $treatment->customer_id }}">
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4">
                                        <label for="name">Doctor Name <span class="text-danger">*</span></label>
                                        @if ($treatment->doctor_id == null)
                                            <input type="text" class="form-control" id="name" required autofocus
                                                name="name" value="" disabled>
                                        @else
                                            <input type="text" class="form-control" id="name"
                                                placeholder="Enter Name" required autofocus name="name"
                                                value="{{ $treatment->doctor->name }}" disabled>
                                        @endif
                                        <input type="hidden" class="form-control" id="customer_id" autofocus
                                            name="customer_id" value="{{ $treatment->customer_id }}">
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4">
                                        <label for="customer_age">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone_number"
                                            placeholder="Enter Customer Phone Number" name="phone_number"
                                            value="{{ $treatment->phno }}" disabled>
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4">
                                        <label for="customer_age">Age</label>
                                        <input type="text" class="form-control" id="customer_age"
                                            placeholder="Enter Customer Age" name="customer_age"
                                            value="{{ $treatment->customer_age }}" disabled>
                                    </div>

                                    <div class="form-group col-md-4 col-lg-4">
                                        <label for="customer_gender">Gender</label>
                                        <select class="form-control" name="customer_gender" id="customer_gender"
                                            required disabled>
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
                                            value="{{ $treatment->dob }}" disabled>
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4">
                                        <label for="address">NRC </label>
                                        <input type="text" class="form-control" id="nrc"
                                            placeholder="Enter NRC" name="nrc" value="{{ $treatment->nrc }}"
                                            disabled>
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4">
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
                                                        {{ in_array('New Patient', $patientStatuses) ? 'checked' : '' }}
                                                        onclick="return false;">
                                                    <label class="form-check-label mx-1" for="new_patient">New Patient
                                                        (အသစ်)</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_status[]" id="follow_up" value="Follow Up"
                                                        {{ in_array('Follow Up', $patientStatuses) ? 'checked' : '' }}
                                                        onclick="return false;">
                                                    <label class="form-check-label mx-1" for="follow_up">Follow Up
                                                        (ရက်ချိန်းပြန်ပြ)</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_status[]" id="post_op" value="Post Op"
                                                        {{ in_array('Post Op', $patientStatuses) ? 'checked' : '' }}
                                                        onclick="return false;">
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
                                                        {{ in_array('Disable', $patientType) ? 'checked' : '' }}
                                                        onclick="return false;">
                                                    <label class="form-check-label mx-1" for="disable">Disable
                                                        (မသန်စွမ်း)</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-5">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_type[]" id="pregnant_woman"
                                                        value="Pregnant Woman"
                                                        {{ in_array('Pregnant Woman', $patientType) ? 'checked' : '' }}
                                                        onclick="return false;">
                                                    <label class="form-check-label mx-1" for="pregnant_woman">Pregnant
                                                        Woman (ကိုယ်ဝန်သည်)</label>
                                                </div>
                                            </div>
                                            {{-- <div class="col-12 col-sm-4">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" class="form-check-input big-checkbox"
                                                        name="patient_type[]" id="foc" value="FOC"
                                                        {{ in_array('FOC', $patientType) ? 'checked' : '' }}
                                                        onclick="return false;">
                                                    <label class="form-check-label mx-1" for="foc">FOC</label>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="age">Department <span class="text-danger">*</span></label>
                                        <select class="form-control" name="department" id="department" required
                                            disabled>
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
                                        <select name="inout_patient" id="inout_patient" class="form-control" required
                                            disabled>
                                            <option value="" selected disabled>Select One
                                            </option>
                                            <option value="IN Patient"
                                                @if ($treatment->inout_patient == 'IN Patient') selected @endif>IN Patient</option>
                                            <option value="OUT Patient"
                                                @if ($treatment->inout_patient == 'OUT Patient') selected @endif>OUT Patient</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="deposit">Deposit</label>
                                        <input type="number" class="form-control" id="deposit"
                                            placeholder="Enter Deposit" name="customer_deposit"
                                            value="{{ $treatment->customer_deposit }}" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">C.D.C Number</label>
                                        <input type="text" name="cdc_no" id="cdc_no" class="form-control"
                                            placeholder="Enter C.D.C Number" value="{{ $treatment->cdc_no }}"
                                            disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">Company</label>
                                        <input type="text" name="company" id="company" class="form-control"
                                            placeholder="Enter Company" value="{{ $treatment->company }}" disabled>
                                    </div>
                                    @if (auth()->user()->is_admin == '1')
                                        <div class="form-group col-md-6">
                                            <label for="branch">Location<span class="text-danger">*</span></label>
                                            <select name="branch" id="location" class="form-control" disabled>
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
                                            <select name="branch" id="location" class="form-control" disabled>
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
                                            placeholder="Enter Address" disabled>{{ $treatment->address }}</textarea>
                                    </div>


                                </div>




                                <hr class="my-3">

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="address">File1 </label>


                                        <span class="text-danger">{{ $treatment->file1 }}</span>
                                        @if ($treatment->file1)
                                            <a href="{{ asset('logos/' . ($treatment->file1 ?? 'null')) }}"
                                                class="btn btn-info btn-sm btn-round ms-2 " download>Download</a>
                                        @else
                                            <span class="text-danger ml-2">No File</span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="address">File2</label>

                                        <span class="text-danger">{{ $treatment->file2 }}</span>
                                        @if ($treatment->file2)
                                            <a href="{{ asset('logos/' . ($treatment->file2 ?? 'null')) }}"
                                                class="btn btn-info btn-sm btn-round ms-2 " download>Download</a>
                                        @else
                                            <span class="text-danger ml-2">No File</span>
                                        @endif
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label for="address">File3 </label>

                                        <span class="text-danger">{{ $treatment->file3 }}</span>
                                        @if ($treatment->file3)
                                            <a href="{{ asset('logos/' . ($treatment->file3 ?? 'null')) }}"
                                                class="btn btn-info btn-sm btn-round ms-2 " download>Download</a>
                                        @else
                                            <span class="text-danger ml-2">No File</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="address">File4 </label>

                                        <span class="text-danger">{{ $treatment->file4 }}</span>
                                        @if ($treatment->file4)
                                            <a href="{{ asset('logos/' . ($treatment->file4 ?? 'null')) }}"
                                                class="btn btn-info btn-sm btn-round ms-2 " download>Download</a>
                                        @else
                                            <span class="text-danger ml-2">No File</span>
                                        @endif
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="address">File5</label>

                                        <span class="text-danger">{{ $treatment->file5 }}</span>
                                        @if ($treatment->file5)
                                            <a href="{{ asset('logos/' . ($treatment->file5 ?? 'null')) }}"
                                                class="btn btn-info btn-sm btn-round ms-2 " download>Download</a>
                                        @else
                                            <span class="text-danger ml-2">No File</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="investigations">History</label>
                                    <textarea name="history" class="form-control" id="" cols="30" rows="8" disabled>{{ $treatment->history }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="investigations">Physical Examination</label>
                                    <textarea name="physical_examination" class="form-control" id="" cols="30" rows="8" disabled>{{ $treatment->physical_examination }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="investigations">Investigations</label>
                                    <textarea name="investigations" class="form-control" id="" cols="30" rows="8" disabled>{{ $treatment->investigation }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="diagnosis">Diagnosis</label>
                                    <textarea name="diagnosis" id="" class="form-control" rows="8" disabled>{{ $treatment->diagnosis }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="treatment">Treatment</label>
                                    <textarea name="treatment" id="" cols="30" rows="8" class="form-control" disabled>{{ $treatment->treatment }}</textarea>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
