Thank you for using our Work order system. The recent <a href="{{env('APP_URL')}}/workorders/{{$workorder->id}}">work order,</a> for {{$workorder->Property()->name}} that you sent has been completed. If you have any further issues please submit a followup message <a href="{{env('APP_URL')}}/workorders/{{$workorder->id}}">here.</a> 

Thank you,

{{Auth::user()->name}}

