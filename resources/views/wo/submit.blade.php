@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<form method="POST" action="/submit-tenant">
			{{csrf_field()}}
			<div class="col-md-6 col-md-offset-3">	
				@permission('manage-wo')
					<h4>Tenant</h4>
					<select name = 'tenant' class="form-control" placeholder='Select One'>
						<option value=''>Select One</option>
						@foreach ($tenants as $tenant)
							<option value={{$tenant->id}}> {{ $tenant->company_name }} </option>
						@endforeach
					</select>
					


				@endpermission

				
					<h4>Problem Type</h4>
					<select name = 'type' class="form-control">
						<option value=''>Select One</option>
						@foreach ($problemTypes as $problemType)
							<option value={{$problemType->id}}> {{ $problemType->type }} </option>
						@endforeach
					</select>
				
					<h4>Describe your problem . . .</h4>
					
					<div class="form-group">
						<textarea name = 'description' class="form-control"></textarea>
					</div>
					
					<div class="form-group">
						<button type='submit' class="btn btn primary">Submit</button>
					</div>

			</div>		
		</form> 
			@if (count($errors))
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach

				</ul>

			@endif

	</div>
</div>




@endsection