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
                            <h1>Accounts Edit</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('/account') }}">Accounts</a></li>
                                <li class="breadcrumb-item active">Accounts Edit</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 offset-3 my-3">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Accounts Update</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="{{ url('accounts_update', $accounts->id) }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="account_code">Code<span
                                                    style="color: red;">&nbsp;*</span></label>
                                            <input type="text" class="form-control" id="account_code"
                                                name="account_code" value="{{ $accounts->account_code }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_name">Name<span
                                                    style="color: red;">&nbsp;*</span></label>
                                            <input type="text" class="form-control" id="account_name"
                                                name="account_name" value="{{ $accounts->account_name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phno">Location <span class="text-danger">*</span></label>
                                            <select class="form-control" name="location" required>
                                                <option value="" selected disabled>Choose Location
                                                </option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if ($branch->id == $accounts->location) selected @endif>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_name">Type <span
                                                    style="color: red;">&nbsp;*</span></label>
                                            <select name="account_type" id="type" class="form-control">
                                                <option value="" disabled
                                                    {{ empty($accounts->account_type) ? 'selected' : '' }}>Select
                                                    Account Type</option>
                                                <option value="Non Current Assets"
                                                    {{ $accounts->account_type == 'Non Current Assets' ? 'selected' : '' }}>
                                                    Non Current Assets (BL)</option>
                                                <option value="Current Assets"
                                                    {{ $accounts->account_type == 'Current Assets' ? 'selected' : '' }}>
                                                    Current Assets (BL)</option>
                                                <option value="Long Term Liability"
                                                    {{ $accounts->account_type == 'Long Term Liability' ? 'selected' : '' }}>
                                                    Long Term Liability (BL)</option>
                                                <option value="Current Liability"
                                                    {{ $accounts->account_type == 'Current Liability' ? 'selected' : '' }}>
                                                    Current Liability (BL)</option>
                                                <option value="Non Current Liability"
                                                    {{ $accounts->account_type == 'Non Current Liability' ? 'selected' : '' }}>
                                                    Non Current Liability (BL)</option>
                                                <option value="Equity"
                                                    {{ $accounts->account_type == 'Equity' ? 'selected' : '' }}>
                                                    Equity (BL)</option>
                                                <option value="Revenue"
                                                    {{ $accounts->account_type == 'Revenue' ? 'selected' : '' }}>
                                                    Revenue (PL)</option>
                                                <option value="Cost of Sale"
                                                    {{ $accounts->account_type == 'Cost of Sale' ? 'selected' : '' }}>
                                                    Cost of Sale (PL)</option>
                                                <option value="Other income"
                                                    {{ $accounts->account_type == 'Other income' ? 'selected' : '' }}>
                                                    Other income (PL)</option>
                                                <option value="Admin Expenses"
                                                    {{ $accounts->account_type == 'Admin Expenses' ? 'selected' : '' }}>
                                                    Admin Expenses (PL)</option>
                                                <option value="Depreciation"
                                                    {{ $accounts->account_type == 'Depreciation' ? 'selected' : '' }}>
                                                    Depreciation (PL)</option>
                                                <option value="Mainteance Expenses"
                                                    {{ $accounts->account_type == 'Mainteance Expenses' ? 'selected' : '' }}>
                                                    Mainteance Expenses (PL)</option>
                                                <option value="S & D Expenses"
                                                    {{ $accounts->account_type == 'S & D Expenses' ? 'selected' : '' }}>
                                                    S & D Expenses (PL)</option>
                                                <option value="Other Expenses"
                                                    {{ $accounts->account_type == 'Other Expenses' ? 'selected' : '' }}>
                                                    Other Expenses (PL)</option>
                                                <option value="Marketing Expenses"
                                                    {{ $accounts->account_type == 'Marketing Expenses' ? 'selected' : '' }}>
                                                    Marketing Expenses (PL)</option>
                                                <option value="Finance Expenses"
                                                    {{ $accounts->account_type == 'Finance Expenses' ? 'selected' : '' }}>
                                                    Finance Expenses (PL)</option>
                                                <option value="Other Income"
                                                    {{ $accounts->account_type == 'Other Income' ? 'selected' : '' }}>
                                                    Other Income (PL)</option>
                                            </select>
                                            <input type="hidden" name="account_bl_pl" id="bl_pl"
                                                value="{{ $accounts->account_bl_pl }}">
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="modal-footer justify-content-between">

                                            <button type="submit" class="btn btn-primary ml-auto"
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
    </div>

    @include('layouts.footer')
    <script>
        document.getElementById('type').addEventListener('change', function() {
            var selectedValue = this.value;
            var hiddenInput = document.getElementById('bl_pl');

            var blCategories = [
                'Non Current Assets',
                'Current Assets',
                'Long Term Liability',
                'Current Liability',
                'Non Current Liability',
                'Equity'
            ];

            var plCategories = [
                'Revenue',
                'Cost of Sale',
                'Other income',
                'Admin Expenses',
                'Depreciation',
                'Mainteance Expenses',
                'S & D Expenses',
                'Other Expenses',
                'Marketing Expenses',
                'Finance Expenses',
                'Other Income'
            ];

            if (blCategories.includes(selectedValue)) {
                hiddenInput.value = 'BL';
            } else if (plCategories.includes(selectedValue)) {
                hiddenInput.value = 'PL';
            } else {
                hiddenInput.value = '';
            }
        });
    </script>
