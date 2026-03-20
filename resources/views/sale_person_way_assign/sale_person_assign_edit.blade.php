@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link  text-gray" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  text-gray" href="#">Date -
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
                    <button type="button" class="btn dropdown-toggle text-gray" data-toggle="dropdown"
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
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Sale Person Assign Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Sale Person Assign Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>


                <div class="container-fluid mt-5">
                    <div class="row  justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title  " style="font-weight: bold;">Sale Person Assign Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <form action="{{ url('sale_person_assign_update', $assign->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">

 <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="name">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Enter Name" required autofocus name="name" value="{{ $assign->name }}">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="name">Phone Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Enter Phone Number" required autofocus
                                                        name="phno" value="{{ $assign->phno }}">
                                                </div>
                                                 <div class="form-group col-md-12 ">
                                                <label for="name">Address<span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="address" id="" cols="30" rows="5">{{ $assign->address }}</textarea>
                                                </div>
                                             @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                                                <div class="form-group col-md-6">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>
                                                    <select name="branch" id="branch" class="form-control"
                                                        required>
                                                        <option selected disabled>Select Location</option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}" @if($branch->id == $assign->location) selected @endif>{{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <div class="form-group" style="display: none;">
                                                    <label for="branch">Location<span
                                                            class="text-danger">*</span></label>

                                                    <input class="form-control" type="text" name="branch"
                                                        id="branch" value="{{ auth()->user()->level }}" required>
                                                </div>
                                            @endif
                                             <div class="form-group col-md-6">
                                                    <label for="name">Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="date" name="date" value="{{ $assign->date }}">
                                                </div>


                                                {{-- <div class="form-group col-md-6">
                                                <label for="name">Sale Person</label><span
                                                    class="text-danger">*</span></label>
                                                <select name="sale_person" id="sale_person" class="form-control" required>
                                                    <option selected disabled>Select Sale Person</option>

                                                    @foreach ($sale_persons as $sale_person)
                                                        <option value="{{ $sale_person->id }}" @if($sale_person->id == $assign->sale_person) selected @endif>{{ $sale_person->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                </div> --}}
                                                @php
    $salePersonIds = explode(',', $assign->sale_person);
@endphp

@foreach ($salePersonIds as $index => $personId)
    <div class="form-group col-md-7" id="formContainer">
        <div class="form-group"><label for="sale_person_{{ $index }}">Sale Person <span class="text-danger">*</span></label>
        <select name="sale_person[]" id="sale_person_{{ $index }}" class="form-control" required>
            <option selected disabled>Select Sale Person</option>
            @foreach ($sale_persons as $sale_person)
                <option value="{{ $sale_person->id }}" @if ($sale_person->id == $personId) selected @endif>
                    {{ $sale_person->name }}
                </option>
            @endforeach
        </select></div>
    </div>
@endforeach<div class="col-md-5 col-sm-5" style="margin-top: 30px;">
                                                <button id="addRowBtn" type="button" class="btn btn-primary "
                                                    title="Add Location Row"><i class="fa-solid fa-plus"></i></button>

                                                <button id="removeRowBtn" type="button" title="Remove Location Row"
                                                    class="btn btn-danger "><i class="fa-solid fa-xmark"></i></button>
                                                </div>

                                                <div class="form-group col-md-6">
                                                <label for="name">Assign</label><span
                                                    class="text-danger">*</span></label>
                                                <select name="assign" id="assign" class="form-control" required>
                                                    <option selected disabled>Select Assign</option>

                                                    <option value="Way" @if($assign->assign == 'Way') selected @endif>Way
                                                    </option>
                                                    <option value="Phone Call" @if($assign->assign == 'Phone Call') selected @endif>Phone
                                                    </option>

                                                </select>
                                                </div>
                                                <div class="form-group col-md-6 d-none" id="call">
                                                <label for="name">Number of Calls</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Number of Calls"  name="call" value="{{ $assign->call }}">
                                                </div>
                                                 <div class="form-group col-md-12">
                                                <label for="phno">Description</label>
                                                <textarea name="description" id="description" cols="10" rows="3" class="form-control">{{ $assign->description }}</textarea>
                                            </div>
                                        </div>


                                        </div>
                                        <div class="modal-footer justify-content-end">

                                            <button type="submit" class="btn btn-primary">Update </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>


        </section>

    </div>



    </div>
  @include('layouts.footer')
<script>
        $(document).ready(function() {
            $('#branch').on('change', function() {


                const selectedBranch = $(this).val();
                const doctorSelect = document.getElementById('sale_person');

                doctorSelect.innerHTML = '<option value="">Select Sale Person</option>';

                // Filter and add the doctors based on the selected branch
                @foreach ($sale_persons as $sale)
                    if (selectedBranch === '{{ $sale->location}}') {
                        const option = document.createElement('option');
                        option.value = '{{ $sale->id }}';
                        option.textContent = '{{ $sale->name }}';
                        doctorSelect.appendChild(option);
                    }
                @endforeach
            });
            $("#location").trigger("change");



        });
    </script>
    <script>
        $(document).ready(function() {
            $('#assign').on('change', function() {


                const selectedBranch = $(this).val();
                if(selectedBranch == 'Way'){
                    $("#call").addClass('d-none');


                }else{
                    $("#call").removeClass('d-none');
                }
            });




        });
    </script>

<script>
        $(document).ready(function() {
            var rowCount = 1; // Initial row count

            $('#addRowBtn').on('click', function() {
                var $lastFormGroup = $('#formContainer .form-group:last');
                var $newFormGroup = $lastFormGroup.clone();

                rowCount++;
                $newFormGroup.find('select').val('');
                // $newFormGroup.find('label').hide();
                $('#formContainer').append($newFormGroup);
            });

            $('#removeRowBtn').on('click', function() {
                console.log($('#formContainer .form-group').length);
                if ($('#formContainer .form-group').length > 1) {
                    $('#formContainer .form-group:last').remove();
                }
            });
        });
    </script>
