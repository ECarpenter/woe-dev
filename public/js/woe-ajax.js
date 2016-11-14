$(document).ready(function(){

	//
	//Logic for tenant registration page
	//
	
	jQuery.fn.extend({
		//
		//validate tenant registartion form. property and tenant id
		//
		validatetenant: function () {
			var tenantid = $(this).val();
			console.log(tenantid);
			$.ajax({
				type: "GET",
				url: '/tenantregister/id',
				data: {tenantid : tenantid},
				dataType: 'json',
				success: function (data){
					console.log(data);
					$('#tenantidform').removeClass('has-error');
					$('form button[type="submit"]').prop( "disabled", false );
					$('#idresult').remove().end();
					$('#propresult').remove().end();
					if (data == "failure")
					{
						$('#tenantidform').addClass('has-error');
						$('#tenantidform').append('<span class="help-block" id="idresult"><strong>Tenant Id not found</strong></span>');
					}
					else
					{
						$('#companynameinput').val(data.company_name);
						$('form input[name="company_name"]').prop( "readOnly", true );
						if ($('select[name="property"]').val() != data.property_id)
						{
							$('#tenantidform').addClass('has-error');
							$('#tenantidform').append('<span class="help-block" id="propresult"><strong>Property does not match</strong></span>');
							$('form button[type="submit"]').prop( "disabled", true );
						}
					}
				}
			});
		}
	});

	


	//
	///
	///FIX THIS NAME IS WRONG WILL TRY TO RUN FOR ANY PAGE WITH THIS NAME
	///
	///
	//generate property list from city entered
	$("select[name='city']").change(function () {
		var city = escape($(this).val());
		console.log(city);
		$.ajax({
			type: "GET",
			url: '/tenantregister/city',
			data: {city : city},
			dataType: 'json',
			success: function (data){
				$("select[name='property']").find('option').remove().end();
				jQuery.each(data, function(index, item) {
				   $("select[name='property']").append('<option value="' + item.id + '">' + item.name + '</option>')
					console.log(item.name); 
				});  
				$("select[name='property']").prepend('<option value="">Select One</option>').val('');
			}
		});
	});

	//makes sure property and tenant id match
	$("select[name='property']").change(function (){
		console.log($.trim($('#tenantidinput').val()))
		if ($.trim($('#tenantidinput').val()) != '')
		{
			$('#tenantidinput').validatetenant();
		}
	});

	//checks tenant id on registration page is valid
	$('#tenantidform').change(function(){
		$('#tenantidinput').validatetenant();
	});

   //
   //display modal form for tenant editing
   //
	$('.open-edit-tenant-modal').click(function(){
		var tenant_id = $(this).val();

		$.get('/tenant/' + tenant_id + '/response', function (data) {
			//success data
			console.log(data);

			$('#tenant_system_id').val(data.tenant_system_id);
			$('#unit').val(data.unit);
			$('#company_name').val(data.company_name);
			$('#insurance_contact_email').val(data.insurance_contact_email);
			if(data.active == 1) {
				$('#active').prop('checked', true);
			}
			else {
				$('#inactive').prop('checked', true);
			}
			
			$('#EditTenantModal').modal('show');
		})
	});

	//
	//Insurance modal functions 
	//
	$('.open-manage-insurance-modal').click(function(){ 
		var insurance_id = $(this).val();

		$.get('/insurance/' + insurance_id + '/response', function (data) {
			//success data
			console.log(data);
			if(data.workerscomp_applicable == 0) {
				console.log('passed if');
				$('#workerscomp_applicable').prop('checked', true);
			}
			$('#ManageInsuranceModal').modal('show');
			$('#ManageInsuranceModal').draggable({
				handle: ".modal-header"
				});       
		})  
	});

	//changes display on insurance modal
	$("input:radio[name='tenantUpload']").click(function() {
		if ($(this).val() == "reject") {
			$('#rejectnote').show();
			$('#insurancedata').hide();
			$('#insurancetable').hide();
		}
		else {
			$('#rejectnote').hide();
			$('#insurancedata').show();
			$('#insurancetable').show();
		}
	});

	$('.open-upload-invoice-modal').click(function(){        
		$('#UploadInvoiceModal').modal('show');
	});

	$('.open-update-insurance-modal').click(function(){           

		$('#UpdateInsuranceModal').modal('show');         
	});

	$('.open-tenant-req-insurance-modal').click(function(){           

		$('#InsReqTenantModal').modal('show');         
	});

	$('.open-property-req-insurance-modal').click(function(){           

		$('#InsReqPropertyModal').modal('show');         
	});

	$( '.file-btn' ).on( "click", function( event ) {
		event.preventDefault();
		console.log( $( this ).text() );
		window.open ($(this).attr("href"));
	});

	//
	//Tenant Billing Process
	//
	$('.open-billing-modal').click(function(){
		var wo_id = $(this).val();

		$.get('/workorders/' + wo_id + '/bill', function (data) {
			//success data
			console.log(data);
			$('#wo_id').val(data.id);
			$('#amount_billed').val(data.amount_billed);
			$('#billing_description').val(data.billing_description);
			$('#job_cost').val(data.job_cost);

			$('#BillingModal').modal('show');
		}) 
	});

	$("#btn-bill").click(function (e) {

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			}
		})

		if(confirm("Are you sure you want to process the tenant's bill?"))
		{   

			$('#modal-loading').modal('show');
			e.preventDefault(); 

			var formData = {
				amount_billed: $('#amount_billed').val(),
				billing_description: $('#billing_description').val(),
				job_cost: $('#job_cost').val(),
			};

			$.ajax({

				type: "PATCH",
				url: '/workorders/' + $('#wo_id').val() + '/bill',
				data: formData,
				dataType: 'json',
				success: function (data){
					console.log(data);

					location.reload();
				},
				error: function (data) {
					console.log('Error:', data);
				}

			});	
			$('#BillingModal').modal('hide');
		}
	});

	$("#btn-send-notice").click(function (e) {
		if(confirm("Are you sure you want to send the tenant an insurance notice?"))
		{ 
			$.ajax({
				type: "GET",
				url: '/tenant/' + $('#tenant_id').val() + '/notice'
			});
			location.reload();
		}
	});

	//
	//Tenant Verification
	//
	$('.open-verification-modal').click(function(){
		var user_id = $(this).data('value');
		$('#user_id').val(user_id);

		$('#company_name_display').empty();
		$('#user_name_display').empty();
		$('#user_email_display').empty();
		$('#user_job_title_display').empty();
		$('#property_verify').empty();
		$('#tenant_verify').empty();

		$.get('/user/verify/display/' + user_id, function(verify){
			console.log(verify);
			$('#company_name_display').append(verify.user.company_name);
			$('#user_name_display').append(verify.user.name);
			$('#user_email_display').append(verify.user.email);
			$('#user_job_title_display').append(verify.user.job_title);
			
			$.each(verify.properties, function(index, property){
				if (property.id == verify.current_property) {					
					$('#property_verify').prepend("<option value='" + property.id + "' selected>" + property.name + "</option>");
				}
				else {
					$('#property_verify').prepend("<option value='" + property.id + "'>" + property.name + "</option>");
				}
			});

			$.each(verify.tenants, function(index, tenant){
				if (verify.current_property == tenant.property_id) 
				{
					$('#tenant_verify').prepend("<option value='" + tenant.id + "'>" + tenant.company_name + "</option>");
				}
			});
			$('#tenant_verify').prepend("<option value='' selected> Select Tenant </option>");
			$('#TenantVerificationModal').data('verify', verify);
		})
		$('#TenantVerificationModal').modal('show');
		
		$('#TenantVerificationModal').draggable({
			handle: ".modal-header"
		});       

	});


	$('#property_verify').change(function (){
		var user_id = $('.open-verification-modal').data('value');
		
		$('#tenant_verify').empty();
		
			$.each($('#TenantVerificationModal').data('verify').tenants, function(index, tenant){
				if ($('#property_verify').val() == tenant.property_id ) 
				{
					$('#tenant_verify').prepend("<option value='" + tenant.id + "'>" + tenant.company_name + "</option>");
				}
			});
			$('#tenant_verify').prepend("<option value='' selected> Select Tenant </option>");
		
	});



	//
	// Work Orders
	//
	
	$('#support_file_upload').change(function(){
		var filename = this.value;
    	var lastIndex = filename.lastIndexOf("\\");
    	if (lastIndex >= 0) {
        filename = filename.substring(lastIndex + 1);
    		$('#filename_display').empty();
			$('#filename_display').prepend(filename);
    	}
		
	});

	//
	//Property-User Edit
	// 
	$('#property_user_multiselect').multiSelect({
				selectableHeader: "<div class='custom-header'>User List</div>",
				selectionHeader: "<div class='custom-header'>Linked to Property</div>",
				afterSelect: function(selectedID) {
					
				},
				afterDeselect: function(selectedID) {

				},
			});

	$('.open-user-property-modal').click(function(){
		var property_id = $(this).data('value');

		

			$.ajax({
			    type: 'GET',
			    url : '/property/multiselectdisplay/'+ property_id +'/',
			    dataType: 'json',
			    success : function(data, textStatus, req) {
			        console.log(data);

					$.each(data.managers, function(index, manager){
						$('#property_user_multiselect').multiSelect('addOption', { value: manager.id, text: manager.name});
					});
					$.each(data.selected, function(index, manager){
						$('#property_user_multiselect').multiSelect('addOption', { value: manager.id, text: manager.name});
						$('#property_user_multiselect').multiSelect('select',  String(manager.id));
					});
					},
				error: function(req, textStatus, errorThrown) {
					//this is going to happen when you send something different from a 200 OK HTTP
					alert('Ooops, something happened: ' + textStatus + ' ' +errorThrown);
				}

			});	
			
			$('#UserModal').modal('show');
	
	});
	//
	//Remit
	//
	
	$('.open-remit-modal').click(function(){ 
		var current_remit_id = $(this).data('value');
		$.get('/property/remit-display/', function(remits){
			console.log(remits);
			
			$('#RemitDisplay').empty();

			$.each(remits, function(index, remit){
				if (remit.id == current_remit_id) {					
					$('#RemitSelect').prepend("<option value='" + remit.id + "' selected>" + remit.payable_to + "</option>");
					$('#RemitDisplay').prepend(remit.payable_to + "<br>" + remit.address  + "<br>");
					if (remit.address_secondline != null)
					{
						$('#RemitDisplay').append(remit.address_secondline + "<br>");
					}
					$('#RemitDisplay').append(remit.city + ", " + remit.state + " " + remit.zip)
				}
				else {
					$('#RemitSelect').prepend("<option value='" + remit.id + "'>" + remit.payable_to + "</option>");
				}
			});   
		})       

		$('#RemitModal').modal('show');         
	});

	$('#RemitSelect').change(function(){
		$('#RemitDisplay').empty();
		$.get('/property/remit-display/', function(remits){
			console.log(remits);
			$.each(remits, function(index, remit){
				if (remit.id == $("select option:selected").val()) {					
					$('#RemitDisplay').prepend(remit.payable_to + "<br>" + remit.address  + "<br>");
					if (remit.address_secondline != null)
					{
						$('#RemitDisplay').append(remit.address_secondline + "<br>");
					}
					$('#RemitDisplay').append(remit.city + ", " + remit.state + " " + remit.zip);
				}
			});
			
			
		})
		
	});
		
});