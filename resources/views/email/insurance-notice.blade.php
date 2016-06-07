
Hi,<br><br>

{{$tenant->company_name}}
@if ($type == 'reject')
 your insurance upload was rejected for the following reason:<br>
{{$tenant->insurance->rejection_msg}}
@else
 your insurance is out of date.
@endif
<br>
<br>
Click here to upload a new insurance certificate: <a href="{{ $link = url('upload/insurance', $token).'?tenant_system_id='.urlencode($tenant->tenant_system_id) }}"> {{ $link }} </a>

<br><br>
Thanks,<br><br>

WOE
