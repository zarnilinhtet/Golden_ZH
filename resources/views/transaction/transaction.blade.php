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
            {{-- <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>DataTables</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">DataTables</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section> --}}

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">


                            <!-- Content Header (Page header) -->
                            <section class="content-header">
                                <div class="container-fluid">
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1>Transactions</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Transaction</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div><!-- /.container-fluid -->
                            </section>

                            @php
                                $userPermissions = [];
                                if (auth()->user()->permission) {
                                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $userPermissions = $decodedPermissions;
                                    }
                                }
                            @endphp



                            @if (in_array('Transaction Register', $userPermissions) || auth()->user()->is_admin == '1')
                                <div class="container-fluid mb-4 mr-auto">
                                    <div class="row">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn btn-default text-white"
                                                data-toggle="modal" data-target="#modal-lg"
                                                style="background-color: #007BFF">
                                                Transaction Register
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Modal Content --}}
                            <div class="modal fade" id="modal-lg">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Transaction Register</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/transaction_register') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="phno">Location <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="location" required
                                                        id="location">
                                                        <option value="" selected disabled>Choose Location
                                                        </option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="accounts_id">Account Name <span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <select name="account_id" class="form-control" id="account_id"
                                                        required>
                                                        <option value="">Select Account</option>

                                                        {{-- @foreach ($account as $accounts)
                                                            <option value="{{ $accounts->id }}">
                                                                {{ $accounts->account_name }}
                                                            </option>
                                                        @endforeach --}}
                                                    </select>
                                                    @error('account_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group" style="display: none">
                                                    <label for="status">Status<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <select name="status" class="form-control" id="status" required>
                                                        {{-- <option value="">Choose One</option> --}}

                                                        <option value="in">IN

                                                        </option>
                                                        <option value="out">OUT

                                                        </option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group" style="display: none;">
                                                    <label for="transaction_code">Opening Amount<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <input type="text" class="form-control" id="transaction_code"
                                                        name="transaction_code" value="0" required>
                                                    @error('transaction_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="transaction_name">Name<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <input type="text" class="form-control" id="transaction_name"
                                                        name="transaction_name" placeholder="Enter Transaction Name"
                                                        required value="">
                                                    @error('transaction_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label>Descriptions</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter ..." style="border-color:#6B7280"
                                                        name="description" value="no need"></textarea>
                                                </div>

                                                <!-- /.card-body -->
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="background-color: #007BFF">Register</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>


                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('deleteStatus'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('deleteStatus') }}
                                </div>
                            @endif
                            @if (session('updateStatus'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('updateStatus') }}
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">Transactions</h3>
                                    <div class="dropdown ml-auto mr-5">
                                        <!-- Dropdown Menu HTML -->
                                        @if (auth()->user()->is_admin == '1' || Auth::user()->type == 'Admin')
                                            <div id="branchDropdown" class="dropdown ml-auto"
                                                style="display:inline-block; margin-left: 10px;">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    {{ $currentBranchName }}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a href="{{ url('transaction') }}" class="dropdown-item">All
                                                        Accounts</a>
                                                    @foreach ($branch_drop as $drop)
                                                        <a class="dropdown-item"
                                                            href="{{ route('transactions', $drop->id) }}">{{ $drop->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                                <!-- /.card-header -->
                                {{-- <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Account Name</th>
                                                <th rowspan="2">Location</th>
                                                <th rowspan="2">Name</th>
                                                <th colspan="2">Amount</th>
                                                <th rowspan="2">Total Amount</th>

                                                <th rowspan="2">Action</th>
                                            </tr>
                                            <tr>
                                                <th>In</th>
                                                <th>Out</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $transaction->account->account_name }}</td>
                                                    <td>{{ $transaction->warehouse->name ?? '' }}</td>



                                                    <td>{{ $transaction->transaction_name }}</td>

                                                    <td class="text-center">


                                                        <span class="text-success" style="font-weight: bold">
                                                            {{ number_format($invoiceAmountsByAccount[$transaction->transaction_name] ?? 0) }}
                                                        </span>

                                                    </td>
                                                    <td class="text-center">


                                                        <span class="text-success" style="font-weight: bold">
                                                            {{ number_format($refundAmountsByAccount[$transaction->transaction_name] ?? 0) }}
                                                        </span>

                                                    </td>
                                                    <td class="text-center"> <span class="text-success"
                                                            style="font-weight: bold">
                                                            {{ number_format(($invoiceAmountsByAccount[$transaction->transaction_name] ?? 0) - ($refundAmountsByAccount[$transaction->transaction_name] ?? 0)) }}
                                                        </span></td>

                                                    <td>
                                                        @if (in_array('Transaction Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('transaction_show', $transaction->id) }}"
                                                                class="btn btn-success"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @endif

                                                        @if (in_array('Transaction Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('transaction_delete', $transaction->id) }}"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this transaction ?')"><i
                                                                    class="fa-solid fa-trash"></i>
                                                        @endif

                                                        @if (in_array('Add Payment', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('payment', $transaction->id) }}"
                                                                class="btn btn-warning ml-2">Add Payment</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                        </tbody>




                                    </table>
                                </div> --}}


                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Account Name</th>
                                                {{-- <th>Status</th> --}}


                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Amount</th>

                                                <th>Action</th>
                                            </tr>
                                        </thead>


                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $transaction->account ? $transaction->account->account_name : 'N/A' }}
                                                    </td>
                                                    <td>{{ $transaction->transaction_name }}</td>
                                                    <td>{{ $transaction->warehouse->name ?? '' }}</td>
                                                    <td>
                                                        {{ number_format(abs(($invoice_payment_method[$transaction->id] ?? 0) + ($sumByIn[$transaction->id] ?? 0) + ($other_income[$transaction->id] ?? 0) - ($sumByOut[$transaction->id] ?? 0) + ($return_payment_method[$transaction->id] ?? 0) + ($po_payment_method[$transaction->id] ?? 0) + ($expense[$transaction->id] ?? 0))) }}
                                                    </td>
                                                    </td>

                                                    <td>

                                                        @if (in_array('Transaction Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('transaction_show', $transaction->id) }}"
                                                                class="btn btn-success"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @endif

                                                        @if (in_array('Transaction Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('transaction_delete', $transaction->id) }}"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this transaction ?')"><i
                                                                    class="fa-solid fa-trash"></i>
                                                        @endif

                                                        @if (in_array('Add Payment', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('payment', $transaction->id) }}"
                                                                class="btn btn-warning ml-2">Add Payment</a>
                                                        @endif

                                                    </td>


                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                        </tbody>


                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>

            </section>


        </div>



    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script>
    <script>
        $(document).ready(function() {
            $('#location').on('change', function() {
                var locationId = $(this).val();
                $('#account_id').html('<option value="">Loading...</option>');

                if (locationId) {
                    $.ajax({
                        url: '{{ route('get-accounts') }}',
                        type: 'GET',
                        data: {
                            locationId: locationId,
                        },

                        success: function(data) {
                            $('#account_id').empty().append(
                                '<option value="">Select Account</option>');

                            if (data && data.length > 0) {
                                $.each(data, function(index, account) {
                                    $('#account_id').append('<option value="' + account
                                        .id + '">' + account.account_name +
                                        '</option>');
                                });
                            } else {
                                $('#account_id').append(
                                    '<option value="">No accounts available</option>');
                            }
                        },
                        error: function() {
                            $('#account_id').empty().append(
                                '<option value="">Error loading accounts</option>');
                        }
                    });
                } else {
                    $('#account_id').empty().append('<option value="">Select Account</option>');
                }
            });
        });
    </script>

    @include('layouts.footer')
