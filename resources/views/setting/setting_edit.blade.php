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

                @if (Auth::user()->type === '1')
                    <li class="nav-item ml-auto">
                        <a class="nav-link" href="#">
                            @if (Auth::user()->branch_id)
                                {{ Auth::user()->branch->branch_name }}
                            @else
                                Admin
                            @endif

                        </a>
                    </li>
                @else
                    <li class="nav-item ml-auto">
                        <a class="nav-link" href="#">
                            @if (Auth::user()->branch_id)
                                {{ Auth::user()->branch->branch_name }}
                            @else
                                N/A
                            @endif
                        </a>
                    </li>
                @endif
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
            <!-- Main content -->
            <section class="content">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Setting Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Setting Edit
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
                                    <h3 class="card-title  " style="font-weight: bold;">Setting Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <form action="{{ url('setting_update', $setting->id) }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label for="name">Category Name <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="category">
                                                        <option value="" selected disabled>Choose Category
                                                        </option>
                                                        <option value="Invoice"
                                                            {{ $setting->category == 'Invoice' ? 'selected' : '' }}>
                                                            Invoice</option>
                                                        <option value="Sale Return (Invoice)"
                                                            {{ $setting->category == 'Sale Return (Invoice)' ? 'selected' : '' }}>
                                                            Sale
                                                            Return (Invoice)
                                                        </option>

                                                        <option value="Sale Account (Invoice Return)"
                                                            {{ $setting->category == 'Sale Account (Invoice Return)' ? 'selected' : '' }}>
                                                            Sale Account
                                                            (Invoice Return)
                                                        </option>
                                                        <option value="Receivable (Invoice Return)"
                                                            {{ $setting->category == 'Receivable (Invoice Return)' ? 'selected' : '' }}>
                                                            Receivable
                                                            (Invoice Return)
                                                        </option>
                                                        <option value="Sale Account (Invoice)"
                                                            {{ $setting->category == 'Sale Account (Invoice)' ? 'selected' : '' }}>
                                                            Sale
                                                            Account
                                                            (Invoice)
                                                        </option>
                                                        <option value="Receivable (Invoice)"
                                                            {{ $setting->category == 'Receivable (Invoice)' ? 'selected' : '' }}>
                                                            Receivable (Invoice)
                                                        </option>

                                                        <option value="Purchase Order"
                                                            {{ $setting->category == 'Purchase Order' ? 'selected' : '' }}>
                                                            Purchase Order
                                                        </option>
                                                        {{-- <option value="Po Return"
                                                            {{ $setting->category == 'Po Return' ? 'selected' : '' }}>
                                                            Po Return
                                                        </option> --}}
                                                        <option value="Buy Account (Purchase Order)"
                                                            {{ $setting->category == 'Buy Account (Purchase Order)' ? 'selected' : '' }}>
                                                            Purchase Account
                                                            (Purchase Order)
                                                        </option>
                                                        <option value="Payable (Purchase Order)"
                                                            {{ $setting->category == 'Payable (Purchase Order)' ? 'selected' : '' }}>
                                                            Payable (Purchase
                                                            Order)</option>

                                                        <option value="Expense"
                                                            {{ $setting->category == 'Expense' ? 'selected' : '' }}>
                                                            Expense
                                                        </option>

                                                        {{-- <option value="Other Income"
                                                            {{ $setting->category == 'Other Income' ? 'selected' : '' }}>
                                                            Other Income
                                                        </option> --}}

                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="phno">Location <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="location" id="location">
                                                        <option value="" selected disabled>Choose Location
                                                        </option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}"
                                                                @if ($setting->branch_id == $branch->id) selected @endif>
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="phno">Transaction Name <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="transaction_id" id="transaction">
                                                        <option value="" selected disabled>Choose Choose
                                                        </option>
                                                        @foreach ($transactions as $transaction)
                                                            @if ($setting->transaction_id == $transaction->id && $transaction->location == $setting->branch_id)
                                                                <option value="{{ $transaction->id }}" selected>
                                                                    {{ $transaction->transaction_name }}
                                                                </option>
                                                            @endif
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


    @include('layouts.footer')
    <script>
        $(document).ready(function() {
            $('#location').on('change', function() {
                var locationId = $(this).val();
                $('#transaction').html('<option value="">Loading...</option>');

                if (locationId) {
                    $.ajax({

                        url: '{{ route('get-transactions') }}',
                        type: 'GET',
                        data: {
                            locationId: locationId,
                        },
                        success: function(data) {
                            $('#transaction').empty().append(
                                '<option value="">Select Transaction</option>');

                            if (data && data.length > 0) {
                                $.each(data, function(index, transaction) {
                                    $('#transaction').append('<option value="' +
                                        transaction
                                        .id + '">' + transaction.transaction_name +
                                        '</option>');
                                });
                            } else {
                                $('#transaction').append(
                                    '<option value="">No Transactions available</option>');
                            }
                        },
                        error: function() {
                            $('#transaction').empty().append(
                                '<option value="">Error loading transactions</option>');
                        }
                    });
                } else {
                    $('#transaction').empty().append('<option value="">Select Transaction</option>');
                }
            });
        });
    </script>
