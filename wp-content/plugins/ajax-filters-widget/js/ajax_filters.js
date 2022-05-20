jQuery("#ajax_filter").on("change", function (e) {
    jQuery.ajax({
        url: ajaxFilter.admin_url,
        type: 'post',
        data: {
            action: 'ajax_filters',
            title: jQuery('#title').val(),
            date: jQuery('#date').val(),
            post_limit: jQuery('#post_limit').val()
        },
        success: function (data) {
            jQuery('#response').html(data);
        }
    });
    return false;
})