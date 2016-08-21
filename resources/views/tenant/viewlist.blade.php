@extends('layouts.app')

@section('content')
<p hidden>testTenantList</p>

<div class="container">
	<div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h5>Refine Tenant List</h5></div>
			<div class="panel-body">
			    <form class="form-horizontal" role="form" method="POST" action="/tenant/refinelist">
			        {!! csrf_field() !!}
			        <div class="row">
				        <label for="inputPropID" class="col-xs-3 control-label">Enter Property ID</label>
				        <div class="col-xs-6">
				            <input type="text" class="form-control" name="property_system_id" placeholder="Yardi ID" value="">
				        </div>
				        <div class="col-xs-3" class="form-group">
				            <button type='submit' class="btn btn-primary btn-md">Enter</button>
				    	</div>
				    </div>
				    <div class="row">

				        <label class="radio-inline col-xs-offset-3">
							<input type="radio" name="active_selector" value="all" checked> All
						</label>
						<label class="radio-inline">
							<input type="radio" name="active_selector" value="active"{{ $active_selector == 'active' ? ' checked' : '' }}> Active
						</label>
						<label class="radio-inline">
							<input type="radio" name="active_selector" value="inactive"{{ $active_selector == 'inactive' ? ' checked' : '' }}> Inactive
						</label>
				    </div>		
			    </form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Tenant</th>
						<th>ID</th>
					</tr>

				@foreach ($tenants as $tenant)
					<tr class="{{$tenant->active ? '' : 'ejc-inactive'}}" onclick = "location.href='/tenant/{{$tenant->id}}'">
						<td>{{$tenant->company_name}}</td>
						<td>{{$tenant->tenant_system_id}}</td>
					</tr>
				@endforeach

			</table>
		</div>
	</div>
</div>
@endsection