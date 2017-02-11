@extends('layouts.app')

@section('content')
<p hidden>testPropertyList</p>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Property</th>
						<th>ID</th>
						<th>Active/Inactive</th>
					</tr>
				@foreach ($properties as $property)
					<tr>
						<td onclick = "location.href='/property/{{$property->id}}'">{{$property->name}}</td>
						<td onclick = "location.href='/property/{{$property->id}}'">{{$property->property_system_id}}</td>
						<td class = "{{$property->active ? 'success' : 'danger'}}" onclick = "location.href='/property/changeactive/{{$property->id}}'">{{$property->active ? 'Active' : 'Inactive'}} </td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection