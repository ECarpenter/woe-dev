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
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }

        .col-centered {
            float: none;
            margin:0 auto;
        }
        .ejc-inactive {
            background-color: #835A5A
        }
        .table-hover > tbody > tr > td.ejc-inactive:hover,
        .table-hover > tbody > tr > th.ejc-inactive:hover,
        .table-hover > tbody > tr.ejc-inactive:hover > td,
        .table-hover > tbody > tr:hover > .ejc-inactive,
        .table-hover > tbody > tr.ejc-inactive:hover > th {
            background-color: #E29A9A;
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
                            <li><a href="/property/add">Add Property</a></li>
                            <li><a href="/property/list">All Properties</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#propertyImportModal">Property Import</a></li>
                            <li><a href="/group/add">Add Property Group</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#GroupIDModal">Manage Property Group</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Tenants<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">

                            <li><a href="#" data-toggle="modal" data-target="#TenantIDModal">View Tenants</a></li>
                            <li><a href="/tenant/add">Add Tenant</a></li>
                            <li><a href="/tenant/list">Tenant List</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#tenantImportModal">Tenant Import</a></li>
                            
                            @permission('manage-insurance')
                            <li><a href="/tenant/uploadlist">Pending Uploads</a></li>
                            <li><a href="/tenant/noncompliancelist">Insurance Noncompliance List</a></li>
                            @endpermission
                        </ul>
                    </li>
                    @endpermission
                    @permission('admin')
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Users<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">

                            <li><a href="/user/list">View Users</a></li>
                            <li><a href="/user/add">Add Users</a></li>
                        </ul>
                    </li>
                    @endpermission
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user/changepassword')}}">Change Password</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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

    <div class="modal fade" id="propertyImportModal" tabindex="-1" role="dialog" aria-labelledby="propertyImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="propertyImportLabel">Import Properties</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/property/import" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            
                            <input type="file" accept=".xls" name="propertyimport">
                            <br>
                            
                            <div class="col-xs-3" class="form-group">
                                <button type='submit' class="btn btn-primary btn-md">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tenantImportModal" tabindex="-1" role="dialog" aria-labelledby="tenantImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="tenantImportLabel">Import Tenants</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/tenant/import" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            
                            <input type="file" accept=".xls" name="tenantimport">
                            <br>
                            
                            <div class="col-xs-3" class="form-group">
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
</body>
</html>
