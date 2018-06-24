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
							<li><a href="{{$user->hasRole('manager') ? ' #' : '/user/'.$user->id}}">{{$user->name}} - {{$user->hasRole('manager') ? ' Manager' : $user->company_name}}</a></li>
						@endforeach
						<li class="button-color"><a href="#" class="open-user-property-modal" data-value="{{$property->id}}">Edit Managers</a></li>
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
		<div class="col-xs-3 col-xs-offset-2 col-md-3 col-md-offset-2">
			<button class="btn btn-primary open-property-req-insurance-modal btn-xs" value="{{$property->id}}">Property Insurance Requirements</button>
			
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
	@permission('manage-insurance')
	<div class="row">
		<div class="col-md-5 col-md-offset-2 text-center">
			<h4>Tenant Insurance</h4>
		</div>	
	</div>


	<div class="row">
		<div class="col-md-6 col-md-offset-2">	
			<table class="table table-hover insurance-table">
					<tr>
						<th>Tenant</th>
						<th>ID</th>
						<th>Expiration Date</th>
						<th>Compliant</th>
						<th>Note</th>
					</tr>

				@foreach ($tenants as $tenant)
					@if ($tenant->active)
						<tr class="success {{$tenant->Insurance->compliant ? '' : 'warning'}} {{$tenant->Insurance->expired ? 'danger' : ''}}" onclick = "location.href='/tenant/{{$tenant->id}}'">
							<td>{{$tenant->company_name}}</td>
							<td>{{$tenant->tenant_system_id}}</td>
							@if ($tenant->Insurance->liability_filename == null && $tenant->Insurance->endorsement_filename == null)
							<th class="insurance-missing" colspan="3">No Insurance Certificate{{$tenant->Insurance->last_notice_sent != null ? ' - Notice Sent: '.date('F d, Y', strtotime($tenant->Insurance->last_notice_sent)) : ''}}</th>
							@elseif ($tenant->Insurance->expired && $tenant->Insurance->last_notice_sent != null)
								<td>{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
								<th class="insurance-missing" colspan="2"> Notice Sent: {{date('F d, Y', strtotime($tenant->Insurance->last_notice_sent))}}</th>
							@else
								<td>{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
								<td>{{$tenant->Insurance->compliant ? 'Complaint' : 'Not Complaint'}}</td>
								<td>{{$tenant->Insurance->note}}</td>
							@endif
						</tr>
					@endif
				@endforeach

			</table>
		</div>
	</div>
	@endpermission
	
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
					<h4 class="modal-title" id="EditModalLabel">{{$property->name}} - Insurance Requirements</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive" id="limitstable">
						<table class="table table-hover">
			    			<tr class="info"><th>Insurance Requirements</th></tr>
			    			
			    			@if ($property->req_cgl != null)
			    				<tr><td>
			    				CGL - {{$property->req_cgl}} {{$property->req_cgl_deductible != null ? '- Deductible - '.$property->req_cgl_deductible : ''}}
			    				</td></tr>
			    			@endif
			    			@if ($property->req_excess != null)
			    				<tr><td>	
			    				Excess - {{$property->req_excess}} {{$property->req_excess_coverage != null ? '- Coverage - '.$property->req_excess_coverage : ''}}
			    				</td></tr>
			    			@endif
			    			@if ($property->req_umbrella != null)
				    			<tr><td>
				    			Umbrella - {{$property->req_umbrella}} {{$property->req_umbrella_coverage != null ? '- Coverage - '.$property->req_umbrella_coverage : ''}}
				    			<tr><td>
			    			@endif
			    			
			    			@if ($property->req_cause_of_loss != null)
			    				<tr><td>
			    				Cause of Loss - {{$property->req_cause_of_loss}} {{$property->req_cause_of_loss_detail != null ? '- Detail - '.$property->req_cause_of_loss_detail : ''}}
				    			</td></tr>
			    			@endif
			    			@if ($property->req_pollution != null)
				    			<tr><td>
				    			Pollution Liability - {{$property->req_pollution}} 
				    			</td></tr>
			    			@endif
			    			@if ($property->req_employers_liability != null)
			    				<tr><td>
			    				Employers Liability - {{$property->req_employers_liability}} 
			    				</td></tr>
			    			@endif
			    			@if ($property->req_auto_liability != null)
			    				<tr><td>
			    				Auto Liability - {{$property->req_auto_liability}} {{$property->req_auto_liability_coverage != null ? '- Coverage - '.$property->req_auto_liability_coverage : ''}}
				    			</td></tr>
			    			@endif
			    		</table>
			    	</div>
					<div class="table-responsive" id="coveragestable">
		    			<table class="table table-hover">
							<tr class="info">
		    					<th>Rquired Coverages</th>
		    				</tr>

			    			@if ($property->req_pollution_amend)
				    			<tr><td>
				    			Amendment of the Pollution Exclusion
				    			</td></tr>
			    			@endif
			    			@if ($property->req_additional_ins_endorsement)
				    			<tr><td>
				    			Additional Insured-Managers and Landlords of Premises Endorsement
				    			</td></tr>
			    			@endif
							@if ($property->req_tenants_pp)
				    			<tr><td>
				    			Tenant's Personal Property
				    			</td></tr>
			    			@endif
			    			@if ($property->req_tenant_improvements)
				    			<tr><td>
				    			Tenant Improvements
				    			</td></tr>
			    			@endif
			    			@if ($property->req_tenant_fixtures)
				    			<tr><td>
				    			Tenant's trade fixtures and other property
				    			</td></tr>
			    			@endif
			    			@if ($property->req_data_endorsement)
				    			<tr><td>
				    			Endorsements to insure against lossess to valuable papers, records and computer equipment, recovering lost data
				    			</td></tr>
			    			@endif
			    			@if ($property->req_earthquake)
				    			<tr><td>
				    			Earthquake
				    			</td></tr>
			    			@endif
			    			@if ($property->req_flood)
				    			<tr><td>
				    			Flood
				    			</td></tr>
			    			@endif
			    			@if ($property->req_workers_comp)
				    			<tr><td>
				    			Workers Comp
				    			</td></tr>
			    			@endif
			    			@if ($property->req_business_interruption)
				    			<tr><td>
				    			Business Interruption
				    			</td></tr>
			    			@endif
			    			@if ($property->req_waiver_of_subrogation)
				    			<tr><td>
				    			Waiver of Subrogation
				    			</td></tr>
			    			@endif
			    		</table>
		    		</div>
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
		                        <label class="col-xs-4 control-label">Additional Insured</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="insured_name" id="insured_name" value="{{ old('insured_name') }}">
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
