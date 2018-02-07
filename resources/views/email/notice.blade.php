
@if ($workorder->urgent)
-----URGENT-----
<br><br> 
@endif
	
{{$workorder->Company_Name()}} has submited a new  <a href="{{env('APP_URL')}}/workorders/{{$workorder->id}}">work order,</a> for {{$workorder->Property()->name}}
<br> <br>
Problem Type : {{$workorder->ProblemType->type}}
<br><br>
Description from {{$workorder->User->name}}:
<br>
{{$workorder->description}}
<br><br>
Contact Phone : {{$workorder->user->phone}}
<br><br>

@if ($workorder->urgent)
-----URGENT-----
<br><br> 
@endif
	

