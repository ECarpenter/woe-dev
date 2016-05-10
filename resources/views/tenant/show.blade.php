@extends ('layouts.app')

@section ('content')

	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small>
				 {{$tenant->Property->name}}
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
			<h4> Unit - <small>
			{{$tenant->unit}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> 
			@if ($tenant->active)
				Active
			@else
				Inactive
			@endif
			 / 
			@if ($tenant->verified)
				Verified
			@else
				Unverified
			@endif

			</h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Created - <small>
			{{date('F d, Y, g:i a', strtotime($tenant->created_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Updated - <small>		
			{{date('F d, Y, g:i a', strtotime($tenant->updated_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-2 col-xs-offset-1 col-md-2 col-md-offset-2">
			<button class="btn btn-primary open-upload-insurance-modal" value="{{$tenant->id}}">Upload Insurance Certificate</button>
		</div>
	</div>

	<div class="row">
		<div class="col-md-5 col-md-offset-3 text-center">
			<h4> Insurance </h4>
		</div>
		
	</div>

	<div class="row">
		<div class="col-md-5 col-md-offset-3">	
			<table class="table table-hover">
					<tr class="info">
						<th>Insurance Type</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Limit</th>
					</tr>
					
					<tr onclick="window.open('/{{$tenant->Insurance->liability_filename}}')">
						<th>Liability</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->liability_start))}}</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->liability_end))}}</th>
						<th>{{$tenant->Insurance->liability_single_limit}} / {{$tenant->Insurance->liability_combined_limit}}</th>
					</tr>
					<tr onclick="window.open('/{{$tenant->Insurance->umbrella_filename}}')">
						<th>Umbrella</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->umbrella_start))}}</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->umbrella_end))}}</th>
						<th>{{($tenant->Insurance->umbrella_limit)}}</th>
					</tr>
					<tr onclick="window.open('/{{$tenant->Insurance->auto_filename}}')">
						<th>Auto</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->auto_start))}}</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->auto_end))}}</th>
						<th>{{($tenant->Insurance->auto_limit)}}</th>
					</tr>
					<tr onclick="window.open('/{{$tenant->Insurance->workerscomp_filename}}')">
						<th>Workers Comp</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->workerscomp_start))}}</th>
						<th>{{date('F d, Y', strtotime($tenant->Insurance->workerscomp_end))}}</th>
						<th>{{($tenant->Insurance->workerscomp_limit)}}</th>
					</tr>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-md-5 col-md-offset-3 text-center">
			<h4> Work Order </h4>
		</div>
		
	</div>

	<div class="row">
		<div class="col-md-5 col-md-offset-3">	
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


	<!-- UploadModal (Pop up when upload button clicked) -->
	<div class="modal fade" id="UploadInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="UploadModalLabel" aria-hidden="true">
		<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="UploadModalLabel">Upload Insurance Certificate</h4>
                </div>
                <div class="modal-body">
                <form method="POST" action="/tenant/{{$tenant->id}}/upload"  enctype="multipart/form-data">
					{{ csrf_field() }}

        			<input type="file" accept=".pdf" name="insurance_cert">
        			<br>
        			<h4>Check all that apply</h4>
        			<div class="checkbox">
        				<label>
        					<input type="checkbox" name="liability" value="Y">
        					Liability
        				</label>
        				<label>
        					<input type="checkbox" name="umbrella" value="Y">
        					Umbrella
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
        			<input class="btn btn-primary" type="submit">
        				
    			</form>
               	</div>
            </div>
        </div>
    </div>



@endsection
