
@if ($workorder->urgent)
-----URGENT-----
<br><br> 
@endif
	
The Property Manager has responded to your<a href="{{env('APP_URL')}}/workorders-tenant/{{$workorder->id}}">work order request,</a> for {{$workorder->Property()->name}}
<br> <br>
Problem Type : {{$workorder->ProblemType->type}}
<br><br>
Description from {{$workorder->User->name}}:
<br>
{{$workorder->description}}
<br><br>
Manager's Response : {{$workorder->manager_notes}}
<br><br>

@if ($workorder->urgent)
-----URGENT-----
<br><br> 
@endif
	

