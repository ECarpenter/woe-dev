@extends ('layouts.app')

@section ('content')
<p hidden>testProperty1</p>
	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			@if ($errors->has('property_user_multiselect'))
				<span class="help-block">
					<strong><font color="#FF000">There needs to be at least ONE manager attached.</font></strong>
				</span>
			@endif
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small>
				 {{$property->name}}
			</small></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-2 col-xs-offset-2">
			<ul class="nav nav-pills nav-stacked">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Users<span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
						@foreach ($property->users as $user)
							<li><a href="{{$user->hasRole('manager') ? ' #' : '/user/'.$user->id}}">{{$user->name}} - {{$user->hasRole('manager') ? ' Manager' : ''}}</a></li>
						@endforeach
						<li class="button-color"><a href="#" class="open-user-property-modal" data-value="{{$property->id}}">Edit Users</a></li>
					</ul>

				</li>
			</ul>
		</div>
		<div class="col-xs-2 col-xs-offset-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				  Tenants<span class="caret"></span>
				</a>

				<ul class="dropdown-menu" role="menu">
					@foreach ($tenants as $tenant)
						<li><a href="/tenant/{{$tenant->id}}">{{$tenant->company_name}}</a></li>
					@endforeach
				</ul>
			</li>
		</ul>
		
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Owner - <small>
			{{$property->Owner->name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> ID - <small>		
			{{$property->property_system_id}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Created - <small>
			{{date('F d, Y, g:i a', strtotime($property->created_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Updated - <small>		
			{{date('F d, Y, g:i a', strtotime($property->updated_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-3 col-xs-offset-2">
			<ul class="nav nav-pills nav-stacked">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					  Insurance Requirements<span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
							<li><a href="#">Liability Single - {{ number_format($property->req_liability_single_limit) }}</a></li>
							<li><a href="#">Liability Combined - {{ number_format($property->req_liability_combined_limit) }}</a></li>
							<li><a href="#">Umbrella - {{ number_format($property->req_umbrella_limit) }}</a></li>
							<li><a href="#">Auto - {{ number_format($property->req_auto_limit) }}</a></li>
							<li><a href="#">Workers Comp - {{ number_format($property->req_workerscomp_limit) }}</a></li>
							<li class="button-color"><a href="#" class="open-property-req-insurance-modal" value="">Edit Requirements</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="col-xs-3 col-xs-offset-1">
			<ul class="nav nav-pills nav-stacked">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					  Remitance Address<span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
							@if ($property->Remit != null)
								<li><a href="#">{{ $property->Remit->payable_to}}</a></li>
								<li><a href="#">{{ $property->Remit->address}}</a></li>
								@if ($property->Remit->address_secondline != null)
									<li><a href="#">{{ $property->Remit->address_secondline}}</a></li>
								@endif
								<li><a href="#">{{ $property->Remit->city}}, {{ $property->Remit->state}} {{ $property->Remit->zip}}</a></li>
							
							<li class="button-color"><a href="#" class="open-remit-modal" data-value="{{$property->remit_id}}">Change Remit Address</a></li>
							@else
							<li class="button-color"><a href="#" class="open-remit-modal" data-value="">Change Remit Address</a></li>
							@endif
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-3 col-xs-offset-6 col-md-3 col-md-offset-6">
			<button class="btn btn-primary open-edit-property-modal btn-xs" value="{{$property->id}}">Edit Property Information</button>
		</div>

		<div class="col-xs-3">
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 col-md-offset-3 text-center">
			<h4> Insurance Non-Compliance </h4>
		</div>	
	</div>


	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Tenant</th>
						<th>ID</th>
					</tr>

				@foreach ($tenants as $tenant)
					@if (!$tenant->insurance->compliant)
						<tr class="{{$tenant->active ? '' : 'ejc-inactive'}}" onclick = "location.href='/tenant/{{$tenant->id}}'">
							<td>{{$tenant->company_name}}</td>
							<td>{{$tenant->tenant_system_id}}</td>
						</tr>
					@endif
				@endforeach

			</table>
		</div>
	</div>
	@if ($property->allow_workorder)
		<div class="row">
			<div class="col-md-5 col-md-offset-3 text-center">
				<h4> Work Orders </h4>
			</div>
			
		</div>

		
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3">	
				<table class="table table-hover">
					<tr class="info">
						<th>Tenant</th>
						<th>Status</th>
						<th>Date</th>
					</tr>
				
					@foreach ($workorders as $workorder)
					<tr onclick = "location.href='/workorders/{{$workorder->id}}'">
						<td>{{$workorder->tenant->company_name}}</td>
						<td>{{$workorder->status}}</td>
						<td>{{date('F d, Y, g:i a', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</td>
					</tr>
					@endforeach
					
				</table>
			</div>
		</div>
	@endif

	{{-- Remit - change Remit vendor --}}
	<div class="modal fade" id="RemitModal" tabindex="-1" role="dialog" aria-labelledby="RemitModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="RemitModalLabel">Change Remitance</h4>
				</div>
				<div class="modal-body">
					<form method="POST" action="/property/remit/{{$property->id}}" >
						{{ csrf_field() }}
						{{ method_field('PATCH') }}

						<div class="row">
							<div class="col-xs-6 col-xs-offset-3">
								<select id="RemitSelect" name="remit" class="form-control">

								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 col-xs-offset-3">
								<div id="RemitDisplay">
								
								</div>
							</div>
						</div>
						<br>
						<div class="col-xs-offset-5">
								<input class="btn btn-primary" type="submit">

						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	{{-- InsuranceReq - will update the insurance requirements --}}
	<div class="modal fade" id="InsReqPropertyModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="EditModalLabel">Edit Insurance Requirements</h4>
				</div>
				<div class="modal-body">
					<form method="POST" action="/insurance/requirements" >
						{{ csrf_field() }}
						{{ method_field('PATCH') }}

						<input type="hidden" name="type" value="property">
						<input type="hidden" name="id" value="{{$property->id}}">

						<div class="row">
							<div class="form-group">
								<label class="col-xs-4 control-label">Liability Single</label>
								<div class="col-xs-6">
									<input type="number" class="form-control" name="req_liability_single_limit" id="req_liability_single_limit" step="100000" value="{{ $property->req_liability_single_limit }}">
								</div>
							</div>   
						</div>

						<div class="row">
							<div class="form-group">
								<label class="col-xs-4 control-label">Liability Combined</label>
								<div class="col-xs-6">
									<input type="number" class="form-control" name="req_liability_combined_limit" id="req_liability_combined_limit" step="100000" value="{{ $property->req_liability_combined_limit }}">
								</div>
							</div>   
						</div>

						<div class="row">
							<div class="form-group">
								<label class="col-xs-4 control-label">Umbrella</label>
								<div class="col-xs-6">
									<input type="number" class="form-control" name="req_umbrella_limit" id="req_umbrella_limit" step="100000" value="{{ $property->req_umbrella_limit }}">
								</div>
							</div>   
						</div>

						<div class="row">
							<div class="form-group">
								<label class="col-xs-4 control-label">Auto</label>
								<div class="col-xs-6">
									<input type="number" class="form-control" name="req_auto_limit" id="req_auto_limit" step="100000" value="{{ $property->req_auto_limit }}">
								</div>
							</div>   
						</div>

						<div class="row">
							<div class="form-group">
								<label class="col-xs-4 control-label">Workers Comp</label>
								<div class="col-xs-6">
									<input type="number" class="form-control" name="req_workerscomp_limit" id="req_workerscomp_limit" step="100000" value="{{ $property->req_workerscomp_limit }}">
								</div>
							</div>   
						</div>
						<input class="btn btn-primary" type="submit">

					</form>
				</div>
			</div>
		</div>
	</div>

	{{-- User - Update Users --}}
	<div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="UserModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="UserModalLabel">Edit Users</h4>
				</div>
				<div class="modal-body">
					<form method="POST" action="/property/user/{{$property->id}}" >
						{{ csrf_field() }}
						{{ method_field('PATCH') }}

						<div class="row">
							<div class="col-xs-8 col-xs-offset-4">
								<h4>Primary Manager</h4>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-6 col-xs-offset-3">
								<select class="form-control" id="primary_manager" name="primary_manager">
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-8 col-xs-offset-4">
								<h4>Associated Users</h4>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-2 col-xs-offset-2">
	

								<select multiple="multiple" id="property_user_multiselect" name="property_user_multiselect[]">		
						
								</select>	
							</div>
						</div>	

						<div class="row">
							<div class="col-xs-10 col-xs-offset-1">
								<hr width="75%">
							</div>
						</div>

						<div class="row">
							<div class="col-xs-2 col-xs-offset-5">
								<input class="btn btn-primary" type="submit">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	{{-- EditPropertyModal --}}
	<div class="modal fade" id="EditPropertyModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
		<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="EditModalLabel">Edit Property Information</h4>
                </div>
                <div class="modal-body">
	                <form method="POST" action="/property/{{$property->id}}/update"  enctype="multipart/form-data">
						{{ csrf_field() }}
						
	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Property ID#</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="property_system_id" id="property_system_id" value="{{ old('property_system_id') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Property Name</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="property_name" id="property_name" value="{{ old('property_name') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Address</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">City</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">State</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="state" id="state" value="{{ old('state') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Zip</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="zip" id="zip" value="{{ old('zip') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
	                    	<div class="form-group">
	                    		<label class="col-xs-4 control-label">Owner</label>
								<div class="col-xs-6">
									<select id="OwnerSelect" name="owner" class="form-control">

									</select>
								</div>
							</div>
						</div>

	                    <div class="row">
	                    	<div class="form-group">
	                    		<div class="col-xs-4 col-xs-offset-4">
		                    		<label class="radio-inline">
		                    			<input type="radio" name="active_switch" id="active" value="true">Active
		                    		</label>
		                    		<label class="radio-inline">
		                    			<input type="radio" name="active_switch" id="inactive" value="false">Inactive
		                    		</label>
	                    		</div>
	                    	</div>
	                    </div>

	                    <div class="row">
		                    <div class="col-xs-2 col-xs-offset-4">
		        				<input class="btn btn-primary" type="submit">
		        			</div>
	        			</div>
	                </form>
	            </div>
	        </div>
	    </div>		
	</div>



@endsection
