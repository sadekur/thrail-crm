jQuery(document).ready(function($) {
	$('#thrailOptinForm').on('submit', function(event) {
		event.preventDefault();

		var name = $("#name").val();
		var email = $("#email").val();
		var nonce = THRAIL.nonce;
		console.log(name);
		console.log(email);
		console.log(nonce);

		$.ajax({
		    url: THRAIL.ajaxurl,
		    method: "POST",
		    data: {
		        action: 'thrail_form',
		        name: name,
		        email: email,
		        nonce: nonce
		    },
		    success: function(response) {
		        alert('Data submitted successfully!');
		    },
		    error: function(response) {
		        alert('Failed to submit data.');
		    }
		});
	});
});
