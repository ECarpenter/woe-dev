@extends('layouts.app')

@section('content')
<p hidden>testGetTenantRegister</p>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h5>Tenant Registration</h5><h6> <small>* denotes required field</small></h6></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('City') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">City</label>

                            <div class="col-md-6">
                                <select name = 'city' class="form-control">
                                    <option value=''>Select One</option>
                                    @foreach ($cities as $city)
                                        <option value="{{$city}}"> {{ $city }} </option>
                                    @endforeach
                                </select>

                                
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('property') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Property*</label>

                            <div class="col-md-6">
                            <select name = 'property' class="form-control">
                                <option value=''>Select A City</option>
                                
                            </select>

                            
                                @if ($errors->has('property'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('property') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>  

                         <div class="form-group">
                            <label class="col-md-4 control-label">Tenant ID#</label>

                            <div class="col-md-6" id="tenantidform">
                                <input type="text" class="form-control" name="tenant_system_id" id='tenantidinput' value="{{ old('tenant_system_id') }}">
                            </div>

                        </div>      

                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Company Name*</label>

                            <div class="col-md-6" id="companynameform">
                                <input type="text" class="form-control" id="companynameinput" name="company_name" value="{{ old('company_name') }}">

                                @if ($errors->has('company_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Your Name*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Job Title*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="job_title" value="{{ old('job_title') }}">

                                @if ($errors->has('job_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('job_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>    

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address*</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password*</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password*</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
