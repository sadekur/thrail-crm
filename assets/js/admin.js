jQuery(document).ready(function($) {
    $('.delete-lead').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.post(ajaxurl, { action: 'delete_lead', id: id }, function(response) {
            alert(response.data.message);
            location.reload();
        });
    });

    $('.edit-lead').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.post(ajaxurl, { action: 'update_lead', id: id }, function(response) {
            alert(response.data.message);
            location.reload();
        });
    });
});
