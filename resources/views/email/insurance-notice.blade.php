
Hi,<br><br>

@if ($type == 'reject')
{{$tenant->company_name}} your insurance upload was rejected for the following reason:
{{$tenant->insurance->rejection_msg}}
@else
{{$tenant->company_name}} your insurance is out of date.
@endif

Click here to upload a new insurance certificate: <a href="{{ $link = url('upload/insurance', $token).'?tenant_system_id='.urlencode($tenant->tenant_system_id) }}"> {{ $link }} </a>

<br><br>
Thanks,<br><br>

WOE
