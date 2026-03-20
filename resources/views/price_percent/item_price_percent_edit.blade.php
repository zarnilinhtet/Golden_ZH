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
                                <h1>Price Percent Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Price Percent Edit
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>


                <div class="container-fluid mt-3">
                    <div class="row  justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-8 mx-auto">
                            <!-- general form elements -->
                            <div class="card" style="background-color:  #007bff26">
                                <div class="card-header">
                                    <h3 class="card-title  " style="font-weight: bold;">Price Percent Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <form action="{{ url('price_update', $price->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="name">Percent <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="percent"
                                                    placeholder="Enter Price Percent" required autofocus name="percent"
                                                    value="{{ $price->percent }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="crc">Location <span
                                                        class="text-danger">*</span></label>
                                                <select name="branch" id="" class="form-control" required>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            @if ($price->branch == $branch->id) selected @endif>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
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

    <script>
        document.getElementById("percent").addEventListener("input", function() {
            if (this.value > 100) {
                alert("Percentage cannot be more than 100!");
                this.value = 100; // Reset to 100 if exceeded
            }
        });
    </script>
    @include('layouts.footer')
