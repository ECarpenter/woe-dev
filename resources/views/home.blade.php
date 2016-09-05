@extends('layouts.app')

@section('content')
<p hidden>testHome</p>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"></div>

                <div class="panel-body">
                    
      
                    @permission('manage-wo')
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">  
                                <table class="table table-hover">

                                        <tr>
                                            <th>Tenant</th>
                                            <th>Property</th>
                                            <th>Status</th>
                                        </tr>
                                    @foreach ($workorders as $workorder)
                                        <tr onclick = "location.href='/workorders/{{$workorder->id}}'">
                                            
                                            @if ($workorder->tenant_id == 0)
                                                <td>{{$workorder->User->company_name}}</td>
                                            @else
                                                <td>{{$workorder->Tenant->company_name}}</td>
                                            @endif
                                            <td>{{$workorder->Property()->name}}</td>
                                            <td>{{$workorder->status}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endpermission
                    @role('tenant')
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">  
                                <table class="table table-hover">

                                        <tr>
                                            <th>Date</th>
                                            <th>Problem Type</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    @foreach ($workorders as $workorder)
                                        <tr onclick = "location.href='/workorders-tenant/{{$workorder->id}}'">
                                            
                                            <td>{{date('F d, Y, g:i a', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</td>
                                            <td>{{$workorder->ProblemType->type}}</td>
                                            <td>{{$workorder->description}}</td>
                                            <td>{{$workorder->status}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
