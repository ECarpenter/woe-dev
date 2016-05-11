<head>	
	<!-- CSS Code -->
	<style type="text/css" scoped>
		img.GeneratedImage {
		width:300px;max-height:225px;
		}

		.Header {
			background-color: #B4B4B4; position: absolute; top: 100px; height: 25px; width: 700px; text-align: center;
		}

		.GenInfoLeft {
			position: absolute; top: 140px; height: 90px; width: 100px; 
		}
		.GenInfoLeftValue {
			position: absolute; top: 140px; left: 100px; height: 90px; width: 350px; 
		}
		.GenInfoRight {
			position: absolute; top: 140px; left: 450px; height: 90px; width: 175px; 
		}
		.GenInfoRightValue {
			position: absolute; top: 140px; left: 550px; height: 90px; width: 175px; 
		}

		body { font-family: Helvetica; }
	</style>
</head>

<body>
	<div align="right">
		<img src="images/DP_Logo.jpg" alt="Company Logo" class="GeneratedImage">
	</div>
	<div align="right">
			<h3>Change of Status </h3>
	</div>
	<div class="Header">GENERAL INFORMATION	 </div>	

	<div class="GenInfoLeft">	
	Property: <br>
	Tenant:   <br>
	Unit:

	</div>

	<div class="GenInfoLeftValue"> {{$workorder->Tenant->Property->name}} <br>
		{{$workorder->Tenant->company_name}} <br>
		{{$workorder->Tenant->unit}}

	</div>

	<div class="GenInfoRight">	
	Property ID: <br>
	Tenant ID: <br>

	</div>

	<div class="GenInfoRightValue">
		{{$workorder->Tenant->Property->property_system_id}} <br>
		{{$workorder->Tenant->tenant_system_id}}
	</div>