<aside class="main-sidebar elevation-4" style="background-color:black">
    <!-- Brand Logo -->
    <span class="text-center brand-link ">
        <span class="brand-text text-white font-weight-bold">POS</span>
    </span>


    <!-- Sidebar -->
    <div class="sidebar">


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @php
                    $userPermissions = [];
                    if (auth()->user()->permission) {
                        $decodedPermissions = json_decode(auth()->user()->permission, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $userPermissions = $decodedPermissions;
                        }
                    }
                @endphp
                @if (in_array('Home', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/home') }}" class="nav-link">
                            <i class="fa-solid fa-home nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Home </p>
                        </a>

                    </li>
                @endif
                @if (in_array('Dashboard', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class="nav-link">
                            <i class="fa-solid fa-table-columns nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Dashboard </p>
                        </a>

                    </li>
                @endif

                @if (in_array('Item', $userPermissions) ||
                        in_array('Item Kits', $userPermissions) ||
                        auth()->user()->is_admin == '1' ||
                        in_array('Price Percent', $userPermissions))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-table text-white"></i>
                            <p class="pl-3 text-white">
                                Products
                                <i class="right fas fa-angle-left text-white"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (in_array('Item', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item text-white">
                                    <a href="{{ url('items') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-white"></i>
                                        <p class='text-white'>Product</p>
                                    </a>
                                </li>
                            @endif
                            {{-- @if (in_array('Item Kits', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item text-white">
                                    <a href="{{ url('item_kits') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-white"></i>
                                        <p class='text-white'>Item Kits</p>
                                    </a>
                                </li>
                            @endif --}}

                            @if (in_array('Item Register', $userPermissions) ||
                                    in_array('Item Kits Register', $userPermissions) ||
                                    auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('items_register') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-white"></i>
                                        <p class='text-white'>Product Register</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('Item Expire', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('items_expire') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-white"></i>
                                        <p class='text-white'> Expired Products</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('Reorder Item', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('reorder_item') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-white"></i>
                                        <p class='text-white'>Reorder Products </p>
                                    </a>
                                </li>
                            @endif
                            {{-- @if (in_array('Price Percent', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('item_price_percent') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-white"></i>
                                        <p class='text-white'>Price Percent </p>
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </li>
                @endif
                <!-- <li class="nav-item">
                    <a href="{{ url('unit') }}" class="nav-link">
                        <i class="fa-solid fa-brands fa-ubuntu nav-icon text-white"></i>
                        <p class="pl-3 text-white">
                            Unit </p>
                    </a>
                </li> -->

                @if (in_array('Supplier', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/supplier') }}" class="nav-link">
                            <i class="fa-solid fa-boxes-packing nav-icon text-white"></i>
                            <p class="pl-3 text-white">
                                Supplier </p>
                        </a>

                    </li>
                @endif
                @if (in_array('Patient', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/customer') }}" class="nav-link">
                            <i class="fa-solid fa-user-plus nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Customer </p>
                        </a>

                    </li>
                @endif
                {{-- @if (in_array('Treatment', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/treatment') }}" class="nav-link">
                            <i class="fa-solid fa-hospital nav-icon  text-white"></i>
                            <p class="pl-3 text-white">Treatment</p>
                        </a>
                    </li>
                @endif
                @if (in_array('Doctor,', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/doctors') }}" class="nav-link">
                            <i class="text-white fa-solid fa-user-doctor nav-icon"></i>
                            <p class="pl-3 text-white">Doctors </p>
                        </a>

                    </li>
                @endif --}}

                <!-- <li class="nav-item">
                    <a href="{{ url('/pos') }}" class="nav-link">
                        <i class="fa-solid fa-cart-plus nav-icon"></i>
                        <p class="pl-3">
                            POS </p>
                    </a>

                </li> -->
                {{-- @if (in_array('POS', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/pos') }}" class="nav-link">
                            <i class="fa-solid fa-cart-plus nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                POS
                            </p><i class=" right fas fa-angle-left  text-white"></i>
                        </a>


                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('pos_manage') }}" class="nav-link">
                                    <i class=" far fa-circle nav-icon  text-white"></i>
                                    <p class=" text-white">POS Management</p>
                                </a>
                            </li>
                            @if (in_array('POS Register', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('pos_register') }}" class="nav-link">
                                        <i class=" far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Issue POS</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}
                @if (in_array('Invoice', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/invoice') }}" class="nav-link">

                            <i class="fa-solid fa-person  nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Invoice
                            </p><i class="right fas fa-angle-left  text-white"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('invoice') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon text-white"></i>
                                    <p class=" text-white">Invoice Manage</p>
                                </a>
                            </li>
                            @if (in_array('Invoice Register', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('invoice_reg') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Issue Invoice</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('Invoice Record', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('invoice_record') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class="text-white">Invoice
                                            Record</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (in_array('Sale Person', $userPermissions) ||
                        in_array('Sale Person Assign', $userPermissions) ||
                        in_array('WayCall', $userPermissions) ||
                        auth()->user()->is_admin == '1')

                    <li class="nav-item">
                        <a href="{{ url('/invoice') }}" class="nav-link">
                            <i class="fa-solid fa-file-invoice-dollar nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Sale Person
                            </p><i class="right fas fa-angle-left  text-white"></i>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('sale_person') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon  text-white"></i>

                                    <p class=" text-white"> Sale Person</p>
                                </a>
                            </li>
                            @if (in_array('Sale Person Assign', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('sale_person_assign') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Sale Person Assign</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('WayCall', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('way_call') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Way/Call</p>
                                    </a>
                                </li>
                            @endif
                            {{-- @if (in_array('Way Call Task', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('way_call_task') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Way Call Task</p>
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </li>
                @endif

                @if (in_array('Quotation', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/quotation') }}" class="nav-link">
                            <i class="nav-icon fas fa-copy  text-white"></i>
                            <!-- <i class="fa-solid fa-receipt nav-icon"></i> -->
                            <p class="pl-3  text-white">
                                Quotation
                            </p><i class="right fas fa-angle-left  text-white"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('quotation') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon  text-white"></i>
                                    <p class=" text-white">Quotation Manage</p>
                                </a>
                            </li>
                            @if (in_array('Quotation Register', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('quotation_register') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Issue Quotation</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <!-- <li class="nav-item">
                    <a href="{{ url('/warehouse') }}" class="nav-link">
                        <i class="fa-solid fa-house nav-icon"></i>
                        <p class="pl-3">
                            Warehouse
                        </p>
                    </a>
                </li> -->
                @if (in_array('Location', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/warehouse') }}" class="nav-link">
                            <i class="fa-solid fa-house nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Location
                            </p>
                        </a>
                    </li>
                @endif
                @if (in_array('Brand', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/brand') }}" class="nav-link">

                            <i class="fa-solid fa-code-branch text-white nav-icon"></i>
                            <p class="pl-3  text-white">
                                Brand
                            </p>
                        </a>
                    </li>
                @endif
                @if (in_array('Transfer', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="fa-solid fa-shuffle  nav-icon text-white"></i>
                            <p class=" text-white pl-3">Transfer Item</p>
                            <i class="right fas fa-angle-left  text-white"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (in_array('Transfer Item', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('transfer_item') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Transfer Item</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ url('show_transfer_history') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon  text-white"></i>
                                    <p class=" text-white">Transfer History</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (in_array('Purchase Order', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="fa-solid fa-receipt nav-icon  text-white"></i>
                            <p class="pl-3  text-white">
                                Purchase Order
                            </p><i class="right fas fa-angle-left  text-white"></i>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('purchase_order_manage') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon  text-white"></i>
                                    <p class=" text-white"> Purchase Order Manage</p>
                                </a>
                            </li>
                            @if (in_array('Purchase Order Register', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('purchase_order_register') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon  text-white"></i>
                                        <p class=" text-white">Issue Purchase Order</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (in_array('Expenses', $userPermissions) ||
                        in_array('Expense Category', $userPermissions) ||
                        auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('expense') }}" class="nav-link">
                            {{-- <i class="text-white fa-solid fa-money-check-dollar "></i> --}}
                            <i class="fas fa-comment-dollar nav-icon text-white"></i>
                            <p class="pl-3 text-white">
                                Expenses </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (in_array('Expenses', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/expense') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Expense Register</p>
                                    </a>
                                </li>
                            @endif

                            @if (in_array('Expense Category', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('expense_category') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Expense Category</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                {{-- @if (in_array('Service Type', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('service') }}" class="nav-link">

                            <i class="fas fa-solid fa-building nav-icon text-white"></i>
                            <p class="pl-3 text-white">
                                Department </p>
                        </a>

                    </li>
                @endif --}}
                @if (in_array('Account', $userPermissions) ||
                        in_array('Transaction', $userPermissions) ||
                        in_array('Setting', $userPermissions) ||
                        auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('account') }}" class="nav-link">
                            <i class="text-white fa-solid fa-money-check-dollar nav-icon"></i>
                            <p class="pl-3 text-white">
                                Accounting </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (in_array('Account', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/account') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Account</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('Transaction', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('transaction') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Transaction</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('Setting', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('setting') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Setting</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (in_array('Report', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="fa-solid fa-bars nav-icon  text-white"></i>

                            <p class="pl-3 text-white">
                                Report </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (in_array('Invoice Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Invoice</p>
                                    </a>
                                </li>
                            @endif
                             @if (in_array('Customer Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('customer_report') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Customer</p>
                                    </a>
                                </li>
                            @endif


                            @if (in_array('Quotation Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_quotation') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Quotation</p>
                                    </a>
                                </li>
                            @endif

                            {{-- @if (in_array('POS Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_pos') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">POS </p>
                                    </a>
                                </li>
                            @endif --}}

                            @if (in_array('Expenses Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_expense') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Expense</p>
                                    </a>
                                </li>
                            @endif


                            @if (in_array('Purchase Order Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_po') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Purchase Order</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('Sale Person Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_sale_person') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Sale Person</p>
                                    </a>
                                </li>
                            @endif


                            @if (in_array('Sale Return (Invoice)', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_sale_return') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Sale Return</p>
                                    </a>
                                </li>
                            @endif


                            @if (in_array('Item Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/report_item') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Item</p>
                                    </a>
                                </li>
                            @endif
                            {{-- @if (in_array('Doctor Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('doctor') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Doctor</p>
                                    </a>
                                </li>
                            @endif --}}
                            {{-- @if (in_array('INOUT Patient', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('inout_patient') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">IN/OUT Patient</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('FOC Patient', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('foc_patient') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">FOC Patient</p>
                                    </a>
                                </li>
                            @endif --}}

                        </ul>
                    </li>
                @endif
                {{-- @if (in_array('Accounting Report', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="fa-solid  nav-icon  text-white fa fa-money-bills"></i>

                            <p class="pl-3 text-white">
                                Accounting Report </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">

                            @if (in_array('Location Report', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/account_location_report') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Location Report</p>
                                    </a>
                                </li>
                            @endif

                            @if (in_array('General Ledger', $userPermissions) || in_array('Profit&Loss', $userPermissions) || in_array('Balance Sheet', $userPermissions) || auth()->user()->is_admin == '1')

                                <li class="nav-item">
                                    <a href="{{ url('/report_account_total') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">Total Report</p>
                                    </a>
                                </li>
                            @endif



                        </ul>
                    </li>
                @endif --}}
                @if (in_array('User', $userPermissions) || in_array('User Type', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="text-white fa-solid fa-users nav-icon"></i>
                            <p class="pl-3 text-white">
                                User </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (in_array('User', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/user') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">User</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('User Type', $userPermissions) || auth()->user()->is_admin == '1')
                                <li class="nav-item">
                                    <a href="{{ url('/user_type') }}" class="nav-link">
                                        <i class="text-white far fa-circle nav-icon"></i>
                                        <p class="text-white">User Type</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (in_array('Configuration', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/config_manage') }}" class="nav-link">
                            <i class="fa-solid fa-gear nav-icon text-white"></i>
                            <p class="pl-3 text-white">
                                Configuration
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
