jQuery(document).ready(function($) {
	$('#thrailOptinForm').on('submit', function(event) {
		event.preventDefault();

		var name = jQuery("name").val();
  		var email = jQuery("email").val();
		var nonce = THRAIL.nonce;

		$.ajax({
		    url: THRAIL.ajaxurl, 
		    method:"POST",
		    data:{name:name, email:email, action:'thrail_form', nonce: nonce},
			success: function(response) {
				alert('Data submitted successfully!');
			},
			error: function(response) {
				alert('Failed to submit data.');
			}
		});
	});
});
