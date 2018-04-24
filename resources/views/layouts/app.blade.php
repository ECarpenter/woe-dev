<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Work Order Express</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/lou-multi-select-7a5354c/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
            background-color: #F0F7FF;
        }

        .fa-btn {
            margin-right: 6px;
        }

        .col-centered {
            float: none;
            margin:0 auto;
        }
        .ejc-inactive {
            background-color: #835A5A;
        }
        .table-hover > tbody > tr > td.ejc-inactive:hover,
        .table-hover > tbody > tr > th.ejc-inactive:hover,
        .table-hover > tbody > tr.ejc-inactive:hover > td,
        .table-hover > tbody > tr:hover > .ejc-inactive,
        .table-hover > tbody > tr.ejc-inactive:hover > th {
            background-color: #E29A9A;
        } 
        .insurance-table {
            background-color: white;
            border: 2px solid black;

        }
        .insurance-missing {
            text-align: center;
        }

        .button-color {
            background-color: #5bc0de;           
        } 

        .disclaimer {
            background-color: #F0F7FF;
            color: red;
        }

        
    
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('http://www.davispartners.com/') }}">

                    <img border="0" alt="Davis Partners" src="/images/logo_transparent.png" width="189" height="30">

                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                    @role('tenant')
                    <li><a href="{{ url('/submit') }}">Submit Work Order</a></li>
                    @endrole
                    @permission('manage-wo')
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Workorders<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">

                            <li><a href="{{ url('/submit') }}">Submit Work Order</a></li>
                            <li><a href="{{ url('/workorders') }}">View Work Order</a></li>
                        </ul>
                    </li>
                    @endpermission          
                    @permission('general')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                Properties<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li><a href="#" data-toggle="modal" data-target="#PropIDModal">View Property</a></li>
                                <li><a href="/property/list">All Properties</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#InsuranceReportModal">Insurance Report</a></li>
                                </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                Tenants<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li><a href="#" data-toggle="modal" data-target="#TenantIDModal">View Tenants</a></li>
                                <li><a href="/tenant/list">Tenant List</a></li>
                                <li><a href="/tenant/unverifiedlist">Unverified Tenant List</a></li>
                                @permission('manage-insurance')
                                    <li><a href="/tenant/uploadlist">Pending Uploads</a></li>
                                    <li><a href="/tenant/noncompliancelist">Insurance Noncompliance List</a></li>
                                @endpermission
                            </ul>
                        </li>
                        @permission('advanced-setup')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Setup<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" data-toggle="modal" data-target="#importModal">Import from Excel</a></li>
                                    <li><a herf="#" data-toggle="modal" data-target="#AddOwnerModal">Add Owner</a></li>
                                    <li><a href="#">Edit Problem Types</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#AddRemitModal">Add Remitance Info</a></li>
                                    <li><a href="/tenant/add">Add Tenant</a></li>
                                    <li><a href="/property/add">Add Property</a></li>
                                    <li><a href="/group/add">Add Property Group</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#GroupIDModal">Manage Property Group</a></li>
                                </ul>
                            </li>
                        @endpermission
                    @endpermission
                    @role('admin')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                Users<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li><a href="/user/list">View Users</a></li>
                                <li><a href="/user/add">Add Users</a></li>
                            </ul>
                        </li>
                    @endrole
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/tenantregister') }}">Tenant Registration</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user/changepassword')}}">Change Password</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                                <li><a href="mailto:support@ejcustom.com">Support</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible text-center col-xs-10 col-xs-offset-1 col-md-7 col-md-offset-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('success') }}
        </div>  
    @endif

    @if(Session::has('info'))
        <div class="alert alert-info alert-dismissible text-center col-xs-10 col-xs-offset-1 col-md-7 col-md-offset-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('info') }}
        </div>  
    @endif

    @if(Session::has('warning'))
        <div class="alert alert-warning alert-dismissible text-center col-xs-10 col-xs-offset-1 col-md-7 col-md-offset-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('warning') }}
        </div>  
    @endif

    @if(Session::has('danger'))
        <div class="alert alert-danger alert-dismissible text-center col-xs-10 col-xs-offset-1 col-md-7 col-md-offset-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('danger') }}
        </div>  
    @endif

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/lou-multi-select-7a5354c/js/jquery.multi-select.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/woe-ajax.js') }}"></script> 

    <!-- PropertyIDModal (Pop up when view property link clicked) -->
    <div class="modal fade" id="PropIDModal" tabindex="-1" role="dialog" aria-labelledby="PropIDLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="PropIDLabel">View Property</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/property" class="form-horizontal">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="inputPropID" class="col-xs-3 control-label">Enter Property ID</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="property_system_id"  placeholder="Yardi ID" value="">
                            </div>
                            
                            <div class="col-xs-3" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- TenantIDModal (Pop up when view tenant link clicked) -->
    <div class="modal fade" id="TenantIDModal" tabindex="-1" role="dialog" aria-labelledby="tenantIDLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="tenantIDLabel">Find Tenant</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/tenant" class="form-horizontal">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="inputTenantID" class="col-xs-3 control-label">Enter Tenant ID</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="tenant_system_id"  placeholder="Yardi ID" value="">
                            </div>
                            
                            
                            <div class="col-xs-3" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- InsuranceReportModal (Pop up when report link clicked) -->
    <div class="modal fade" id="InsuranceReportModal" tabindex="-1" role="dialog" aria-labelledby="InsuranceReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="InsuranceReportLabel">View Property</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/insurancereport" class="form-horizontal">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="inputPropID" class="col-xs-3 control-label">Enter Property ID</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="property_system_id"  placeholder="Yardi ID" value="">
                            </div>
                            
                            <div class="col-xs-3" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Single upload box --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="importLabel">Import From Excel</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/import" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-xs-3" >
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="importType" value="lease">
                                        Lease Summary
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="property">
                                        Properties
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="tenant">
                                        Tenants
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="past">
                                        Past Tenants
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="sold">
                                        Sold Properties
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="transfer">
                                        Transfer Tenants
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="insreq">
                                        Insurance Requirements
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="remit">
                                        Remitance Info
                                    </label>
                                    <label>
                                        <input type="radio" name="importType" value="manager">
                                        Update Managers
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="col-xs-3" >
                                <input type="file" accept=".xls,.xlsx" name="importFile">
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="col-xs-3 col-xs-offset-3" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- GroupIDModal (Pop up when view group link clicked) -->
    <div class="modal fade" id="GroupIDModal" tabindex="-1" role="dialog" aria-labelledby="GroupIDLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="GroupIDLabel">View Group</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/group" class="form-horizontal">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="inputPropID" class="col-xs-3 control-label">Enter Group ID</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="group_system_id"  placeholder="Yardi ID" value="">
                            </div>
                            
                            <div class="col-xs-3" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- RemitanceModal (Pop up when view add remitance link clicked) -->
    <div class="modal fade" id="AddRemitModal" tabindex="-1" role="dialog" aria-labelledby="AddRemitLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="AddRemitLabel">Add Remitance Info</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/vendor/add" class="form-horizontal">
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">System ID# *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="system_id" id="system_id" value="{{ old('system_id') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">Payable To *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="payable_to" id="payable_to" value="{{ old('payable_to') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">Address *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">Address 2</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="addres_secondline" id="address_secondline" value="{{ old('address_secondline') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">City *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">State *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="state" id="state" value="{{ old('state') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">Zip *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="zip" id="zip" value="{{ old('zip') }}">
                                </div>
                            </div>   
                        </div>
                        <div class="row">
                            <label class="col-xs-2 col-xs-offset-2 control-label">* Required</label>
                            
                            <div class="col-xs-3 col-xs-offset-1" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Add</button>
                            </div>
                        </div>

                       
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- AddOWnerModal (Pop up when view add remitance link clicked) -->
    <div class="modal fade" id="AddOwnerModal" tabindex="-1" role="dialog" aria-labelledby="AddOwnerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="AddOwnerLabel">Add Owner Info</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/owner/add" class="form-horizontal">
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">Name *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="owner_name" id="owner_name" value="{{ old('owner_name') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">AP - Email *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="ap_email" id="ap_email" value="{{ old('ap_email') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-xs-4 control-label">AR Email *</label>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="ar_email" id="ar_email" value="{{ old('ar_email') }}">
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <label class="col-xs-2 col-xs-offset-2 control-label">* Required</label>
                            
                            <div class="col-xs-3 col-xs-offset-1" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
