(function ($) {
 Drupal.behaviors.ioby_project_notification = {
    attach: function (context, settings) {
      // Set cookie
      var projectNotification = $.cookie("hide-project-notification");

      if (projectNotification == '0') {
         $(".close-project-notification").parent().hide();
      }
      else{
        $(".close-project-notification").click(function() {
            $(this).parent().hide();
            $.cookie('hide-project-notification', '0', { expires: 7 }); // One week / 7 days
          });
      }
    }
  }
})(jQuery);
