@extends ('layouts.app')

@section ('content')
	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small>
				{{$workorder->Tenant->Property->name}}
			</small></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Tenant - <small>
			{{$workorder->Tenant->company_name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Unit - <small>		
			{{$workorder->Tenant->unit}}
			</small></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> User - <small>
			{{$workorder->Tenant->User->name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> User Job Title - <small>		
			{{$workorder->Tenant->job_title}}
			</small></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Problem Type - <small>
			{{$workorder->ProblemType->type}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Status - <small>		
			{{$workorder->status}}
			</small></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-xs-offset-4">
			<h4> Description </h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-xs-offset-2">
			<h4><small>{{$workorder->description}}</small> </h4>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-xs-2 col-xs-offset-5">
			<a class="btn btn-primary" href="/workorders/{{$workorder->id}}/edit" role="button">Edit</a>
		</div>
	</div>
@endsection
