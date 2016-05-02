$(document).ready(function(){



    //display modal form for task editing
    $('.open-modal').click(function(){
        var wo_id = $(this).val();

        $.get('/workorders/' + wo_id + '/bill', function (data) {
            //success data
            console.log(data);
            $('#wo_id').val(data.id);
            $('#amount_billed').val(data.amount_billed);
            $('#billing_description').val(data.billing_description);
            $('#job_cost').val(data.job_cost);

            $('#myModal').modal('show');
        }) 
    });

    $( '.file-btn' ).on( "click", function( event ) {
    	event.preventDefault();
    	console.log( $( this ).text() );
    	window.open ($(this).attr("href"));
    	
	});

	$("#btn-save").click(function (e) {

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

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
		$('#myModal').modal('hide');
	});
});


