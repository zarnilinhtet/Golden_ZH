@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link  text-gray" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
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
                    <button type="button" class="btn dropdown-toggle text-gray" data-toggle="dropdown" aria-haspopup="true"
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
                                <h1>Sale Person Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Sale Person Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>


                <div class="container-fluid mt-3">
                    <div class="row  justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-8">
                            <!-- general form elements -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title  " style="font-weight: bold;">Sale Person Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <form action="{{ url('sale_person_update', $sale_person->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" required
                                                    autofocus name="name" value="{{ $sale_person->name }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="phno">Phone Number</label>
                                                <input type="text" class="form-control" id="phone number"
                                                    name="phno" value="{{ $sale_person->phno }}">
                                            </div>
 <div class="form-group">
                                                <label for="unit">Principal</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Principal" autofocus name="principal" value="{{ $sale_person->principal }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Position</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Position" autofocus name="position" value="{{ $sale_person->position }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="phone number"
                                                    name="address" value="{{ $sale_person->address }}">
                                            </div>
                                            @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')

                                            <div class="form-group ">
                                                <label for="branch">Location<span
                                                        class="text-danger">*</span></label>
                                                <select name="location" id="branch" class="form-control"
                                                    required>

                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}" @if($branch->id == $sale_person->location) selected @endif>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="form-group col-md-6" style="display: none;">
                                                <label for="branch">Location<span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="location"
                                                    id="branch" value="{{ auth()->user()->level }}"
                                                    required>
                                            </div>
                                        @endif

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
