@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add Property Group
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/group/save">
                        {!! csrf_field() !!} 

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('group_system_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Group ID#</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="group_system_id" value="{{ old('group_system_id') }}">
                                @if ($errors->has('group_system_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('group_system_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-group"></i>Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
