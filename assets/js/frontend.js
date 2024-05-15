jQuery(document).ready(function($) {
	$('#thrailOptinForm').submit(function(event) {
		event.preventDefault();

		var name = $("#name").val();
		var email = $("#email").val();
		var nonce = THRAIL.nonce;

		$.ajax({
		    url: THRAIL.ajaxurl,
		    method: "POST",
		    data: {
		        action: 'thrail_form',
		        name: name,
		        email: email,
		        nonce: nonce
		    },
		    dataType: 'JSON',
		    success: function(response) {
		         if(response.success) {
                    alert(response.data.message);
                } else {
                    alert(response.data.message);
                }
		    },
		    error: function(response) {
		        console.log(response);
		    }
		});
	});

	// $(document).on( 'submit', '#thrailOptinForm', function(e) {
	// 	e.preventDefault();


	// 	var $form = $(this);

	// 	var $data = $form.serialize();

	// 	$.ajax({
	// 	    url: THRAIL.ajaxurl,
	// 		data: $data,
	// 		type: 'POST',
	// 		dataType: 'JSON',
	// 		success: function(resp){
	// 			// console.log(resp);
	// 		},
	// 		error: function( $xhr, $sts, $err ) {
	// 		}
	// 	})
	// })
});
