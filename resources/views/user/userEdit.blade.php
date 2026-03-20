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
                                <h1>User</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">User
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>


                <div class="container-fluid">
                    <div class="row  justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-8">
                            <!-- general form elements -->
                            <div class="card " >
                                <div class="card-header">
                                    <h3 class="card-title">User Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="{{ url('update_user', $userShow->id) }}" method="POST">
                                    @csrf
                                    <div class="card-body">
 <div class="form-group  col-md-12">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_sale_person"
                                                        name="type" value="2"
                                                        @if($userShow->type==2) checked @endif>
                                                    <label class="custom-control-label" for="is_sale_person">Is
                                                        Sale Person</label>
                                                </div>
                                        <div class="form-group mt-2">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Name"  value="{{ $userShow->name }}">
                                                 <select name="sale_person_id" class="form-control d-none" id="saleperson_id">
                                                        <option value="" >Select Name</option>
                                                        @foreach ($salepersons as $person)
                                                            <option value="{{ $person->id }}" @if($person->id == $userShow->sale_person_id) selected @endif>{{ $person->name }}
                                                            </option>
                                                        @endforeach
                                                        </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Enter email" required value="{{ $userShow->email }}">
                                        </div>

                                        {{-- <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="form-control" name="type" id="type">
                                                <option value="{{ $userShow->type }}" selected>{{ $userShow->type }}
                                                </option>
                                                <option value="Admin">Admin</option>
                                                <option value="Warehouse">Warehouse</option>
                                                <option value="Shop">Shop</option>
                                                <option value="Cashier">Cashier</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="level">Location</label>
                                            <select class="form-control" name="level" id="level">
                                                <option value="{{ $userShow->level }}" selected>
                                                    {{ $userShow->warehouse->name ?? 'Default' }}
                                                </option>

                                                @foreach ($branchs as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" placeholder="New Password"
                                                autocomplete="new-password">
                                        </div>

                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
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
<script>
    $(document).ready(function() {
        $("#is_sale_person").change(function() {
            if ($(this).is(":checked")) {
                $("#saleperson_id").removeClass("d-none");
                $("#name").addClass("d-none");
            } else {
                $("#saleperson_id").addClass("d-none");
                $("#name").removeClass("d-none");
            }
        });


       
       $("#saleperson_id").change(function () {
    // Get the selected option's text (sale person name)
    var selectedName = $(this).find("option:selected").text();

    // If a valid name is selected, assign it to the #name input
    if (selectedName !== "Select Name") {
        $("#name").val(selectedName);
    } else {
        $("#name").val(""); // Clear if "Select Name" is chosen
    }
});

    });

</script>
