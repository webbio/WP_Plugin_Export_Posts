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

        var btnMenus = $('#btnExportMenus');
        btnMenus.on('click', function() {
            btnMenus.prop('disabled', true);
            $.post(settings.ajaxurl, {'action' : 'export_menus'}, function( response ) {
                btnMenus.prop('disabled', false);
                btnMenus.append(' success!');
            });
        })
    });
})(jQuery);