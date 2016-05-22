@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> {{$group->name}} - <small> 
				 {{$group->group_system_id}}</a>
			</small></h4>
		</div>
	</div>
	<div class="row">
		<form class="form-horizontal" role="form" method="POST" action="/group/{{$group->id}}/update">
            {!! csrf_field() !!} 
            {!! method_field('PATCH') !!}

			<div class="form-group">
				<div class="col-xs-3 col-xs-offset-2">
					<input type="text" class="form-control" name="property_system_id" placeholder="Property ID">
				</div>
				<div class="col-xs-2 col-xs-offset-1">
					<button type="submit" class="btn btn-primary">Add</button>
				</div>  
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-2">
			<form class="form-horizontal" role="form" method="POST" action="/group/{{$group->id}}/remove">
				{!! csrf_field() !!}
				{!! method_field('PATCH') !!}	
				<table class="table table-hover">
						<tr>
							<th>Property</th>
							<th>ID</th>
							<th></th>
						</tr>
					@foreach ($group->Property()->orderBy('property_system_id')->get() as $property)
						<tr>
							<td onclick = "location.href='/property/{{$property->id}}'">{{$property->name}}</td>
							<td onclick = "location.href='/property/{{$property->id}}'">{{$property->property_system_id}}</td>
							<td><button type="submit" class="btn-xs btn-primary" name="prop_id" value="{{$property->id}}">Remove</td>
						</tr>
					@endforeach
				</table>
			</form>
		</div>
	</div>

</div>



@endsection
