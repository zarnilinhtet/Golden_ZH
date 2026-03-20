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
                                <h1>Way Call Task Edit</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ url('way_call_task') }}">Way Call Task</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit
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
                                    <h3 class="card-title  " style="font-weight: bold;">Way Call Task Edit</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <form action="{{ url('way_call_task_update', $way_call_task->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
  <div class="row"><div class="form-group col-md-6">
                                                <label for="name">Doctor Name<span class="text-danger">*</span></label>


                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Doctor Name" required autofocus name="doctor_name" value="{{ $way_call_task->doctor_name }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Designation</label>
                                                <!-- <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Unit" required autofocus name="unit"> -->

                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Designation" autofocus name="designation" value="{{ $way_call_task->designation }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Speciality</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Speciality" autofocus name="speciality" value="{{ $way_call_task->speciality }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="unit">Hp/Clinic</label>


                                                <input type="text" class="form-control"
                                                    placeholder="Enter Hp/Clinic" autofocus name="hp_clinic" value="{{ $way_call_task->hp_clinic }}">
                                            </div>

                                             @if (auth()->user()->is_admin == '1' || auth()->user()->type == 'Admin')

                                            <div class="form-group d-none">
                                                <label for="branch">Location<span
                                                        class="text-danger">*</span></label>
                                                <select name="location" id="branch" class="form-control"
                                                    required>

                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ $way_call_task->location == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="form-group col-md-6" style="display: none;">
                                                <label for="branch">Location<span
                                                        class="text-danger">*</span></label>

                                                <input class="form-control" type="text" name="location"
                                                    id="branch" value="{{ auth()->user()->level }}"
                                                    required>
                                            </div>
                                        @endif
                                            <div class="form-group col-md-6">
                                                <label for="base_id">Type</label>
                                                <select name="type" id="" class="form-control">
                                                    <option value="Operation Service" {{ $way_call_task->type == 'Operation Service' ? 'selected' : '' }}>Operation Service</option>
                                                    <option value="Control Service" {{ $way_call_task->type == 'Control Service' ? 'selected' : '' }}>Control Service</option>
                                                    <option value="Delivery Call" {{ $way_call_task->type == 'Delivery Call' ? 'selected' : '' }}>Delivery Call</option>
                                                    <option value="KOL Relation/Promotion" {{ $way_call_task->type == 'KOL RelationPromotion' ? 'selected' : '' }}>KOL Relation/Promotion</option>
                                                    <option value="Holiday" {{ $way_call_task->type == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                                                    <option value="Leave" {{ $way_call_task->type == 'Leave' ? 'selected' : '' }}>Leave</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="date">Date</label>
                                                <input type="date" class="form-control" id="date" name="date"
                                                    required value="{{ $way_call_task->date }}">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="image">Image</label>
                                                <input type="file" class="form-control d-none" id="old_image" name="old_image"
                                                    accept="image/*" value="{{ $way_call_task->image }}">
                                                <input type="file" class="form-control" id="image" name="image"
                                                    accept="image/*" >
                                                @if ($way_call_task->image)
                                                    <img src="{{ asset('upload/task/'. $way_call_task->image) }}" alt="Way Call Task Image"
                                                        class="img-thumbnail mt-2" style="max-width: 200px;">
                                                        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteImage()">Remove</button>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="unit">Address</label>


                                                <input type="text" class="form-control" id="unit"
                                                    placeholder="Enter Address" autofocus name="address" value="{{ $way_call_task->address }}">
                                            </div>
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
    function deleteImage() {
        if (confirm('Are you sure you want to remove this image?')) {
            const imageInput = document.getElementById('old_image');
            imageInput.value = ''; // Clear the file input
            const imgElement = document.querySelector('.img-thumbnail');
            if (imgElement) {
                imgElement.remove(); // Remove the image element from the DOM
            }
        }
    }
</script>
