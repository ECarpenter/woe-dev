@extends('layouts.app')

@section('content')
<p hidden>testPropertyAdd</p>

<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add Property</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="/property/save">
						{!! csrf_field() !!} 

						<div class="form-group{{ $errors->has('owner') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Owner*</label>

							<div class="col-md-6">
							<select name = 'owner_id' class="form-control">
								<option value=''>Select One</option>
								@foreach ($owners as $owner)
									<option value={{$owner->id}}> {{ $owner->name }} </option>
								@endforeach
							</select>

							
								@if ($errors->has('owner'))
									<span class="help-block">
										<strong>{{ $errors->first('owner') }}</strong>
									</span>
								@endif
							</div>
						</div> 

						<div class="form-group{{ $errors->has('manager') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Manager*</label>

							<div class="col-md-6">
							<select name = 'manager' class="form-control">
								<option value=''>Select One</option>
								@foreach ($managers as $manager)
									<option value={{$manager->id}}> {{ $manager->name }} </option>
								@endforeach
							</select>

							
								@if ($errors->has('manager'))
									<span class="help-block">
										<strong>{{ $errors->first('manager') }}</strong>
									</span>
								@endif
							</div>
						</div> 

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Property Name</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
								@if ($errors->has('name'))
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>
						</div> 

						<div class="form-group{{ $errors->has('property_system_id') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Property ID#</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="property_system_id" value="{{ old('property_system_id') }}">
								@if ($errors->has('property_system_id'))
									<span class="help-block">
										<strong>{{ $errors->first('property_system_id') }}</strong>
									</span>
								@endif
							</div>
						</div>                       

						<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Address*</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="address" value="{{ old('address') }}">

								@if ($errors->has('address'))
									<span class="help-block">
										<strong>{{ $errors->first('address') }}</strong>
									</span>
								@endif
							</div>
						</div>                     
						 
						<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">City*</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="city" value="{{ old('city') }}">

								@if ($errors->has('city'))
									<span class="help-block">
										<strong>{{ $errors->first('city') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">State*</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="state" value="{{ old('state') }}">

								@if ($errors->has('state'))
									<span class="help-block">
										<strong>{{ $errors->first('state') }}</strong>
									</span>
								@endif
							</div>
						</div>    

						<div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Zip*</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="zip" value="{{ old('zip') }}">

								@if ($errors->has('zip'))
									<span class="help-block">
										<strong>{{ $errors->first('zip') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('req_liability_single_limit') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Liability Single Limit*</label>

							<div class="col-md-6">
								<input type="number" class="form-control" name="req_liability_single_limit" step="100000" value="{{ old('req_liability_single_limit') }}">

								@if ($errors->has('req_liability_single_limit'))
									<span class="help-block">
										<strong>{{ $errors->first('req_liability_single_limit') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('req_liability_combined_limit') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Liability Combined Limit*</label>

							<div class="col-md-6">
								<input type="number" class="form-control" name="req_liability_combined_limit" step="100000" value="{{ old('req_liability_combined_limit') }}">

								@if ($errors->has('req_liability_combined_limit'))
									<span class="help-block">
										<strong>{{ $errors->first('req_liability_combined_limit') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('req_auto_limit') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Auto Limit*</label>

							<div class="col-md-6">
								<input type="number" class="form-control" name="req_auto_limit" step="100000" value="{{ old('req_auto_limit') }}">

								@if ($errors->has('req_auto_limit'))
									<span class="help-block">
										<strong>{{ $errors->first('req_auto_limit') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('req_umbrella_limit') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Umbrella Limit*</label>

							<div class="col-md-6">
								<input type="number" class="form-control" name="req_umbrella_limit" step="100000" value="{{ old('req_umbrella_limit') }}">

								@if ($errors->has('req_umbrella_limit'))
									<span class="help-block">
										<strong>{{ $errors->first('req_umbrella_limit') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('req_workerscomp_limit') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Workers Comp Limit*</label>

							<div class="col-md-6">
								<input type="number" class="form-control" name="req_workerscomp_limit" step="100000" value="{{ old('req_workerscomp_limit') }}">

								@if ($errors->has('req_workerscomp_limit'))
									<span class="help-block">
										<strong>{{ $errors->first('req_workerscomp_limit') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-btn fa-user"></i>Submit
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
