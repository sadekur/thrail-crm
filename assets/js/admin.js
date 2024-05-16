jQuery(document).ready(function($) {
	$('.delete-lead').on('click', function(e) {
		e.preventDefault();
		var id = $(this).data('id');

		$.ajax({
			url: THRAIL.ajaxurl,
			method: "POST",
			data: {
				action: 'delete_lead',
				id: id
			},
			dataType: 'JSON',
			success: function(response) {
				alert(response.data.message);
				 location.reload();
			},
			error: function(response) {
				console.log(response);
			}
		});

	});

	// $('.edit-lead').on('click', function(e) {
	//     e.preventDefault();
	//     var id = $(this).data('id');

	//     $.ajax({
	//         url: THRAIL.ajaxurl,
	//         method: "POST",
	//         data: {
	//             action: 'update_lead',
	//             id: id
	//         },
	//         dataType: 'JSON',
	//         success: function(response) {
	//             alert(response.data.message);
	//              location.reload();
	//         },
	//         error: function(response) {
	//             console.log(response);
	//         }
	//     });


	// });

        $('.edit-lead').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var currentRow = $(this).closest('tr');
            var name = currentRow.find('.name-column').text();
            var email = currentRow.find('.email-column').text();

            $('#leadId').val(id);
            $('#leadName').val(name);
            $('#leadEmail').val(email);

            $('#editLeadModal').dialog({
                autoOpen: false,
                height: 300,
                width: 350,
                modal: true,
                buttons: {
                    "Save changes": function() {
                        updateLead();
                    },
                    Cancel: function() {
                        $(this).dialog("close");
                    }
                }
            });

            $('#editLeadModal').dialog('open');
        });

        function updateLead() {
            var id = $('#leadId').val();
            var name = $('#leadName').val();
            var email = $('#leadEmail').val();

            $.ajax({
                url: THRAIL.ajaxurl,
                method: "POST",
                data: {
                    action: 'update_lead',
                    id: id,
                    name: name,
                    email: email,
                    nonce: THRAIL.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Lead updated successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                }
            });
        }
});
