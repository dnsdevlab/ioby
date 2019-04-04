(function ($) {
  Drupal.behaviors.prevent_duplicate_save = {
    attach: function (context, settings) {
      var submitted = 0;
      $('#edit-next').click(function(){
         if (submitted == 1) {
             $(this).attr('disabled', 'disabled');
         }
         else {
             submitted = 1;
         }
      });
    }
  }
})(jQuery);
