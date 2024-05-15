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

	$('.edit-lead').on('click', function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		var currentRow = $(this).closest('tr'); 
		if (currentRow.next().hasClass('edit-row')) {
			currentRow.next().toggle();
			return;
		}

		var name = currentRow.find('.name-column').text();
		var email = currentRow.find('.email-column').text();

		var formHtml = '<tr class="edit-row"><td colspan="4"><form>' +
			'<input type="text" name="name" value="' + name + '" />' +
			'<input type="email" name="email" value="' + email + '" />' +
			'<button type="submit">Update</button>' +
			'<button type="button" class="cancel-edit">Cancel</button>' +
			'</form></td></tr>';

		$(formHtml).insertAfter(currentRow);
		currentRow.next().find('form').on('submit', function(e) {
			e.preventDefault();
			updateLead(id, $(this).find('input[name="name"]').val(), $(this).find('input[name="email"]').val());
		});

		currentRow.next().find('.cancel-edit').on('click', function() {
			currentRow.next().remove();
		});
	});

	function updateLead(id, name, email) {
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
					alert(response.data.message);
					location.reload();
				} else {
					alert(response.data.message);
				}
			},
			error: function() {
				alert('Failed to update lead.');
			}
		});
	}
});
