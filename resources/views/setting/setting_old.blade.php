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

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">



                            <section class="content-header">
                                <div class="container-fluid">
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1>Settings</h1>
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ url('/dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Settings</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </section>




                            <div class="mt-2 col-md-6 mx-auto">
                                <div class="card text-white" style="background-color: #251287">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-3">
                                            <h5 class="col-md-5">Invoice :</h5>
                                            @if ($invoice && $invoice->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $invoice->transaction->transaction_name ?? null }}
                                                </h5>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#customerinvoice">
                                                    Edit
                                                </button>
                                                <div class="modal fade" id="customerinvoice">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" style="color: black;">Invoice
                                                                    Edit</h4>

                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ url('invoice_setting/edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id" id=""
                                                                        class="form-control">

                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}"
                                                                                @if ($invoice->transaction_id == $transaction->id) selected @endif>
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ url('invoice_setting') }}" class="col-md-7">
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" id=""
                                                                class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction
                                                                </option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>





                            </div>
                            <div class="mt-2 col-md-6 mx-auto">
                                <div class="card text-white" style="background-color: #251287">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-1">
                                            <h5 class="col-md-5">POS :</h5>
                                            @if ($pos && $pos->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $pos->transaction->transaction_name ?? null }}
                                                </h5>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#customerinvoice">
                                                    Edit
                                                </button>
                                                <div class="modal fade" id="customerinvoice">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" style="color: black;">POS
                                                                    Edit</h4>

                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ url('pos_setting/edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id" id=""
                                                                        class="form-control">

                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}"
                                                                                @if ($pos->transaction_id == $transaction->id) selected @endif>
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ url('pos_setting') }}" class="col-md-7">
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" id=""
                                                                class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction
                                                                </option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>





                            </div>
                            <div class="mt-2 col-md-6 mx-auto">
                                <div class="card text-white" style="background-color: #251287">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-1">
                                            <h5 class="col-md-5">Sale Return:</h5>
                                            @if ($invoice_return && $invoice_return->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $invoice_return->transaction->transaction_name ?? null }}
                                                </h5>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#invoice_return">
                                                    Edit
                                                </button>
                                                <div class="modal fade" id="invoice_return">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" style="color: black;">Sale
                                                                    Return
                                                                    Edit</h4>

                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ url('invoice_return_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id" id=""
                                                                        class="form-control">

                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}"
                                                                                @if ($invoice_return->transaction_id == $transaction->id) selected @endif>
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ url('invoice_return_setting') }}" class="col-md-7">
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" id=""
                                                                class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction
                                                                </option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>





                            </div>
                            <div class="mt-2 col-md-6 mx-auto">
                                <div class="card text-white" style="background-color: #251287">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-1">
                                            <h5 class="col-md-5">PO:</h5>
                                            @if ($exchange_order && $exchange_order->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $exchange_order->transaction->transaction_name ?? null }}
                                                </h5>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#eo">
                                                    Edit
                                                </button>
                                                <div class="modal fade" id="eo">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" style="color: black;">Purchase
                                                                    Order
                                                                    Edit</h4>

                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ url('exchange_order_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id" id=""
                                                                        class="form-control">

                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}"
                                                                                @if ($exchange_order->transaction_id == $transaction->id) selected @endif>
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ url('exchange_order_setting') }}" class="col-md-7">
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" id=""
                                                                class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction
                                                                </option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>





                            </div>
                            <div class="mt-2 col-md-6 mx-auto">
                                <div class="card text-white" style="background-color: #251287">
                                    <div class="card-body">
                                        <div class="row col-md-12 mt-1">
                                            <h5 class="col-md-5">PO Return:</h5>
                                            @if ($eo_return && $eo_return->transaction_id != null)
                                                <h5 class="col-md-5">
                                                    {{ $eo_return->transaction->transaction_name ?? null }}
                                                </h5>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#eo_return">
                                                    Edit
                                                </button>
                                                <div class="modal fade" id="eo_return">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" style="color: black;">Purchase
                                                                    Order Return
                                                                    Edit</h4>

                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ url('exchange_order_return_setting_edit') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <select name="transaction_id" id=""
                                                                        class="form-control">

                                                                        @foreach ($transactions as $transaction)
                                                                            <option value="{{ $transaction->id }}"
                                                                                @if ($eo_return->transaction_id == $transaction->id) selected @endif>
                                                                                {{ $transaction->transaction_name ?? null }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <form action="{{ url('exchange_order_return_setting') }}"
                                                    class="col-md-7">
                                                    <div class="row col-md-12">
                                                        <div class="col-md-9">
                                                            <select name="transaction_id" id=""
                                                                class="form-control">
                                                                <option value="" selected disabled>Choose
                                                                    Transaction
                                                                </option>
                                                                @foreach ($transactions as $transaction)
                                                                    <option value="{{ $transaction->id }}">
                                                                        {{ $transaction->transaction_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit"
                                                                class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>





                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    <script src="{{ asset('backend/js/jquery-3.6.0.js') }}"></script>
    @include('layouts.footer')
