@extends ('layouts.app')

@section ('content')
<p hidden>testWorkOrders1</p>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small> 
				{{$workorder->Property()->name}}
			</small></h4>
		</div>
	</div>

	<div class="row">

		@if($workorder->support_file == NULL)
		<div class="col-xs-2 col-xs-offset-2">
			<button  class="btn btn-default btn-xs" id="vendor-invoice-btn" disabled = 'disabled'> No File </button>
		</div>
		@else
		<div class="col-xs-2 col-xs-offset-2">
			<button  class="btn btn-info btn-xs file-btn" id="vendor-invoice-btn" href="{{ Helper::getS3URL(SUPPORT_PATH.$workorder->support_file) }}" > {{substr($workorder->support_file, 14)}} </button>
		</div>
		@endif

	

		@if($workorder->tenant_invoice_filename == NULL)
		<div class="col-xs-2 col-xs-offset-3">
			<button  class="btn btn-default btn-xs" id="tenant-invoice-btn" disabled = 'disabled'> Invoice </button>
		</div>
		@else
		<div class="col-xs-2 col-xs-offset-3">
			<button  class="btn btn-info btn-xs file-btn" id="tenant-invoice-btn" href="{{ asset($workorder->tenant_invoice_filename) }}" > Invoice </button>
		</div>
		@endif
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			
			@if ($workorder->tenant_id == 0)
				<h4> Tenant - <small>
				{{$workorder->Company_Name()}} - Not Verfied
			@else
				<h4> Tenant - <small>
				{{$workorder->Company_Name()}}
			@endif
				</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Unit - <small>	
			{{$workorder->Unit()}}
			
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Sent Workorder - <small>{{$workorder->user->name}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> e-mail - <small><a href="mailto:{{$workorder->User->email}}">{{$workorder->User->email}}</a>
			
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Sent - <small>
			{{date('F d, Y, g:i a', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Updated - <small>		
			{{date('F d, Y, g:i a', strtotime($workorder->updated_at->timezone(Auth::user()->timezone)))}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> Problem Type - <small>
			{{$workorder->ProblemType->type}}
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Status - <small>		
			{{$workorder->status}}
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6 col-xs-offset-4">
			<h4> Description </h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-xs-offset-2">
			<h4><small>{{$workorder->description}}</small> </h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-xs-offset-4">
			<h4> Manager's Comments </h4>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-xs-offset-2">
			<h4><small>{{$workorder->manager_notes}}</small> </h4>
		</div>
	</div>

	<br>




@endsection
