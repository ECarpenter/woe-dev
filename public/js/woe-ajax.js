$(document).ready(function(){



    //display modal form for task editing
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

    $('.open-edit-tenant-modal').click(function(){
        var tenant_id = $(this).val();

        $.get('/tenant/' + tenant_id + '/response', function (data) {
            //success data
            console.log(data);

            $('#tenant_system_id').val(data.tenant_system_id);
            $('#unit').val(data.unit);
            $('#company_name').val(data.company_name);
            if(data.active == 1) {
                $('#active').prop('checked', true);
            }
            else {
                $('#inactive').prop('checked', true);
            }
            if(data.verified == 1) {
                $('#verified').prop('checked', true);
            }
            else {
                $('#unverified').prop('checked', true);
            }
            $('#EditTenantModal').modal('show');
        })
    });
    
    $('.open-upload-invoice-modal').click(function(){        

        $('#UploadInvoiceModal').modal('show');
    });

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
        })  
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

	$("#btn-bill").click(function (e) {

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        if(confirm("Are you sure you want to process the tenant's bill?"))
        {      
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

    $("input:radio[name='tenantUpload']").click(function(){
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
});


