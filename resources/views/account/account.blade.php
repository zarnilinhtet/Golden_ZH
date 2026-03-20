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
                                            <h1>Accounts</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Accounts</li>
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

                            @if (in_array('Account Register', $userPermissions) || auth()->user()->is_admin == '1')
                                <div class="container-fluid mb-4 mr-auto">
                                    <div class="row">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn btn-default text-white"
                                                data-toggle="modal" data-target="#modal-lg"
                                                style="background-color: #007BFF">
                                                Accounts Register
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
                                            <h4 class="modal-title">Account Register</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/accounts_register') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="account_code">Code<span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <input type="text" class="form-control" id="account_code"
                                                        name="account_code" placeholder="Enter Account Code" required>
                                                    @error('account_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="account_name">Name <span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <input type="text" class="form-control" id="account_name"
                                                        name="account_name" placeholder="Enter Account Name" required>
                                                    @error('account_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="phno">Location <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="location" required>
                                                        <option value="" selected disabled>Choose Location
                                                        </option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="form-group">
                                                    <label for="account_name">Type <span
                                                            style="color: red;">&nbsp;*</span></label>
                                                    <select name="account_type" id="type" class="form-control">
                                                        <option value="" selected disabled>Select Account Type
                                                        </option>
                                                        <option value="Non Current Assets">Non Current Assets (BL)
                                                        </option>
                                                        <option value="Current Assets">Current Assets (BL)</option>
                                                        <option value="Long Term Liability">Long Term Liability (BL)
                                                        </option>
                                                        <option value="Current Liability">Current Liability (BL)
                                                        </option>
                                                        <option value="Non Current Liability">Non Current Liability
                                                            (BL)
                                                        </option>
                                                        <option value="Equity">Equity (BL)</option>
                                                        <option value="Revenue">Revenue (PL)</option>
                                                        <option value="Cost of Sale">Cost of Sale (PL)</option>

                                                        <option value="Other income">Other income (PL)</option>
                                                        <option value="Admin Expenses">Admin Expenses (PL)</option>
                                                        </option>
                                                        <option value="Depreciation">Depreciation (PL)
                                                        </option>
                                                        <option value="Mainteance Expenses">Mainteance Expenses (PL)
                                                        </option>
                                                        <option value="S & D Expenses">S & D Expenses (PL)
                                                        </option>
                                                        <option value="Other Expenses">Other Expenses (PL)
                                                        </option>
                                                        <option value="Marketing Expenses">Marketing Expenses (PL)
                                                        </option>
                                                        <option value="Finance Expenses">Finance Expenses (PL)
                                                        </option>
                                                        <option value="Other Income">Other Income (PL)
                                                        </option>
                                                    </select>
                                                    <input type="hidden" name="account_bl_pl" id="bl_pl">
                                                    @error('type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
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
                                </div>
                            </div>


                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
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
                                    <h3 class="card-title">Accounts</h3>
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
                                                    <a href="{{ url('account') }}" class="dropdown-item">All
                                                        Accounts</a>
                                                    @foreach ($branch_drop as $drop)
                                                        <a class="dropdown-item"
                                                            href="{{ route('accounts', $drop->id) }}">{{ $drop->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>BL/PL</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = '1';
                                            @endphp
                                            @foreach ($accountList as $accounts)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $accounts->account_code }}</td>
                                                    <td>{{ $accounts->account_name }}</td>
                                                    <td>{{ $accounts->account_type }}</td>
                                                    <td>{{ $accounts->account_bl_pl }}</td>
                                                    <td>{{ $accounts->warehouse->name ?? '' }}</td>
                                                    <td>

                                                        @if (in_array('Account Edit', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('accounts_show', $accounts->id) }}"
                                                                class="btn btn-success"><i
                                                                    class="fa-solid fa-pen-to-square"></i></a>
                                                        @endif


                                                        @if (in_array('Account Delete', $userPermissions) || auth()->user()->is_admin == '1')
                                                            <a href="{{ url('accounts_delete', $accounts->id) }}"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this?')"><i
                                                                    class="fa-solid fa-trash"></i>
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
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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
