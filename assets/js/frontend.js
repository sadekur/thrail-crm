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
});
