
@if ($workorder->urgent)
-----URGENT-----
<br><br> 
@endif
	
There is a new message about a <a href="{{env('APP_URL')}}/workorders-tenant/{{$workorder->id}}">work order request,</a> for {{$workorder->Property()->name}}
<br> <br>
Problem Type : {{$workorder->ProblemType->type}}
<br><br>
Description from {{$workorder->User->name}}:
<br>
{{$workorder->description}}
<br><br>
New Message : {{$post->message}}
<br>
From : {{$post->User->name}}
<br><br>

@if ($workorder->urgent)
-----URGENT-----
<br><br> 
@endif
	

