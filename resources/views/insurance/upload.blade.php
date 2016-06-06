@extends ('layouts.app')

@section ('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Upload Insurance Document</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="/upload/insurance/save">
                        {!! csrf_field() !!} 
                        <input type="hidden" name="token" value="{{$token}}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Tenant ID#</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="tenant_system_id" value="{{$_GET["tenant_system_id"]}}">
                            </div>
                        </div>

                        <div class="form-group">
                        <div class="col-md-offset-4 col-xs-offset-2">	
                        	<label class="btn btn-primary btn-file">Upload Insurance Certificate
                                <input type="file" accept=".pdf" name="insurance_cert" style="display:none;" onchange="javascript:this.form.submit();">
                            </label>
                        </div>
                            
                        </div>    
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
                        


@endsection
