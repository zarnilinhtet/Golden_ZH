 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
     <!-- Left navbar links -->
     <ul class="navbar-nav col-md-6">
         <li class="nav-item">
             <a class="nav-link  text-gray" data-widget="pushmenu" href="#" role="button"><i
                     class="fas fa-bars"></i></a>
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
