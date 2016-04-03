@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<form method="POST" action="/submit/">
			{{csrf_field()}}
			
				<div class="col-md-6 col-md-offset-3">	
				
					<select class="form-control">
						@foreach ($problemTypes as $problemType)
							<option>{{ $problemType->type }}</option>
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

			</div>		
		</form> 
	</div>
</div>




@endsection