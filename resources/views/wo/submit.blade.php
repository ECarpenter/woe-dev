@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<form method="POST" action="/submit-tenant">
			{{csrf_field()}}
			<div class="col-md-6 col-md-offset-3">	
				@role('admin','manager','accountant')
					<select name = 'tenant' class="form-control">
						@foreach ($tenants as $tenant)
							<option value={{$tenant->id}}> {{ $tenant->company_name }} </option>
						@endforeach
					</select>
					<hr>


				@endrole

				
				
					<select name = 'type' class="form-control">
						@foreach ($problemTypes as $problemType)
							<option value={{$problemType->id}}> {{ $problemType->type }} </option>
						@endforeach
					</select>
				
					<hr>
					
					<div class="form-group">
						<textarea name = 'description' class="form-control">Describe your problem . . .</textarea>
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