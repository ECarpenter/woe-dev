@extends('layouts.app')

@section('content')
<p hidden>testGroupList</p>

<div class="container">
	<div class="row">
	 
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>Property Group</th>
						<th>ID</th>
					</tr>
				@foreach ($groups as $group)
					<tr onclick = "location.href='/group/{{$group->id}}/manage'">
						<td>{{$group->name}}</td>
						<td>{{$group->group_system_id}}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection