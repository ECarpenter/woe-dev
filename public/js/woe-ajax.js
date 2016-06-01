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

        $.get('/tenant/' + tenant_id + '/edit', function (data) {
            //success data
            console.log(data);

            $('#tenant_system_id').val(data.tenant_system_id);
            $('#unit').val(data.unit);
            $('#company_name').val(data.company_name);
            $('#active').prop('checked', data.active);
            $('#verified').prop('checked', data.verified);
            $('#inactive').prop('checked', !data.active);
            $('#unverified').prop('checked', !data.verified);
            $('#EditTenantModal').modal('show');
        })
    });
    
    $('.open-upload-invoice-modal').click(function(){        

        $('#UploadInvoiceModal').modal('show');
    });

    $('.open-manage-insurance-modal').click(function(){ 

        $('#ManageInsuranceModal').modal('show');         
    });

    $('.open-update-insurance-modal').click(function(){           

        $('#UpdateInsuranceModal').modal('show');         
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
});


