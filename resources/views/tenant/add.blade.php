@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h5>Tenant Registration</h5><h6> <small>* denotes required field</small></h6></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/tenant/save">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('property') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Property*</label>

                            <div class="col-md-6">
                            <select name = 'property' class="form-control">
                                <option value=''>Select One</option>
                                @foreach ($properties as $property)
                                    <option value={{$property->id}}> {{ $property->name }} </option>
                                @endforeach
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

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="tenant_system_id" value="{{ old('tenant_system_id') }}">
                            </div>
                        </div>                        

                        <div class="form-group{{ $errors->has('suite') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Suite*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="suite" value="{{ old('suite') }}">

                                @if ($errors->has('suite'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('suite') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                     
                         
                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Company Name*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}">

                                @if ($errors->has('company_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Title*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="job_title" value="{{ old('job_title') }}">

                                @if ($errors->has('job_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('job_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>    

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
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

                        <div class="form-group">
                            
                                <input type="hidden" class="form-control" name="password" value="password">

                                                  
                                <input type="hidden" class="form-control" name="password_confirmation" value="password">

                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Submit
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
