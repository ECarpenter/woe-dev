<head>	
	<!-- CSS Code -->
	<style type="text/css" scoped>
		img.TemplateImage { 
			 width:700px; max-height:1000px;
		}

		.ManagerInfo {
			position: absolute; top: 130px; left: 110px; height: 90px; width: 450px; text-align: left;
		}
		.BillToInfo {
			position: absolute; top: 200px; left: 110px; height: 90px; width: 350px; text-align: left;
		}
		.Date {
			position: absolute; top: 110px; left: 485px; height: 90px; width: 175px; text-align: left;
		}
		.InvoiceNumber {
			position: absolute; top: 155px; left: 485px; height: 90px; width: 175px; text-align: left;
		}
		.Description {
			position: absolute; top: 360px; left: 115px; height: 90px; width: 175px; text-align: left;
		}
		.BillbackValue {
			position: absolute; top: 360px; left: 485px; height: 90px; width: 175px; text-align: left;
		}
		.RemitAddress {
			position: absolute; top: 560px; left: 115px; height: 90px; width: 350px; text-align: left;
		}
		.Total {
			position: absolute; top: 655px; left: 485px; height: 90px; width: 175px; text-align: left;
		}
		

		body { font-family: Helvetica; font-size: 75%; }
	</style>
</head>

<body>
	<div align="right">
		<img src="images/Invoice_Template.jpg" alt="COS" class="TemplateImage">
		<div class="ManagerInfo"> {{$workorder->Property()->PrimaryManager()->address}} <br>	
			{{$workorder->Property()->PrimaryManager()->city}},  {{$workorder->Property()->PrimaryManager()->state}} {{$workorder->Property()->PrimaryManager()->zip}}  <br>
			Tel: {{$workorder->Property()->PrimaryManager()->phone}} Fax: {{$workorder->Property()->PrimaryManager()->fax}}
		</div>
		<div class="Date">{{date('F d, Y', strtotime($workorder->created_at->timezone(Auth::user()->timezone)))}}</div>
		<div class="InvoiceNumber">{{$workorder->invoice_number}}</div>

		<div class="BillToInfo">
			{{$workorder->Tenant->company_name}} 
		</div>
		<div class="Description">
			{{$workorder->billing_description}}
		</div>
		<div class="BillbackValue">
			$ {{number_format($workorder->amount_billed , 2)}}
		</div>
		<div class="RemitAddress">
			{{$workorder->Property()->Remit->payable_to}} <br>
			{{$workorder->Property()->Remit->address}} <br>
			@if ($workorder->Property()->Remit->address_secondline != null)
				{{$workorder->Property()->Remit->address_secondline}} <br>	
			@endif
			{{$workorder->Property()->Remit->city}},  {{$workorder->Property()->Remit->state}} {{$workorder->Property()->Remit->zip}}  <br>
		</div>
		<div class="Total">
			$ {{number_format($workorder->amount_billed , 2)}}
		</div>

	</div>
	