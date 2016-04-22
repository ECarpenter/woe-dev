@extends ('layouts.app')

@section ('content')
	<form method="POST" action="/workorders/{{$workorder->id}}/save">
	{{ method_field('PATCH') }}
	{{ csrf_field()}}

		<div class="row">
			<div class="col-xs-4 col-xs-offset-4">
				<h4> Property - <small>
					{{$workorder->Tenant->Property->name}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> Tenant - <small>
				{{$workorder->Tenant->company_name}}
				</small></h4>
			</div>
			<div class="col-xs-4">
				<h4> Unit - <small>		
				{{$workorder->Tenant->unit}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> User - <small>
				{{$workorder->Tenant->User->name}}
				</small></h4>
			</div>
			<div class="col-xs-4">
				<h4> User Job Title - <small>		
				{{$workorder->Tenant->job_title}}
				</small></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2">
				<h4> Problem Type - <small>
				<select name = 'type' class="form-control">
						@foreach ($typeList as $problemType)
							<option <?php if($workorder->ProblemType->id == $problemType->id){echo("selected");}?> value={{$problemType->id}}> {{ $problemType->type }} </option>
						@endforeach
					</select>
				</small></h4>
			</div>
			<div class="col-xs-4">
				<h4> Status - <small>		
				<select name = 'status' class="form-control">
						
						<option <?php if($workorder->status == 'Open'){echo("selected");}?> > Open </option>
						<option <?php if($workorder->status == 'In Process'){echo("selected");}?> > In Process </option>
						<option <?php if($workorder->status == 'Done'){echo("selected");}?> > Done </option>
						<option <?php if($workorder->status == 'Canceled'){echo("selected");}?> > Canceled </option>
						
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
			<div class="form-group">
				<textarea name='description'  class="col-xs-4 col-xs-offset-3 "class="form-control">{{ $workorder->description }}</textarea>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-6 col-xs-offset-4" class="form-group">
				<button type='submit' class="btn btn-primary">Save</button>
			</div>
		</div>
	</form>
@endsection
