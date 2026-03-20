@include('layouts.header')
<style>
    .img-thumbnail {
        border: 2px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
</style>

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

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="mb-2 row">
                            <div class="col-sm-6">
                                <h1> Configuration Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"> Configuration Edit
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

                <div class="mt-3 container-fluid">
                    <div class="row justify-content-center d-flex">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="card" style="background-color:  #007bff26">
                                <div class="card-header">
                                    <h3 class="card-title " style="font-weight: bold;">Profile Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <form action="{{ url('config_update', $user_profile->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="name">Company Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" autofocus
                                                        name="name" value="{{ $user_profile->name }}">
                                                    @error('name')
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    @enderror
                                                </div>

                                                @if (auth()->user()->is_admin == '1')
                                                    <div class="form-group col-md-6">
                                                        <label for="branch">Location<span
                                                                class="text-danger">*</span></label>
                                                        <select name="branch" id="branch" class="form-control"
                                                            required>
                                                            @foreach ($branchs as $branch)
                                                                <option value="{{ $branch->id }}"
                                                                    {{ $branch->id == $user_profile->branch ? 'selected' : '' }}>
                                                                    {{ $branch->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('branch')
                                                            <span class="text-danger">{{ $errors->first('branch') }}</span>
                                                        @enderror



                                                    </div>
                                                @else
                                                    <div class="form-group col-md-6">
                                                        <label for="branch">Location<span
                                                                class="text-danger">*</span></label>
                                                        <select name="branch" id="branch" class="form-control"
                                                            required>

                                                            @foreach ($branchs as $branch)
                                                                @if ($branch->id == auth()->user()->level)
                                                                    <option value="{{ $branch->id }}"
                                                                        {{ $branch->id == $user_profile->branch ? 'selected' : '' }}>
                                                                        {{ $branch->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Company Logo</label>
                                                <div id="logoPreviewContainer" class="mt-3">
                                                    @if ($user_profile->logos)
                                                        <a href="{{ asset('logos/' . $user_profile->logos) }}"
                                                            target="_blank" id="logoLink">
                                                            <img src="{{ asset('logos/' . $user_profile->logos) }}"
                                                                id="logoPreview" class="img-thumbnail"
                                                                style="max-width: 200px; max-height: 200px;"
                                                                alt="Company Logo Preview">
                                                        </a>
                                                    @else
                                                        <label for="">Logo doesn't have ,Upload here</label>
                                                    @endif
                                                </div>
                                                <input type="file" class="form-control mt-3" id="name" autofocus
                                                    name="logos" value="">
                                                @error('logos')
                                                    <span class="text_danger">{{ $errors->first('logos') }}</span>
                                                @enderror

                                            </div>

                                            <div class="form-group">
                                                <label for="name">Address</label>
                                                <textarea name="address" id="" cols="30" rows="5" class="form-control">{{ $user_profile->address }}</textarea>
                                                @error('address')
                                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Description</label>
                                                <textarea name="description" id="" cols="30" rows="2" class="form-control">{{ $user_profile->description }}</textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                                @enderror
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phno1">Phone Number <span
                                                                class="text-danger">*</span></label>
                                                        <input type="tel" class="form-control" id="phone_number1"
                                                            name="phno1" value="{{ $user_profile->phno1 }}">
                                                        @error('phno1')
                                                            <span class="text-danger">{{ $errors->first('phno1') }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phno2">Phone Number</label>
                                                        <input type="tel" class="form-control" id="phone_number2"
                                                            name="phno2" value="{{ $user_profile->phno2 }}">
                                                        @error('phno2')
                                                            <span class="text-danger">{{ $errors->first('phno2') }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phno2">Email</label>
                                                        <input type="email" class="form-control" id="phone_number2"
                                                            name="email" value="{{ $user_profile->email }}">
                                                        @error('email')
                                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-end">
                                            <a href="{{ url('config_manage') }}" type="submit"
                                                class="btn btn-danger">Back</a>
                                            <button type="submit" class="btn btn-primary">Update</button>
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
