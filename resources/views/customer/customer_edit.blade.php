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
                @foreach ($errors->all() as $error)
                    {{-- <li>{{ $error }}</li> --}}
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ $error }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Customer Edit</h1>
                            </div>

                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Customer Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>


                <div class="container-fluid mt-5">
                    <div class="row  justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card container">
                                <div class="card-header">
                                    <h3 class="card-title " style="font-weight: bold;">Patient Edit</h3>
                                </div>
                                {{-- <hr> --}}
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body container">
                                    <form action="{{ url('customer_update', $showCustomer->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="name"
                                                            required autofocus name="name"
                                                            value="{{ $showCustomer->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phno">Phone Number <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="phone number"
                                                            name="phno" value="{{ $showCustomer->phno }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="address">Date Of Birth </label>
                                                        <input type="date" class="form-control" id="address"
                                                            placeholder="Enter Date of Birth" name="dob"
                                                            value="{{ $showCustomer->dob }}">
                                                    </div>
                                                </div>
                                                @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="branch">Location<span
                                                                    class="text-danger">*</span></label>
                                                            <select name="branch" id="branch" class="form-control"
                                                                required>
                                                                <option selected disabled>Select Location</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}"
                                                                        {{ $branch->id == $showCustomer->branch ? 'selected' : '' }}>
                                                                        {{ $branch->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- <div class="form-group col-md-6">
                                                        <label for="address">File5 <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" name="file5">
                                                        <span class="text-danger">{{ $showCustomer->file5 }}</span>

                                                    </div> --}}
                                                    <div class="form-group" style="display: none;">
                                                        <label for="branch">Location<span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="branch"
                                                            id="branch" value="{{ auth()->user()->level }}">
                                                    </div>
                                                @endif





                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="address">Address</label>
                                                        <textarea class="form-control" name="address" id="address" cols="10" rows="5"
                                                            placeholder="Enter Address">{{ $showCustomer->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-end">
                                                <button type="submit" class="btn btn-primary">Update </button>
                                            </div>
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
