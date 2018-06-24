@extends ('layouts.app')

@section ('content')
<p hidden>testTenant1</p>
	


	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small> <a href="/property/{{$tenant->Property->id}}">
				 {{$tenant->Property->name}}</a>
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Tenant - <small>
			{{$tenant->company_name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> ID - <small>		
			{{$tenant->tenant_system_id}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> E-Mail - <small><a href="mailto:
			{{$tenant->insurance_contact_email}}"> {{$tenant->insurance_contact_email}}</a>
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> 
			@if ($tenant->active)
				Active
			@else
				Inactive
			@endif			

			</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Unit - <small>
			{{$tenant->unit}} 
			</small></h4>
		</div>
		@permission('manage-insurance')
			<div class="col-xs-4">
				<h4> Ins. Notice Sent - <small>
					@if ($tenant->insurance->last_notice_sent != null)		
						{{date('F d, Y, g:i a', strtotime($tenant->insurance->last_notice_sent->timezone(Auth::user()->timezone)))}}
					@else
						None
					@endif
				</small></h4>
			</div>
		@endpermission
	</div>

	<div class="row">
		<div class="col-xs-2 col-xs-offset-2">
			<ul class="nav nav-pills nav-stacked">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					  Users<span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
						@foreach ($tenant->user as $user)
							<li><a href="/user/{{$user->id}}">{{$user->name}}</a></li>
						@endforeach
					</ul>
				</li>
			</ul>
		</div>
		
	</div>

	<div class="row">
		<div class="col-xs-3 col-xs-offset-6 col-md-3 col-md-offset-6">
			<button class="btn btn-primary open-edit-tenant-modal btn-xs" value="{{$tenant->id}}">Edit Tenant Information</button>
		</div>

		<div class="col-xs-3">
			
		</div>
	</div>

	<br>
	@permission('manage-insurance')
		<div class="row">
			<div class="col-xs-3 col-xs-offset-3 col-md-3 col-md-offset-3">
				<button class="btn btn-primary open-manage-insurance-modal btn-xs" value="{{$tenant->insurance->id}}">	Manage Insurance Information</button>
			</div>
			<div class="col-xs-3  col-md-3 ">
				@if ($state['manual_notice'] == "valid")
				<button class="btn btn-primary btn-xs" type="button" id="btn-send-notice">	Send Insurance Notice </button>
				<input type="hidden" id="tenant_id" name="tenant_id" value={{$tenant->id}}>
				@else
				<a href="#" class="btn btn-primary btn-xs disabled" role="button">	Send Insurance Notice </a>
				@endif
			</div>
		</div>
		<div id="InformationDisplay">
			<div id="InsuranceDisplay">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3 text-center">
						<h4> Insurance </h4>
					</div>
					
				</div>

				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3">	
						<table class="table table-hover">
								<tr class="info">
									<th>Insurance Form</th>
									<th>Expiration date</th>
								</tr>
								@if ($state['llink'] != "")
								<tr onclick="{{$state['llink']}}">
									<td class="{{$state['status']}}">{{$tenant->Insurance->combined_file ?'Certificate and Evidence of Property Insurance' : 'Certificate of Liability Insurance'}}</td>
									
									<td class="{{$state['status']}}">{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
								</tr>
								@endif
								@if ($state['elink'] != "")
								<tr onclick="{{$state['elink']}}">
									<td class="{{$state['status']}}">Evidence of Commercial Property Insurance</td>
									
									<td class="{{$state['status']}}">{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
								</tr>
								@endif
								
						</table>
					</div>
				</div>
			</div>
		</div>
	@endpermission
	@permission('manage-wo')
		<div id="InformationDisplay">
			<div id="WorkorderDisplay">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3 text-center">
						<h4> Work Order </h4>
					</div>
					
				</div>

				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3">	
						<table class="table table-hover">
								<tr class="info">
									<th>Type</th>
									<th>Status</th>
									<th>Date</th>
								</tr>
								@foreach ($tenant->Workorder()->orderBy('created_at','desc')->get() as $workorder)
								<tr onclick = "location.href='/workorders/{{$workorder->id}}'">
									<td>{{$workorder->ProblemType->type}}</td>
									<td>{{$workorder->status}}</td>
									<td>{{date('F d, Y, g:i a', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</td>
								</tr>
								@endforeach
						</table>
					</div>
				</div>
			</div>	
		</div>
	@endpermission
		


	<!-- ManageInsuranceModal (Pop up when manage button clicked) -->
	<div class="modal fade" id="ManageInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="ManageModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="ManageModalLabel">Manage Insurance Certificate - {{$tenant->company_name}} ( {{$tenant->tenant_system_id}})</h4>

                </div>
                <div class="modal-body">
                <form method="POST" action="/insurance/{{$tenant->Insurance->id}}/update"  enctype="multipart/form-data">
					{{ csrf_field() }}
					{{ method_field('PATCH') }}

					<div class="container">
						<div class="col-lg-12 col-centered">

							@if ($tenant->Insurance->tempfile == null && $tenant->Insurance->tempfile2 == null) 
								<input type="file" accept=".pdf" name="insurance_cert">
								<h4>Type of Upload</h4>
				    			<div class="col-xs-3">
					    			<div class="radio">
					    				<label>
					    					<input type="radio" name="typeSelect" value="a25" checked>
					    					ACORD 25 - Certificate of Liability
					    				</label>
					    				<br>
					    				<label>
					    					<input type="radio" name="typeSelect" value="a28">
					    					ACORD 28 - Evidence of Commercial Property Insurance
					    				</label>
					    				<br>
					    				<label>
					    					<input type="radio" name="typeSelect" value="both">
					    					Both
					    				</label>
					    			</div>
					    		</div>
							@else
								@if ($tenant->Insurance->tempfile !=null)
									@if($tenant->Insurance->combined_file)
										<button  class="btn btn-primary btn-xs file-btn" id="vendor-invoice-btn" href="{{ $tempfileurl }}" > View Certificate Evidence of Insurance</button>
									@else
										<button  class="btn btn-primary btn-xs file-btn" id="vendor-invoice-btn" href="{{ $tempfileurl }}" > View Insurance Certificate </button>
									@endif
								@endif
								@if ($tenant->Insurance->tempfile2 != null)
									<button  class="btn btn-primary btn-xs file-btn" id="vendor-invoice-btn" href="{{ $tempfile2url }}" > View Evidence of Property Insurance </button>
								@endif
								<br>
								<h4>Accept tenant's upload</h4>
								<div class="radio">
									<label>
										<input type="radio" name="tenantUpload" value="accept" checked>
										Accept
									</label>
									<label>
										<input type="radio" name="tenantUpload" value="reject">
										Reject
									</label>
								</div>
								<div class="form-group" id="rejectnote" hidden>
									<label>
									<h4>Reason for Rejection</h4>
										<textarea name ="rejection_msg" class="form-control"> </textarea>
									</label>
								</div>
			    			@endif
			    			<br>
			    			<div id="insurancedata">
				    			
				    			<br>
				    			<div class="col-xs-3 {{($tenant->Insurance->tempfile == null) && ($tenant->Insurance->tempfile2 == null) ? "col-xs-offset-2" : ""}}">
				    				<label>Expiration Date</label>
		                            <input type="date" name="liability_end" id="liability_end" value="{{date('Y-m-d', strtotime($tenant->Insurance->liability_end))}}">
		                        </div>
		                        <br>
		                        <br>
		                        <br>
		                        <br>
		                        <br>
				    			<div>
				    				<label>Additional Insured: Davis Partners LLC - {{$tenant->Property->insured_name}}</label>
		                        </div>
		                        <br>
		                        <div>
		                        	<input type="checkbox" name="compliant" {{$tenant->Insurance->compliant ? "checked" : ""}}>Insurance Certificate is Compliant <br>
		                        	<input type="checkbox" name="auto_notice" {{$tenant->Insurance->auto_notice ? "checked" : ""}}>Send Automatic Expiration Notice <br>
		                        </div>
		                        <label>Notes</label>
								<br>
								<textarea name=note rows="6" cols="50">{{$tenant->Insurance->note}}</textarea>
				    			
							</div>
						</div>
					</div>

					<div class="table-responsive" id="limitstable">
						<table class="table table-hover">
			    			<tr class="info"><th>Insurance Requirements</th></tr>
			    			
			    			@if ($$insurance_requirements->req_cgl != null)
			    				<tr><td>
			    				CGL - {{$$insurance_requirements->req_cgl}} {{$$insurance_requirements->req_cgl_deductible != null ? '- Deductible - '.$$insurance_requirements->req_cgl_deductible : ''}}
			    				</td></tr>
			    			@endif
			    			@if ($$insurance_requirements->req_excess != null)
			    				<tr><td>	
			    				Excess - {{$$insurance_requirements->req_excess}} {{$$insurance_requirements->req_excess_coverage != null ? '- Coverage - '.$$insurance_requirements->req_excess_coverage : ''}}
			    				</td></tr>
			    			@endif
			    			@if ($$insurance_requirements->req_umbrella != null)
				    			<tr><td>
				    			Umbrella - {{$$insurance_requirements->req_umbrella}} {{$$insurance_requirements->req_umbrella_coverage != null ? '- Coverage - '.$$insurance_requirements->req_umbrella_coverage : ''}}
				    			</td></tr>
			    			@endif
			    			
			    			@if ($$insurance_requirements->req_cause_of_loss != null)
			    				<tr><td>
			    				Cause of Loss - {{$$insurance_requirements->req_cause_of_loss}} {{$$insurance_requirements->req_cause_of_loss_detail != null ? '- Detail - '.$$insurance_requirements->req_cause_of_loss_detail : ''}}
				    			</td></tr>
			    			@endif
			    			@if ($$insurance_requirements->req_pollution != null)
				    			<tr><td>
				    			Pollution Liability - {{$$insurance_requirements->req_pollution}} 
				    			</td></tr>
			    			@endif
			    			@if ($$insurance_requirements->req_employers_liability != null)
			    				<tr><td>
			    				Employers Liability - {{$$insurance_requirements->req_employers_liability}} 
			    				</td></tr>
			    			@endif
			    			@if ($$insurance_requirements->req_auto_liability != null)
			    				<tr><td>
			    				Auto Liability - {{$$insurance_requirements->req_auto_liability}} {{$$insurance_requirements->req_auto_liability_coverage != null ? '- Coverage - '.$$insurance_requirements->req_auto_liability_coverage : ''}}
				    			</td></tr>
			    			@endif
			    		</table>
			    	</div>
        					<div class="table-responsive" id="coveragestable">
				    			<table class="table table-hover">
									<tr class="info">
				    					<th>Rquired Coverages</th>
				    				</tr>

					    			@if ($$insurance_requirements->req_pollution_amend)
						    			<tr><td>
						    			Amendment of the Pollution Exclusion
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_additional_ins_endorsement)
						    			<tr><td>
						    			Additional Insured-Managers and Landlords of Premises Endorsement
						    			</td></tr>
					    			@endif
									@if ($$insurance_requirements->req_tenants_pp)
						    			<tr><td>
						    			Tenant's Personal Property
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_tenant_improvements)
						    			<tr><td>
						    			Tenant Improvements
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_tenant_fixtures)
						    			<tr><td>
						    			Tenant's trade fixtures and other property
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_data_endorsement)
						    			<tr><td>
						    			Endorsements to insure against lossess to valuable papers, records and computer equipment, recovering lost data
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_earthquake)
						    			<tr><td>
						    			Earthquake
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_flood)
						    			<tr><td>
						    			Flood
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_workers_comp)
						    			<tr><td>
						    			Workers Comp
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_business_interruption)
						    			<tr><td>
						    			Business Interruption
						    			</td></tr>
					    			@endif
					    			@if ($$insurance_requirements->req_waiver_of_subrogation)
						    			<tr><td>
						    			Waiver of Subrogation
						    			</td></tr>
					    			@endif
					    		</table>
				    	</div>
        			<div class="table-responsive" id="insurancetable">
						<table class="table table-hover">
							<tr class="info">
									<th>Insurance Form</th>
									<th>Expiration date</th>
								</tr>
								@if ($state['llink'] != "")
								<tr onclick="{{$state['llink']}}">
									<td class="{{$state['status']}}">{{$tenant->Insurance->combined_file ?'Certificate and Evidence of Insurance' : 'Certificate of Liability Insurance'}}</td>
									
									<td class="{{$state['status']}}">{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
								</tr>
								@endif
								@if ($state['elink'] != "")
								<tr onclick="{{$state['elink']}}">
									<td class="{{$state['status']}}">Evidence of Commercial Property Insurance</td>
									
									<td class="{{$state['status']}}">{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
								</tr>
								@endif
						</table>
					</div>
						
						<br>
        				<input class="btn btn-primary" type="submit">
        		
        				
    			</form>

               	</div>
            </div>
        </div>
    </div>

    <!-- InsuranceReq - will update the insurance requirements -->
    <div class="modal fade" id="InsReqTenantModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
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

						<input type="hidden" name="type" value="tenant">
						<input type="hidden" name="id" value="{{$tenant->id}}">

	                    <input class="btn btn-primary" type="submit">

					</form>
				</div>
			</div>
		</div>
	</div>



    <!-- EditTenantModel - will let the user edit basic tenant information -->
	<div class="modal fade" id="EditTenantModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
		<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="EditModalLabel">Edit Tenant Information</h4>
                </div>
                <div class="modal-body">
	                <form method="POST" action="/tenant/{{$tenant->id}}/update"  enctype="multipart/form-data">
						{{ csrf_field() }}
						
	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Tenant ID#</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="tenant_system_id" id="tenant_system_id" value="{{ old('tenant_system_id') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Insurance Contact</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="insurance_contact_email" id="insurance_contact_email" value="{{ old('tenant_system_id') }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group{{ $errors->has('unit') ? ' has-error' : '' }}">
		                        <label class="col-xs-4 control-label">Unit</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="unit" id="unit" value="{{ old('unit') }}">
		                            @if ($errors->has('unit'))
		                                <span class="help-block">
		                                    <strong>{{ $errors->first('unit') }}</strong>
		                                </span>
		                            @endif
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">	                     
		                    <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
		                        <label class="col-xs-4 control-label">Company Name</label>
		                        <div class="col-xs-6">
		                            <input type="text" class="form-control" name="company_name" id="company_name" value="{{ old('company_name') }}">

		                            @if ($errors->has('company_name'))
		                                <span class="help-block">
		                                    <strong>{{ $errors->first('company_name') }}</strong>
		                                </span>
		                            @endif
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
