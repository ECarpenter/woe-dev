@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if ($passwordchanged)
                        Success! Your password has been changed.
                    @else
                        Something went wrong, your password was not changed.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
