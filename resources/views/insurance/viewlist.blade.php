@extends('layouts.app')

@section('content')
<p hidden>testTenantLists</p>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Tenant</th>
						<th>ID</th>
					</tr>
				@foreach ($tenants as $tenant)
					<tr onclick = "location.href='/tenant/{{$tenant->id}}'">
						<td>{{$tenant->company_name}}</td>
						<td>{{$tenant->tenant_system_id}}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection