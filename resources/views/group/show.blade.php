@extends ('layouts.app')

@section ('content')

	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small>
				 {{$group->name}}
			</small></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-2 col-xs-offset-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="dropdown">
			    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			      Managers<span class="caret"></span>
			    </a>

			    <ul class="dropdown-menu" role="menu">
			        @foreach ($group->properties as $property)
			        	@foreach ($property->Managers() as $manager)
			        	<li><a href="#">{{$manager->name}}</a></li>
			        	@endforeach
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
		<div class="col-md-5 col-md-offset-3 text-center">
			<h4> Work Orders </h4>
		</div>
		
	</div>

	<div class="row">
		<div class="col-md-5 col-md-offset-3">	
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



@endsection
