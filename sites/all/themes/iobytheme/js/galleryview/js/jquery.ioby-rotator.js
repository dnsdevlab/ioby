(function($){
  $(document).ready(function() {
    $('#rotator').galleryView({
      show_panels: false,
      filmstrip_size:3,
      frame_width:541,
      frame_height: 326,
      frame_opacity: 1.0,
      show_filmstrip_nav: false,
      frame_gap:20,
      transition_interval:5600
    });
  })
 })(jQuery);