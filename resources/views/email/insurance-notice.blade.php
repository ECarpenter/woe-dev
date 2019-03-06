<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 14">
<meta name=Originator content="Microsoft Word 14">
<link rel=File-List href="InsuranceSignature_files/filelist.xml">
<link rel=Edit-Time-Data href="InsuranceSignature_files/editdata.mso">

<link rel=themeData href="InsuranceSignature_files/themedata.thmx">
<link rel=colorSchemeMapping
href="InsuranceSignature_files/colorschememapping.xml">

<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-536870145 1073786111 1 0 415 0;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-520081665 -1073717157 41 0 66047 0;}
@font-face
	{font-family:"Palatino Linotype";
	panose-1:2 4 5 2 5 5 5 3 3 4;
	mso-font-charset:0;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-536870265 1073741843 0 0 415 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:10.0pt;
	margin-left:0in;
	line-height:115%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:minor-fareast;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-link:"Balloon Text Char";
	margin:0in;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:8.0pt;
	font-family:"Tahoma","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:minor-fareast;}
span.BalloonTextChar
	{mso-style-name:"Balloon Text Char";
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:"Balloon Text";
	mso-ansi-font-size:8.0pt;
	mso-bidi-font-size:8.0pt;
	font-family:"Tahoma","sans-serif";
	mso-ascii-font-family:Tahoma;
	mso-hansi-font-family:Tahoma;
	mso-bidi-font-family:Tahoma;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:minor-fareast;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;}
.MsoPapDefault
	{mso-style-type:export-only;
	margin-bottom:10.0pt;
	line-height:115%;}
@page WordSection1
	{size:8.5in 11.0in;
	margin:1.0in 1.25in 1.0in 1.25in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
-->
</style>

</head>

<body lang=EN-US style='tab-interval:.5in'>

{{$tenant->company_name}} <br>
{{$tenant->property->name}}<br>
{{$tenant->property->address}}<br>
{{$tenant->property->city}} {{$tenant->property->state}}, {{$tenant->property->zip}}<br> <br>
Hi,<br><br>

@if ($type == 'reject')
 your insurance upload was rejected for the following reason:<br>
{{$tenant->insurance->rejection_msg}}

Please upload a corrected certificate. 
@else

Our records indicate that we do not have a current Certificate of Insurance for the above referenced premises on file.  Please note that we must receive a current Certificate of Insurance within the next five (5) business days evidencing proof of the required coverage and amount outlined in your Lease.  In order to expedite the process, we suggest submitting the insurance section of your lease to your insurance agent, and ask for both an ACORD 25 and ACORD 28 form from them.  Said Certificate must include {{($tenant->property->insured_name != null) ? $tenant->property->insured_name.' and ' : ''}}Davis Property Management, Inc. as additional insured.  Please reference the premise address and suite number on the certificate.    

@endif
<br>
<br>
Click here to upload a new insurance certificate: <a href="{{ $link = url('upload/insurance', $token).'?tenant_system_id='.urlencode($tenant->tenant_system_id) }}"> {{ $link }} </a>

<br><br>
Thank you,<br><br>


<div class=WordSection1>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
style='font-family:"Arial","sans-serif";color:black'>Insurance Administrator</span></b><span
style='font-size:8.0pt;mso-bidi-font-size:11.0pt;line-height:115%;font-family:
"Arial","sans-serif";color:#6D6E71'><br>
</span><span style='font-size:8.0pt;mso-bidi-font-size:11.0pt;line-height:115%;
font-family:"Arial","sans-serif";color:black'>949.752.2066 off<br>
insurance@davispartners.com<br>
1420 BRISTOL STREET NORTH, #100, NEWPORT BEACH, CA 92660<o:p></o:p></span></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
style='font-size:10.0pt;mso-bidi-font-size:11.0pt;line-height:115%;font-family:
"Palatino Linotype","serif";color:navy;mso-no-proof:yes'>

<img width=816 height=124 src="{{ $message->embed(public_path()."/InsuranceSignature_files/image001.jpg")}}" v:shapes="Picture_x0020_1"></span></b><b
style=mso-bidi-font-weight:normal'><span style='font-size:10.0pt;mso-bidi-font-size:
11.0pt;line-height:115%;font-family:"Palatino Linotype","serif";color:navy'><o:p></o:p></span></b></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
style='font-size:10.0pt;mso-bidi-font-size:11.0pt;line-height:115%;font-family:
"Palatino Linotype","serif";color:navy'><br style='mso-special-character:line-break'>
<br style='mso-special-character:line-break'>
</span></b><b style='mso-bidi-font-weight:normal'><span
style='font-size:10.0pt;mso-bidi-font-size:11.0pt;line-height:115%;font-family:
"Palatino Linotype","serif";color:olive'><o:p></o:p></span></b></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

</div>

</body>

</html>
