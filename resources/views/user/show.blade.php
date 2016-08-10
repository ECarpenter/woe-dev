@extends ('layouts.app')

@section ('content')
	
	


	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small> <a href="/property/{{$user->Property()->id}}">
				 {{$user->Property()->name}}</a>
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Tenant - <small>
			{{$user->company_name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> ID - <small>		
			{{$user->tenant->tenant_system_id}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> User Name - <small>
			{{$user->name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Job Title - <small>		
			{{$user->job_title}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> User E-Mail - <small><a href="mailto:
			{{$user->email}}"> {{$user->email}}</a>
			</small></h4>
		</div>
		
	</div>

	@permission('manage-wo')
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
							@foreach ($user->tenant->Workorder()->orderBy('created_at','desc')->get() as $workorder)
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


@endsection