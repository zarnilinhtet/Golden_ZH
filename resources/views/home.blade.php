<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', 'Times New Roman', serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
        }

        .navbar {
            background: #212529 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .navbar-brand {
            font-weight: bold;
            letter-spacing: 2px;
            color: #fff !important;
        }

        .hero-section {
            background: linear-gradient(90deg, #bf1717 0%, #f06565 100%);
            color: #fff;
            border-radius: 1rem;
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 24px rgba(99, 102, 241, 0.12);
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .hero-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        }

        .dashboard-card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(60, 72, 100, 0.10);
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .dashboard-card:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 8px 32px rgba(60, 72, 100, 0.18);
        }

        .dashboard-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .bg-purple {
            background: #ed0b0b !important;
        }

        .text-purple {
            color: #6d28d9 !important;
        }

        .dashboard-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        @media (max-width: 767px) {
            .hero-section {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                CodeVerse-Web Solutions
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white small"><i class="fa-regular fa-calendar"></i> Date - <?= date('d-m-y') ?></span>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero-section">
            {{-- <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=128"
                class="hero-avatar" alt="User Avatar"> --}}
            <div>
                <h2 class="mb-1">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="mb-0">Your personalized dashboard gives you quick access to all your modules.</p>
            </div>
        </div>

        <div class="row g-4 mt-4">
            @php
                $userPermissions = [];
                if (auth()->user()->permission) {
                    $decodedPermissions = json_decode(auth()->user()->permission, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $userPermissions = $decodedPermissions;
                    }
                }
            @endphp

            <!-- Dashboard -->
            <div class="col-md-4 col-12 col-lg-3">
                <a href="{{ url('dashboard') }}" style="text-decoration: none;">
                    <div class="dashboard-card text-success d-flex flex-column align-items-center p-4">
                        <i class="fas fa-home dashboard-icon bg-light text-success rounded-circle p-3"></i>
                        <div class="dashboard-title mt-2">Dashboard</div>
                    </div>
                </a>
            </div>

            @if (in_array('Item', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('items') }}" style="text-decoration: none;">
                        <div class="dashboard-card text-danger d-flex flex-column align-items-center p-4">
                            <i class="fas fa-boxes dashboard-icon bg-light text-danger rounded-circle p-3"></i>
                            <div class="dashboard-title mt-2">Products</div>
                        </div>
                    </a>
                </div>
            @endif

            @if (in_array('Invoice', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('invoice') }}" style="text-decoration: none;">
                        <div class="dashboard-card text-warning d-flex flex-column align-items-center p-4">
                            <i
                                class="fas fa-file-invoice-dollar dashboard-icon bg-light text-warning rounded-circle p-3"></i>
                            <div class="dashboard-title mt-2">Invoice</div>
                        </div>
                    </a>
                </div>
            @endif

            @if (in_array('Purchase Order', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('purchase_order_manage') }}" style="text-decoration: none;">
                        <div class="dashboard-card text-info d-flex flex-column align-items-center p-4">
                            <i class="fas fa-shopping-cart dashboard-icon bg-light text-info rounded-circle p-3"></i>
                            <div class="dashboard-title mt-2">PO</div>
                        </div>
                    </a>
                </div>
            @endif

            @if (in_array('Transfer', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('show_transfer_history') }}" style="text-decoration: none;">
                        <div class="dashboard-card text-secondary d-flex flex-column align-items-center p-4">
                            <i
                                class="fas fa-exchange-alt dashboard-icon bg-light text-secondary rounded-circle p-3"></i>
                            <div class="dashboard-title mt-2">Transfer</div>
                        </div>
                    </a>
                </div>
            @endif

            @if (in_array('Expenses', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('expense') }}" style="text-decoration: none;">
                        <div class="dashboard-card text-dark d-flex flex-column align-items-center p-4">
                            <i class="fas fa-wallet dashboard-icon bg-light  rounded-circle p-3"
                                style="color: #6d28d9"></i>
                            <div class="dashboard-title mt-2" style="color: #6d28d9">Expenses</div>
                        </div>
                    </a>
                </div>
            @endif

            @if (in_array('Account', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('account') }}" style="text-decoration: none;">
                        <div class="dashboard-card text-primary d-flex flex-column align-items-center p-4">
                            <i class="fas fa-calculator dashboard-icon bg-light  rounded-circle p-3"
                                style="color: #2837d9"></i>
                            <div class="dashboard-title mt-2" style="color: #2837d9">Accounting</div>
                        </div>
                    </a>
                </div>
            @endif

            @if (in_array('Customer', $userPermissions) || auth()->user()->is_admin == '1')
                <div class="col-md-4 col-12 col-lg-3">
                    <a href="{{ url('customer') }}" style="text-decoration: none;">
                        <div class="dashboard-card  d-flex flex-column align-items-center p-4">
                            <i class="fas fa-users dashboard-icon bg-light  rounded-circle p-3"
                                style="color: #ee16ee"></i>
                            <div class="dashboard-title mt-2" style="color: #ee16ee">Customer</div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
