@include('layouts.header')
<style>
    .class-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #2A7774;
        transition: transform 0.2s ease-in-out;
    }

    .class-card:hover {
        transform: scale(1.05);
    }

    .class-title {
        font-size: 20px;
        font-weight: bold;
    }

    .class-info {
        font-size: 14px;
        color: #6c757d;
    }

    .class-actions {
        margin-top: 15px;
    }
</style>
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

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1>Sale Person Per Month</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">Sale Person Per Month
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif



                <div class="ml-2 ">


                     <div class="container">


                        <p class="text-center"><strong>Select a Sale Person to view more details.</strong></p>


                    <div class="row g-4 mx-3">

                    {{-- @foreach ($invoices as $invoice)
    @php
        $salePersons = explode(',', $invoice->sale_person);
    @endphp

    @foreach ($salePersons as $id)
        @php
            $salePerson = \App\Models\SalePerson::find($id);
        @endphp
        @if ($salePerson)
           @if(auth()->user()->is_admin == '1' )
             <div class="col-6 col-sm-4 col-md-4 col-lg-4 my-2">
                <a href="{{ url('show_way_call', [$year, $month, $id]) }}">
                    <div class="class-card p-3 shadow-sm">
                        <div class="class-title text-center text-white">{{ $salePerson->name }}</div>
                    </div>
                </a>
            </div>
            @else
                @if ($salePerson->id == auth()->user()->sale_person_id )
                     <div class="col-6 col-sm-4 col-md-4 col-lg-4 my-2">
                <a href="{{ url('show_way_call', [$year, $month, $id]) }}">
                    <div class="class-card p-3 shadow-sm">
                        <div class="class-title text-center text-white">{{ $salePerson->name }}</div>
                    </div>
                </a>
            </div>
                @endif
            @endif
        @endif
    @endforeach
@endforeach --}}
@php
    $allSalePersonIds = [];

    // Collect all sale person IDs from invoices
    foreach ($invoices as $invoice) {
        $ids = explode(',', $invoice->sale_person);
        $allSalePersonIds = array_merge($allSalePersonIds, $ids);
    }

    // Remove duplicates and empty values
    $uniqueSalePersonIds = array_unique(array_filter($allSalePersonIds));
@endphp

@foreach ($uniqueSalePersonIds as $id)
    @php
        $salePerson = \App\Models\SalePerson::find($id);
    @endphp

    @if ($salePerson)
        @if (auth()->user()->is_admin == '1' )
            <div class="col-6 col-sm-4 col-md-4 col-lg-4 my-2">
                <a href="{{ url('show_way_call', [$year, $month, $id]) }}">
                    <div class="class-card p-3 shadow-sm">
                        <div class="class-title text-center text-white">{{ $salePerson->name }}</div>
                    </div>
                </a>
            </div>
            @else
                @if ($salePerson->id == auth()->user()->sale_person_id )
                     <div class="col-6 col-sm-4 col-md-4 col-lg-4 my-2">
                <a href="{{ url('show_way_call', [$year, $month, $id]) }}">
                    <div class="class-card p-3 shadow-sm">
                        <div class="class-title text-center text-white">{{ $salePerson->name }}</div>
                    </div>
                </a>
            </div>
                @endif
            @endif
        @endif
   
@endforeach


                    </div>
                </div>




        </div>

        </section>

    </div>



    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js ') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 30,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>
