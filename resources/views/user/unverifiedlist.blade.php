@extends('layouts.app')

@section('content')
<p hidden>testTenantUnverifiedList</p

	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">	
				<table class="table table-hover">
						<tr>
							<th>User</th>
							<th>E-Mail</th>
							<th>Company Name</th>
						</tr>
					@foreach ($users as $user)
						
						<tr class="open-verification-modal" data-value="{{$user->id}}">
							<td>{{$user->name}}</td>
							<td>{{$user->email}}</td>
							<td>{{$user->company_name}}</td>
						</tr>
						
					@endforeach
				</table>
			</div>
		</div>
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