@include('layouts.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav col-md-6">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Date -
                        <?= $currentDate = date('d-m-y') ?></a>
                </li>


            </ul>

            <!-- Right navbar links -->
            <ul class="ml-auto navbar-nav">


                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <div class="dropdown-menu ">
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


            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Transaction Payment Edit</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Transaction Payment Edit</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            @if (session('updateStatus'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('updateStatus') }}
                </div>
            @endif
            <!-- Main content -->
            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 offset-3 my-3 p-3">
                            <div class="card card-primary p-4">

                                <!-- /.card-header -->
                                <!-- form start -->

                                <form action="{{ url('payment_update', $show->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" value="" name="transaction_id">
                                    <input type="hidden" value="" name="account_id">

                                    <div class="form-group">
                                        <label for="status">Status <span style="color: red;">*</span></label>
                                        <select name="payment_status" class="form-control" id="status" required>
                                            <option value="{{ $show->payment_status }}">
                                                {{ ucfirst($show->payment_status) }}
                                            </option>
                                            <option value="in">IN</option>
                                            <option value="out">OUT</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="transaction_code">Amount <span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" id="transaction_code" name="amount"
                                            placeholder="Enter Amount" required value="{{ $show->amount }}">
                                        @error('transaction_code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="note" rows="3" placeholder="Enter ...">{{ $show->note }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="modal-footer justify-content-end">

                                        <button type="submit" class="btn btn-primary"
                                            style="background-color: #007BFF">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>



    </div>


    @include('layouts.footer')
