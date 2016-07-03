@extends ('layouts.app')

@section ('content')

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
						<li><a href="#">{{$user->name}} - {{$user->hasRole('manager') ? ' Manager' : ''}}</a></li>
					@endforeach
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
							<li><a href="#" class="open-property-req-insurance-modal" value="{{$property}}">Edit Requirements</a></li>
					</ul>
				</li>
			</ul>
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

	<!-- InsuranceReq - will update the insurance requirements -->
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


@endsection
