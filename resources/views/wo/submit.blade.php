@extends('layouts.app')

@section('content')
<p hidden>testSubmit</p>

<div class="container">
	<form method="POST" action="/submit-tenant" enctype="multipart/form-data">
		{{csrf_field()}}
			
		{{-- Tenant Selection --}}

		@permission('manage-wo')
			<input type="hidden" name="property" value=1></input>

			<div class="row">
				<div class="col-md-6 col-md-offset-3 disclaimer text-center">
					<h5>
						{{env("DISCLAIMER", "")}}
						<br><br>
						{{env("DISCLAIMER2","")}}

					</h5>
				</div>
			</div>

			<div class="row">			
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
		    </div>
		    <input type="hidden" name="phone" value="NONE"></input>

		@endpermission
		@role('tenant')
				<input type="hidden" name="property" value={{Auth::user()->Properties()->first()->id}}></input>
			@if (Auth::user()->verified)
				<input type="hidden" name="tenant" value={{Auth::user()->tenant_id}}></input>
			@else
				<input type="hidden" name="tenant" value=0></input>
			@endif
		@endrole

		{{-- Problem Type --}}

		<div class="row">
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
		</div>

		{{-- DESCRIPTION --}}
		<div class="row">
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
		</div>
		{{-- Phone Number --}}

		@role('tenant')
		<div class="row">
			<div class="col-md-2 col-md-offset-3">
		        <h4>Phone Number</h4>
				<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
		      
		            <input type="text" class="form-control" Name="phone" value="{{ Auth::user()->phone }}">

		            @if ($errors->has('phone'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('phone') }}</strong>
		                </span>
		            @endif
		        </div>
		    </div>
		</div>

		@endrole
		{{-- Upload Supporting Document --}}

		<div class="row">
			<div class="col-md-6 col-md-offset-3">

			
				<div class="form-group{{ $errors->has('support_file') ? ' has-error' : '' }}">
				<label class="btn btn-primary btn-file">Upload Media
					<input id="support_file_upload" type="file" name="support_file" accept="image/*, audio/*, video/*, application/zip, application/pdf" style="display:none;" class="form-control" onchange="">
					</input>
				</label>
				<small id="filename_display">No File Selected</small>

				@if ($errors->has('support_file'))
		            <span class="help-block">
		                <strong>Sorry, that file type is not accepted</strong>
		            </span>
		        @endif
				</div>
			</div>
		</div>

		{{-- Urgent --}}
        
       	<div class="row">
       		<div class="col-md-3  col-md-offset-3">
       			<div class="checkbox">
					<label>
						<input type="checkbox" name="urgent" value="Y">
						Urgent
					</label>
					
				</div>
			</div>
       	</div>

		{{-- SUBMIT --}}

		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</form> 
			

</div>




@endsection