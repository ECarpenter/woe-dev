@extends ('layouts.app')

@section ('content')
<p hidden>testWorkOrders1</p>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-4">
			<h4> Property - <small> <a href="/property/{{$workorder->Property()->id}}">
				{{$workorder->Property()->name}}</a>
			</small></h4>
		</div>
	</div>

	<div class="row">
		@if($workorder->vendor_invoice_filename == NULL)
		<div class="col-xs-2 col-xs-offset-2">
			<button  class="btn btn-default btn-xs" id="vendor-invoice-btn" disabled = 'disabled'> Vendor Invoice </button>
		</div>
		@else
		<div class="col-xs-2 col-xs-offset-2">
			<button  class="btn btn-info btn-xs file-btn" id="vendor-invoice-btn" href="{{ asset($workorder->vendor_invoice_filename) }}" > Vendor Invoice </button>
		</div>
		@endif

		@if($workorder->cos_filename == NULL)
		<div class="col-xs-1 col-xs-offset-1">
				<button  class="btn btn-default file-btn btn-xs" id="cos-btn" disabled = 'disabled' > COS </button>
		</div>
		@else
		<div class="col-xs-1 col-xs-offset-1">
			<button class="btn btn-info file-btn btn-xs" id="cos-btn"
			href="{{ asset($workorder->cos_filename) }}" >  COS </button>
		</div> 
		@endif

		@if($workorder->tenant_invoice_filename == NULL)
		<div class="col-xs-2 col-xs-offset-1">
			<button  class="btn btn-default btn-xs" id="tenant-invoice-btn" disabled = 'disabled'> Tenant Invoice </button>
		</div>
		@else
		<div class="col-xs-2 col-xs-offset-1">
			<button  class="btn btn-info btn-xs file-btn" id="tenant-invoice-btn" href="{{ asset($workorder->tenant_invoice_filename) }}" > Tenant Invoice </button>
		</div>
		@endif
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			
			@if ($workorder->tenant_id == 0)
				<h4> Tenant - <small><a href="#">
				{{$workorder->Company_Name()}} - Not Verfied
			@else
				<h4> Tenant - <small><a href="/tenant/{{$workorder->Tenant->id}}">
				{{$workorder->Company_Name()}}
			@endif
			</a></small></h4>
		</div>
		<div class="col-xs-4">
			<h4> Unit - <small>	
			{{$workorder->Unit()}}
			
			</small></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 col-xs-offset-2">
			<h4> User - <small><a href="/user/{{$workorder->user->id}}">{{$workorder->user->name}}</a>
			</small></h4>
		</div>
		<div class="col-xs-4">
			<h4> User email - <small><a href="mailto:{{$workorder->User->email}}">{{$workorder->User->email}}</a>		
			
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
	<br>

	<div class="row">
		<div class="col-xs-2 col-xs-offset-1 col-md-2 col-md-offset-2">
			<button class="btn btn-primary open-upload-invoice-modal" value="{{$workorder->id}}">Upload Invoice</button>
		</div>

		<div class="col-xs-2 col-xs-offset-2 col-md-2 col-md-offset-1">
			<a class="btn btn-primary" href="/workorders/{{$workorder->id}}/edit" role="button" >Edit</a>
		</div>

		<div class="col-xs-2 ">
			@if ($workorder->tenant_id == 0)
			<button class="btn btn-primary open-billing-modal" value="{{$workorder->id}}" disabled>Billing</button>
			@else
			<button class="btn btn-primary open-billing-modal" value="{{$workorder->id}}">Billing</button>
			@endif
		</div>

	</div>


<!-- BillingModal (Pop up when billing button clicked) -->
	<div class="modal fade" id="BillingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Billing Info</h4>
                </div>
                <div class="modal-body">

                	<form id="billingform" name="billingform" class="form-horizontal" novalidate="">

                        <div class="form-group error">
                            <label for="inputJobCost" class="col-xs-3 control-label">Job Cost</label>
                            <div class="col-xs-9">
                                <input type="number" class="form-control has-error" id="job_cost" step="0.01" placeholder="0.00" value="">
                            </div>
                            <label for="inputAmountBilled" class="col-xs-3 control-label">Amount to Bill</label>
                            <div class="col-xs-9">
                                <input type="number" class="form-control has-error" id="amount_billed" step="0.01" placeholder="0.00" value="">
                            </div>  
                            <label for="inputBillingDescription" class="col-xs-3 control-label">Billing Description</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control has-error" id="billing_description"  placeholder="Billing Description" value="">
                            </div>  
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                	@if (!$workorder->billed)
                    	<button type="button" class="btn btn-primary" id="btn-bill">Bill Tenant</button>
                    @else
                    	<button type="button" class="btn btn-primary" disabled="disabled" id="btn-bill">Tenant Billed</button>
                    @endif
                    <input type="hidden" id="wo_id" name="wo_id" value="0">
                    <meta name="_token" content="{{ csrf_token() }}">
                    <meta name="app-url" content="http://localhost:8000">
                </div>
            </div>
        </div>
    </div>

    <!-- UploadModal (Pop up when upload button clicked) -->
	<div class="modal fade" id="UploadInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="UploadModalLabel" aria-hidden="true">
		<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="UploadModalLabel">Upload File</h4>
                </div>
                <div class="modal-body">
                <form method="POST" action="/workorders/{{$workorder->id}}/upload"  enctype="multipart/form-data">
					{{ csrf_field() }}

        			<input type="file" accept=".pdf" name="vendorinvoice">
        			<br>
        			<input class="btn btn-primary" type="submit">
        				
    			</form>
               	</div>
            </div>
        </div>
    </div>



@endsection
