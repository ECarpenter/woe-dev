@extends('layouts.app')

@section('content')
<p hidden>testHome</p>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Work Orders</div>

                <div class="panel-body">
                    
      
                    @permission('manage-wo')
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">  
                                <table class="table table-hover">
                                        <tr>
                                            <th>Tenant</th>
                                            <th>Property</th>
                                            <th>Tenant</th>
                                            <th>Status</th>
                                        </tr>
                                    @foreach ($workorders as $workorder)
                                        <tr onclick = "location.href='/workorders/{{$workorder->id}}'">
                                            
                                            <td>{{date('F d, Y, g:i a', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</td>
                                            <td>{{$workorder->Property()->name}}</td>
                                            @if ($workorder->tenant_id == 0)
                                                <td>{{$workorder->User->company_name}}</td>
                                            @else
                                                <td>{{$workorder->Tenant->company_name}}</td>
                                            @endif
                                            
                                            <td>{{$workorder->status}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endpermission
                    @role('tenant')
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">  
                                <table class="table table-hover">

                                        <tr>
                                            <th>Date</th>
                                            <th>Problem Type</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    @foreach ($workorders as $workorder)
                                        <tr onclick = "location.href='/workorders-tenant/{{$workorder->id}}'">
                                            
                                            <td>{{date('F d, Y, g:i a', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</td>
                                            <td>{{$workorder->ProblemType->type}}</td>
                                            <td>{{$workorder->description}}</td>
                                            <td>{{$workorder->status}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div> 
    @permission('manage-wo')
        @if ($users->count() != 0)
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Unverified Tenants</div>

                        <div class="panel-body">
                            
              
                            
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">  
                                        <table class="table table-hover">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Property
                                                    <th>Company Name</th>
                                                    <th>email</th>
                                                </tr>
                                            @foreach ($users as $user)
                                                <tr class="open-verification-modal" data-value="{{$user->id}}">
                                                    
                                                    <td>{{$user->name}}</td>
                                                    <td>{{$user->Property()->name}}</td>
                                                    <td>{{$user->company_name}}</td>
                                                    <td>{{$user->email}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endpermission
</div>


<div class="modal fade" id="TenantVerificationModal" tabindex="-1" role="dialog" aria-labelledby="VerificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="VerificationModalLabel">Verify User</h4>
                </div>


                <form class="form-horizontal" role="form" method="POST" action="/user/verify/update">
                    {!! csrf_field() !!}
                    {{ method_field('PATCH') }}
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company Name</label>
                            <div class="col-md-6" id="company_name_display">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">User Name</label>
                            <div class="col-md-6" id="user_name_display">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">User e-mail</label>
                            <div class="col-md-6" id="user_email_display">
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label class="col-md-4 control-label">User Job Title</label>
                            <div class="col-md-6" id="user_job_title_display">
                            </div>
                        </div>
                        

                        <div class="form-group{{ $errors->has('property') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Property</label>

                            <div class="col-md-6">
                            <select name = 'property' id="property_verify" class="form-control">
                                
                            </select>

                            
                                @if ($errors->has('property'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('property') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>  
                        <div class="form-group{{ $errors->has('tenant') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Tenant</label>

                            <div class="col-md-6">
                            <select name = 'tenant' id="tenant_verify" class="form-control">
                                <option value=""> Select Tenant </option>
                                
                            </select>

                            
                                @if ($errors->has('tenant'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tenant') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                
                
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-xs-2 col-xs-offset-4">
                                <input class="btn btn-primary" type="submit" value="Verify">
                            </div>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="0">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
