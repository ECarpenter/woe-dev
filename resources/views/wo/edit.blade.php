@extends ('layouts.app')

@section ('content')
<p hidden>testWorkOrders1Edit</p>

	<form method="POST" action="/workorders/{{$workorder->id}}/save">
	{{ method_field('PATCH') }}
	{{ csrf_field() }}

		<div class="row">
			<div class="col-xs-4 col-xs-offset-4">
				<h4> Property - <small>
					{{$workorder->Property()->name}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> Tenant - <small>
				{{$workorder->Company_Name()}}
				</small></h4>
			</div>
			<div class="col-xs-4">
				<h4> Unit - <small>		
				{{$workorder->unit()}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> User - <small><a href="/user/{{$workorder->user->id}}">{{$workorder->user->name}}</a>
			</small>
				</small></h4>
			</div>
			<div class="col-xs-4">
				<h4> User Job Title - <small>		
				{{$workorder->User->job_title}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> User email - <small><a href="mailto:{{$workorder->User->email}}">{{$workorder->User->email}}
					</a></small></h4>
			</div>
			<div class="col-xs-4">
				<h4> Updated - <small>		
				{{date('F d, Y, g:i a', strtotime($workorder->updated_at))}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> Sent - <small>
					{{date('F d, Y, g:i a', strtotime($workorder->created_at))}}
				</small></h4>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2 col-xs-offset-2">
				<h4> Problem Type - <small>
					<select name = 'type' class="form-control">
						@foreach ($typeList as $problemType)
							<option <?php if($workorder->ProblemType->id == $problemType->id){echo("selected");}?> value={{$problemType->id}}> {{ $problemType->type }} </option>
						@endforeach
					</select>
				</small></h4>
			</div>
			<div class="col-xs-2 col-xs-offset-2">
				<h4> Status - <small>		
				<select name = 'status' class="form-control">
						
						<option <?php if($workorder->status == 'Submited'){echo("selected");}?> > Submited </option>
						<option <?php if($workorder->status == 'In Process'){echo("selected");}?> > In Process </option>
						<option <?php if($workorder->status == 'Closed'){echo("selected");}?> > Closed </option>
						
					</select>
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6 col-xs-offset-4">
				<h4> Description </h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-3 ">
				{{ $workorder->description }}
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6 col-xs-offset-4">
				<h4> Manager Notes </h4>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<textarea name='manager_notes'  class="col-xs-4 col-xs-offset-3 "class="form-control">{{ $workorder->manager_notes }}</textarea>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-2 col-xs-offset-4" class="form-group">
				<button type='submit' class="btn btn-primary">Save</button>
			</div>
		</div>
	</form>




</div>


@endsection
