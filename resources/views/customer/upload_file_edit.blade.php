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
                                <h1>Patients File Edit</h1>
                            </div>

                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Patient's File Edit
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
                                    <h3 class="card-title  " style="font-weight: bold;">Patient's File Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->

                                <div class="card-body">
                                    <form action="{{ url('file_update', $customer->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">


                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="address">File1 <span
                                                            class="text-danger">*</span></label>

                                                    <input type="file" class="form-control " id="name" autofocus
                                                        name="file1" value="">
                                                    <span class="text-danger">{{ $customer->file1 }}</span>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="address">File2 <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" name="file2">
                                                    <span class="text-danger">{{ $customer->file2 }}</span>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="address">File3 <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" name="file3">
                                                    <span class="text-danger">{{ $customer->file3 }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="address">File4 <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" name="file4">
                                                    <span class="text-danger">{{ $customer->file4 }}</span>

                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="address">File5 <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" name="file5">
                                                    <span class="text-danger">{{ $customer->file5 }}</span>

                                                </div>
                                                <div class="form-group col-md-6"><label for="address">Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="date"
                                                        value="{{ $customer->date }}">
                                                </div>
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
