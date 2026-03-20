@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link  text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  text-white" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


                {{-- <li class="nav-item ml-auto">
                    <a class="nav-link text-white" href="#">
                       Goldenzh

                    </a>
                </li> --}}

            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle  text-white" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
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
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Patient Detail</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Patient Detail
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('delete_success'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('delete_success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <section class="content container-fluid">

                    <div class="row">
                        <div class="col-10 mx-auto">
                            <div class="card" style="background-color: #007bff26">
                                <div class="card-header mt-2 mb-2">
                                    <h4 class="cade-title text-center">Patient Detail </h4>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        @csrf
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="phno"> Patient Name </label>
                                                    <input type="text" class="form-control" id="phone number"
                                                        placeholder="Enter Company Name" name="customer_name"
                                                        value="{{ $customer->name }}" disabled>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="crc"> Phone Number</label>
                                                    <input type="text" class="form-control" id="shopname"
                                                        placeholder="Enter Phone Number" name="phone_number"
                                                        value="{{ $customer->phno }}" disabled>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="">Age</label>
                                                    <input type="text" class="form-control" id="shopname"
                                                        placeholder="" name="phone_number" value="{{ $customer->age }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="gender">Gender</label><br>
                                                    <select name="gender" id="" class="form-control" disabled>
                                                        <option>
                                                            @if ($customer->gender == 'Male')
                                                                Male
                                                            @else
                                                                Female
                                                            @endif
                                                        </option>
                                                    </select>

                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="address">Date Of Birth </label>
                                                    <input type="date" class="form-control" id="address"
                                                        value="{{ $customer->dob }}" name="dob" disabled>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="address">NRC </label>
                                                    <input type="text" class="form-control" id="address"
                                                        value="{{ $customer->nrc }}" disabled>
                                                </div>
                                                @php
                                                    $patientStatuses =
                                                        json_decode($customer->patient_status, true) ?? [];
                                                @endphp
                                                <div class="form-group col-md-5">
                                                    <label for="status">Patient Status</label>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input big-checkbox"
                                                                    name="patient_status[]" id="new_patient"
                                                                    value="New Patient"
                                                                    {{ in_array('New Patient', $patientStatuses) ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label mx-1"
                                                                    for="new_patient">New Patient
                                                                    (အသစ်)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input big-checkbox"
                                                                    name="patient_status[]" id="follow_up"
                                                                    value="Follow Up"
                                                                    {{ in_array('Follow Up', $patientStatuses) ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label mx-1"
                                                                    for="follow_up">Follow Up
                                                                    (ရက်ချိန်းပြန်ပြ)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input big-checkbox"
                                                                    name="patient_status[]" id="post_op"
                                                                    value="Post Op"
                                                                    {{ in_array('Post Op', $patientStatuses) ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label mx-1"
                                                                    for="post_op">Post Op
                                                                    (ခွဲပြီးလူနာ)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                @php
                                                    $patientType = json_decode($customer->patient_type, true) ?? [];
                                                @endphp
                                                <div class="form-group col-md-6">
                                                    <label for="status">Patient Type</label>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-5">
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input big-checkbox"
                                                                    name="patient_type[]" id="disable"
                                                                    value="Disable"
                                                                    {{ in_array('Disable', $patientType) ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label mx-1"
                                                                    for="disable">Disable
                                                                    (မသန်စွမ်း)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-5">
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input big-checkbox"
                                                                    name="patient_type[]" id="pregnant_woman"
                                                                    value="Pregnant Woman"
                                                                    {{ in_array('Pregnant Woman', $patientType) ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label mx-1"
                                                                    for="pregnant_woman">Pregnant
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
                                                    <label for="name">Department </label>
                                                    <select name="department" id="" class="form-control"
                                                        disabled>

                                                        <option value="OG"
                                                            @if ($customer->department == 'OG') selected @endif>OG
                                                        </option>
                                                        <option value="OTO"
                                                            @if ($customer->department == 'OTO') selected @endif>OTO
                                                        </option>
                                                        <option value="Surgery"
                                                            @if ($customer->department == 'Surgery') selected @endif>Surgery
                                                        </option>
                                                        <option value="General"
                                                            @if ($customer->department == 'General') selected @endif>General
                                                        </option>

                                                    </select>
                                                </div>



                                                <div class="form-group col-md-6">
                                                    <label for="name">IN/OUT Patient </label>
                                                    <select name="customer_type" id="" class="form-control"
                                                        disabled>

                                                        <option value="IN Patient"
                                                            @if ($customer->inout_patient == 'IN Patient') selected @endif>IN
                                                            Patient
                                                        </option>
                                                        <option value="OUT Patient"
                                                            @if ($customer->inout_patient == 'OUT Patient') selected @endif>OUT
                                                            Patient
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="">Deposit</label>
                                                    <input type="text" class="form-control" id="deposit"
                                                        placeholder="" name="deposit"
                                                        value="{{ $customer->deposit }}" disabled>
                                                </div>






                                                <div class="form-group col-md-6">
                                                    <label for="address">C.D.C Number</label>
                                                    <input type="text" name="cdc_no" class="form-control"
                                                        value="{{ $customer->cdc_no }}" disabled>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="address">Company</label>
                                                    <input type="text" name="company" class="form-control"
                                                        value="{{ $customer->company }}" disabled>
                                                </div>



                                                <div class="form-group col-md-6">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>
                                                    <select name="branch" id="branch" class="form-control"
                                                        disabled>
                                                        <option selected disabled>Choose One</option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}"
                                                                @if ($customer->branch == $branch->id) selected @endif>
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="address">Address </label>
                                                <textarea name="address" id="" cols="30" rows="5" class="form-control" disabled>{{ $customer->address }}</textarea>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <a href="{{ url('customer ') }}"><button type="button"
                                                        class="btn btn-dark">Back</button></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- Modal End --}}
                </section>


            </section>

        </div>



    </div>






    @include('layouts.footer')
