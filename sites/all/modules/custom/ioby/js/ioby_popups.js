(function ($) {
    Drupal.behaviors.iobypopups = {
        attach: function(context, settings) {
            if ($.isFunction($.colorbox)) {
                $("a.whats-this")
                    .colorbox({
                        innerWidth: 500,
                        inline: true
                    });
            }
        }
    };
})(jQuery);