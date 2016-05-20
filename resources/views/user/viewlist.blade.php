@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">	
			<table class="table table-hover">
					<tr>
						<th>User</th>
						<th>E-Mail</th>
					</tr>
				@foreach ($users as $user)
					@if ($user->hasRole('admin'))
					<tr onclick = "location.href='/user/{{$user->id}}'">
						<td>{{$user->name}}</td>
						<td>{{$user->email}}</td>
					</tr>
					@endif
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection