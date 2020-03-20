(function($) {
    $(document).ready(function() {
        var btn = $('#btnExportPosts');
        btn.on('click', function() {
            btn.prop('disabled', true);
            $.post(settings.ajaxurl, {'action' : 'export_posts'}, function( response ) {
                btn.prop('disabled', false);
                btn.append(' success!');
            });
        })
    });
})(jQuery);