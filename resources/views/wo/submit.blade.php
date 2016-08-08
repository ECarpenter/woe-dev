@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<form method="POST" action="/submit-tenant">
			{{csrf_field()}}
				
			@permission('manage-wo')
				<input type="hidden" name="property" value=1></input>
				
				<div class="col-md-6 col-md-offset-3">
					<h4>Tenant</h4>
					<div class="form-group{{ $errors->has('tenant') ? ' has-error' : '' }}">
						<select name = 'tenant' class="form-control{{ $errors->has('tenant') ? ' has-error' : '' }}">
							<option value=''>Select One</option>
							@foreach ($tenants as $tenant)
								<option value={{$tenant->id}}> {{ $tenant->company_name }} </option>
							@endforeach
						</select>
						
						@if ($errors->has('tenant'))
			                <span class="help-block">
			                    <strong>{{ $errors->first('tenant') }}</strong>
			                </span>
			            @endif
			        </div>
		        </div>
			@endpermission
			@role('tenant')
					<input type="hidden" name="property" value={{Auth::user()->Properties()->first()->id}}></input>
				@if (Auth::user()->verified)
					<input type="hidden" name="tenant" value={{Auth::user()->Tenant()->first()->id}}></input>
				@else
					<input type="hidden" name="tenant" value=0></input>
				@endif
			@endrole

			<div class="col-md-6 col-md-offset-3">

			<h4>Problem Type</h4>
				<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
				<select name = 'type' class="form-control">
					<option value=''>Select One</option>
					@foreach ($problemTypes as $problemType)
						<option value={{$problemType->id}}> {{ $problemType->type }} </option>
					@endforeach
				</select>
				@if ($errors->has('type'))
		            <span class="help-block">
		                <strong>{{ $errors->first('type') }}</strong>
		            </span>
		        @endif
				</div>
			</div>
			<div class="col-md-6 col-md-offset-3">
				<h4>Describe your problem . . .</h4>
				
				<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
					<textarea name ='description' class="form-control"></textarea>
				

					@if ($errors->has('description'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('description') }}</strong>
		                </span>
		            @endif
	            </div>
			</div>
			<div class="col-md-6 col-md-offset-3">
				<div class="form-group">
					<button type='submit' class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form> 
			

	</div>
</div>




@endsection