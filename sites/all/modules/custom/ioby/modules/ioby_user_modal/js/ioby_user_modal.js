(function ($) {

 Drupal.behaviors.ioby_user_modal = {
  attach: function (context, settings) {
    var uid = Drupal.settings.ioby_user_modal;

    // User forms tabbed
    $('ul.ioby-user-modal-tabs li').click(function(){
      var tab_id = $(this).attr('data-tab');

      $('ul.ioby-user-modal-tabs li').removeClass('current');
      $('.ioby-user-modal-tab-content').removeClass('current');

      $(this).addClass('current');
      $("#"+tab_id).addClass('current');
    })

    // Alter ctools url for unified login/registration modal
    if($('body').hasClass('logged-in')){

      var user_modal_link = "a.ctools-modal-ioby-user-modal";

      if ($(user_modal_link).length > 0) {
        // for each link
        $(user_modal_link).each(function() {
          // Get a href value and destination paramater
          var url = $(this).attr('href');
          var destination = getParamaters(url)['destination'];
          $(this).attr('href', destination);
        });
      }

      // get paramaters
      function getParamaters(url) {
        var url = url;
        var vars = [], hash;
        var hashes = url.slice(url.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
          hash = hashes[i].split('=');
          vars.push(hash[0]);
          vars[hash[0]] = hash[1];
        }
        return vars;
      }

    }
  }
}

// Theme for ctools ioby_user_modal
Drupal.theme.prototype.ioby_user_modal = function () {
  var html = '';
  html += '<div id="ctools-modal" class="popups-box">';
  html += '  <div class="ctools-modal-content ctools-modal-ioby_user-modal-content">';
  html += '    <span class="popups-close"><a class="close" href="#">' + Drupal.CTools.Modal.currentSettings.closeImage + '</a></span>';
  html += '    <div class="modal-scroll"><div class="ioby-modal-header"><h3>' + Drupal.CTools.Modal.currentSettings.headerText +'</h3></div><div id="modal-content" class="ioby-user-modal-content-wrapper modal-content popups-body"></div></div>';
  html += '  </div>';
  html += '</div>';
  return html;
}

}(jQuery));
