@extends ('layouts.app')

@section ('content')

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
		<div class="col-xs-4">
			<ul class="nav nav-pills nav-stacked">
				<li class="dropdown">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				    Insurance Requirements<span class="caret"></span>
				    </a>

				    <ul class="dropdown-menu" role="menu">
						<li><a href="#">Liability Single - {{ $tenant->req_liability_single_limit == 0 ? number_format($tenant->property->req_liability_single_limit).' (property)' : number_format($tenant->req_liability_single_limit) }}</a></li>
						<li><a href="#">Liability Combined - {{ $tenant->req_liability_combined_limit == 0 ? number_format($tenant->property->req_liability_combined_limit).' (property)' : number_format($tenant->req_liability_combined_limit) }}</a></li>
						<li><a href="#">Excess/Umbrella - {{ $tenant->req_umbrella_limit == 0 ? number_format($tenant->property->req_umbrella_limit).' (property)' : number_format($tenant->req_umbrella_limit) }}</a></li>
						<li><a href="#">Auto - {{ $tenant->req_auto_limit == 0 ? number_format($tenant->property->req_auto_limit).' (property)' : number_format($tenant->req_auto_limit) }}</a></li>
						<li><a href="#">Workers Comp - {{ $tenant->req_workerscomp_limit == 0 ? number_format($tenant->property->req_workerscomp_limit).' (property)' : number_format($tenant->req_workerscomp_limit) }}</a></li>
						<li><a href="#" class="open-tenant-req-insurance-modal">Edit Requirements</a></li>
					</ul>
				</li>
			</ul>		

			
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Updated - <small>		
			{{date('F d, Y, g:i a', strtotime($tenant->updated_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Ins. Notice Sent - <small>
			@if ($tenant->insurance->last_notice_sent != null)		
			{{date('F d, Y, g:i a', strtotime($tenant->insurance->last_notice_sent->timezone(Auth::user()->timezone)))}}
			@endif
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-3 col-xs-offset-3 col-md-3 col-md-offset-3">
			<button class="btn btn-primary open-edit-tenant-modal btn-xs" value="{{$tenant->id}}">Edit Tenant Information</button>
		</div>

		<div class="col-xs-3">
			
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-xs-3 col-xs-offset-3 col-md-3 col-md-offset-3">
			<button class="btn btn-primary open-manage-insurance-modal btn-xs" value="{{$tenant->insurance->id}}"}}">	Manage Insurance Information</button>
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

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3 text-center">
			<h4> Insurance </h4>
		</div>
		
	</div>

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-3">	
			<table class="table table-hover">
					<tr class="info">
						<th>Insurance Type</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Limit</th>
					</tr>
					
					<tr onclick="{{$state['llink']}}">
						<td class="{{$state['lfile']}}">Liability</td>
						<td class="{{$state['lexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->liability_start))}}</td>
						<td class="{{$state['lexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</td>
						<td class="{{$state['llimit']}}">{{number_format($tenant->Insurance->liability_single_limit)}} / {{number_format($tenant->Insurance->liability_combined_limit)}}</td>
					</tr>
					<tr onclick="{{$state['ulink']}}">
						<td class="{{$state['ufile']}}">Excess/Umbrella</td>
						<td class="{{$state['uexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->umbrella_start))}}</td>
						<td class="{{$state['uexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->umbrella_end))}}</td>
						<td class="{{$state['ulimit']}}">{{number_format($tenant->Insurance->umbrella_limit)}}</td>
					</tr>
					<tr onclick="{{$state['alink']}}">
						<td class="{{$state['afile']}}">Auto</td>
						<td class="{{$state['aexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->auto_start))}}</td>
						<td class="{{$state['aexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->auto_end))}}</td>
						<td class="{{$state['alimit']}}">{{number_format($tenant->Insurance->auto_limit)}}</td>
					</tr>
					@if ($tenant->insurance->workerscomp_applicable)
					<tr onclick="{{$state['wlink']}}">
						<td class="{{$state['wfile']}}">Workers Comp</td>
						<td class="{{$state['wexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->workerscomp_start))}}</td>
						<td class="{{$state['wexpire']}}">{{date('F d, Y', strtotime($tenant->Insurance->workerscomp_end))}}</td>
						<td class="{{$state['wlimit']}}">{{number_format($tenant->Insurance->workerscomp_limit)}}</td>
					</tr>
					@endif
					<tr onclick="{{$state['elink']}}">
						<td class="{{$state['efile']}} text-center" colspan="4" >Endorsement</td>
					</tr>
			</table>
		</div>
	</div>

	@permission('manage-wo')
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
	@endpermission

	<!-- ManageInsuranceModal (Pop up when manage button clicked) -->
	<div class="modal fade" id="ManageInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="ManageModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="ManageModalLabel">Manage Insurance Certificate</h4>
                </div>
                <div class="modal-body">
                <form method="POST" action="/insurance/{{$tenant->Insurance->id}}/update"  enctype="multipart/form-data">
					{{ csrf_field() }}
					{{ method_field('PATCH') }}

					<div class="container">
						<div class="col-lg-8 col-centered">

							@if ($tenant->Insurance->tempfile == null) 
								<input type="file" accept=".pdf" name="insurance_cert">
							@else
								<button  class="btn btn-primary btn-xs file-btn" id="vendor-invoice-btn" href="{{ $tempfileurl }}" > View Insurance Certificate </button>
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
				    			<h4>Type of Upload</h4>
				    			<div class="radio">
				    				<label>
				    					<input type="radio" name="typeSelect" value="certificate" checked>
				    					Certificate
				    				</label>
				    				<label>
				    					<input type="radio" name="typeSelect" value="endorsement">
				    					Endorsement
				    				</label>
				    			</div>
				    			<br>
				    			<h4>Type of Certificate <small>- Check all that apply</small></h4>
				    			<div class="checkbox" id="rejectnote" >
				    				<label>
				    					<input type="checkbox" name="liability" value="Y">
				    					Liability
				    				</label>
				    				<label>
				    					<input type="checkbox" name="umbrella" value="Y">
				    					Excess/Umbrella
				    				</label>
				    				<label>
				    					<input type="checkbox" name="auto" value="Y">
				    					Auto
				    				</label>
				    				<label>
				    					<input type="checkbox" name="workerscomp" value="Y">
				    					Workers Comp
				    				</label>
				    			</div>
							</div>
						</div>
					</div>
        			<div class="table-responsive" id="insurancetable">
						<table class="table table-hover">
							<tr class="info">
								<th>Insurance Type</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Limit</th>
							</tr>
							
							<tr>
								<td class="{{$state['lfile']}}">Liability</td>
								<td class="{{$state['lexpire']}}"><input type="date" name="liability_start" value="{{date('Y-m-d', strtotime($tenant->Insurance->liability_start))}}"></td>
								<td class="{{$state['lexpire']}}"><input type="date" name="liability_end" value="{{date('Y-m-d', strtotime($tenant->Insurance->liability_end))}}"></td>
								<td class="{{$state['llimit']}}"><input type="number" name="liability_single_limit" step="100000" value="{{$tenant->Insurance->liability_single_limit}}"> / <input type="number" name="liability_combined_limit" step="100000" value="{{$tenant->Insurance->liability_combined_limit}}"></td>
							</tr>
							<tr>
								<td class="{{$state['ufile']}}">Excess/Umbrella</td>
								<td class="{{$state['uexpire']}}"><input type="date" name="umbrella_start" value="{{date('Y-m-d', strtotime($tenant->Insurance->umbrella_start))}}"></td>
								<td class="{{$state['uexpire']}}"><input type="date" name="umbrella_end" value="{{date('Y-m-d', strtotime($tenant->Insurance->umbrella_end))}}"></td>
								<td class="{{$state['ulimit']}}"><input type="number" name="umbrella_limit" step="100000" value="{{$tenant->Insurance->umbrella_limit}}"></td>
							</tr>
							<tr>
								<td class="{{$state['afile']}}">Auto</td>
								<td class="{{$state['aexpire']}}"><input type="date" name="auto_start" value="{{date('Y-m-d', strtotime($tenant->Insurance->auto_start))}}"></td>
								<td class="{{$state['aexpire']}}"><input type="date" name="auto_end" value="{{date('Y-m-d', strtotime($tenant->Insurance->auto_end))}}"></td>
								<td class="{{$state['alimit']}}"><input type="number" name="auto_limit" step="100000" value="{{$tenant->Insurance->auto_limit}}"></td>
							</tr>
							<tr>
								<td class="{{$state['wfile']}}">Workers Comp</td>
								<td class="{{$state['wexpire']}}"><input type="date" name="workerscomp_start" value="{{date('Y-m-d', strtotime($tenant->Insurance->workerscomp_start))}}"></td>
								<td class="{{$state['wexpire']}}"><input type="date" name="workerscomp_end" value="{{date('Y-m-d', strtotime($tenant->Insurance->workerscomp_end))}}"></td>
								<td class="{{$state['wlimit']}}"><input type="number" name="workerscomp_limit" step="100000" value="{{$tenant->Insurance->workerscomp_limit}}"> - <input type="checkbox" id="workerscomp_applicable" name="workerscomp_applicable" value="N">
				    					Not Applicable </td>
							</tr>

						</table>
					</div>
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

						<div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Liability Single</label>
		                        <div class="col-xs-6">
		                            <input type="number" class="form-control" name="req_liability_single_limit" id="req_liability_single_limit" step="100000" value="{{ $tenant->req_liability_single_limit }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Liability Combined</label>
		                        <div class="col-xs-6">
		                            <input type="number" class="form-control" name="req_liability_combined_limit" id="req_liability_combined_limit" step="100000" value="{{ $tenant->req_liability_combined_limit }}">
		                        </div>
		                    </div>   
	                    </div>

						<div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Excess/Umbrella</label>
		                        <div class="col-xs-6">
		                            <input type="number" class="form-control" name="req_umbrella_limit" id="req_umbrella_limit" step="100000" value="{{ $tenant->req_umbrella_limit }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Auto</label>
		                        <div class="col-xs-6">
		                            <input type="number" class="form-control" name="req_auto_limit" id="req_auto_limit" step="100000" value="{{ $tenant->req_auto_limit }}">
		                        </div>
		                    </div>   
	                    </div>

	                    <div class="row">
		                    <div class="form-group">
		                        <label class="col-xs-4 control-label">Workers Comp</label>
		                        <div class="col-xs-6">
		                            <input type="number" class="form-control" name="req_workerscomp_limit" id="req_workerscomp_limit" step="100000" value="{{ $tenant->req_workerscomp_limit }}">
		                        </div>
		                    </div>   
	                    </div>
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
	                    	<div class="form-group">
	                    		<div class="col-xs-4 col-xs-offset-4">
		                    		<label class="radio-inline">
		                    			<input type="radio" name="verified_switch" id="verified" value="true">Verified
		                    		</label>
		                    		<label class="radio-inline">
		                    			<input type="radio" name="verified_switch" id="unverified" value="false">Unverified
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
