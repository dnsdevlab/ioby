(function($) {

  "use strict";

  /**
   * Makes sure that captchas can continue working after AJAX calls.
   */
  Drupal.behaviors.recaptchaReload = {
    attach: function(context, settings) {
      if (typeof(settings.recaptcha) != 'undefined' && typeof(grecaptcha) != 'undefined') {
        if (document.readyState == 'complete') {
          var el = $('.' + settings.recaptcha.class, context);
          if (el.size() > 0 && el.children().size() == 0) {
            grecaptcha.render(el.get(0), {
              sitekey: el.data('sitekey'),
              theme: el.data('theme')
            });
          }
        };
      }
    }
  };

})(jQuery);
