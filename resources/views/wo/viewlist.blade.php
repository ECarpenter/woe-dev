@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Tenant</th>
						<th>Property</th>
						<th>Status</th>
					</tr>
				@foreach ($workorders as $workorder)
					<tr onclick = "location.href='/workorders/{{$workorder->id}}'">
						
						@if ($workorder->tenant_id == 0)
							<td>{{$workorder->User->company_name}}</td>
						@else
							<td>{{$workorder->Tenant->company_name}}</td>
						@endif
						<td>{{$workorder->Property()->name}}</td>
						<td>{{$workorder->status}}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection