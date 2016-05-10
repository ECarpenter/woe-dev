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
			position: absolute; top: 140px; height: 90px; width: 175px; 
		}
		.GenInfoLeftValue {
			position: absolute; top: 140px; left: 100px; height: 90px; width: 175px; 
		}
		.GenInfoRight {
			position: absolute; top: 140px; left: 350px; height: 90px; width: 175px; 
		}
		.GenInfoRightValue {
			position: absolute; top: 140px; left: 400px; height: 90px; width: 1750px; 
		}

		body { font-family: DejaVu Sans; }
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

	<div class="GenInfoLeftValue"> 		{{$workorder->Tenant->Property->name}}

	</div>

	<div class="GenInfoRight">	
	Property#: <br>
	Tenant ID: <br>

	</div>