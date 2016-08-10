
Hi,<br><br>


	
{{$workorder->Company_Name()}} has submited a new  <a href="demo.ejcustom.com/workorders/{{$workorder->id}}">work order,</a> for {{$workorder->Property()->name}}
<br> 
Description from {{$workorder->User->name}}:
<br>
{{$workorder->description}}
<br><br>
Thanks,<br><br>

WOE
