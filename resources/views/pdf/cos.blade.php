<head>	
	<!-- CSS Code -->
	<style type="text/css" scoped>
		img.TemplateImage { 
			 width:700px; max-height:1000px;
		}

		.GenInfoLeftValue {
			position: absolute; top: 162px; left: 120px; height: 90px; width: 350px; text-align: left;
		}
		.GenInfoRightValue {
			position: absolute; top: 162px; left: 470px; height: 90px; width: 175px; text-align: left;
		}
		.BillbackCode {
			position: absolute; top: 360px; left: 470px; height: 90px; width: 175px; text-align: left;
		}
		.BillbackValue {
			position: absolute; top: 360px; left: 550px; height: 90px; width: 175px; text-align: left;
		}
		.Comment {
			position: absolute; top: 590px; left: 25px; height: 90px; width: 350px; text-align: left;
		}
		.SubmittedBy {
			position: absolute; top: 900px; left: 130px; height: 90px; width: 175px; text-align: left;
		}
		.Date {
			position: absolute; top: 900px; left: 470px; height: 90px; width: 175px; text-align: left;
		}
		

		body { font-family: Helvetica; }
	</style>
</head>

<body>
	<div align="right">
		<img src="images/Change of Status_Template.jpg" alt="COS" class="TemplateImage">
		<div class="GenInfoLeftValue"> {{$workorder->Property()->name}} <br>	
			{{$workorder->Company_Name()}} <br>
			{{$workorder->Unit()}}
		</div>
		<div class="GenInfoRightValue">
			{{$workorder->Tenant->Property->property_system_id}} <br>
			{{$workorder->Tenant->tenant_system_id}}
		</div>
		<div class="BillbackCode">
			{{ $chargecode->code }}  
		</div>
		<div class="BillbackValue">
			$ {{number_format($workorder->amount_billed , 2)}}
		</div>
		<div class="Comment">
			{{$workorder->billing_description}}
		</div>
		<div class="SubmittedBy">{{\Auth::user()->name}}</div>

		<div class="Date">{{date('F d, Y', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</div>

	</div>
	