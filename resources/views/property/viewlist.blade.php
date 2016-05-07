@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Property</th>
						<th>ID</th>
					</tr>
				@foreach ($properties as $property)
					<tr onclick = "location.href='/property/{{$property->id}}'">
						<td>{{$property->name}}</td>
						<td>{{$property->property_system_id}}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection