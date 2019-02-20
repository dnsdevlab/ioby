(function ($) {
  Drupal.behaviors.iobytheme = {
    attach: function(context, settings) {

    // var inview1 = new Waypoint.Inview({
    //   element: $('.happening-callout:nth-of-type(1) .progress-bar__number')[0],
    //   entered: function(direction, element) {
    //     numberCounts($(this.element));
    //     overlayGrow($(this.element));
    //     this.destroy()
    //   }
    // })
    //
    // var inview2 = new Waypoint.Inview({
    //   element: $('.happening-callout:nth-of-type(2) .progress-bar__number')[0],
    //   enter: function(direction, element) {
    //     numberCounts($(this.element));
    //     overlayGrow($(this.element));
    //     this.destroy()
    //   }
    // })
    //
    // var inview3 = new Waypoint.Inview({
    //   element: $('.happening-callout:nth-of-type(3) .progress-bar__number')[0],
    //   enter: function(direction, element) {
    //     numberCounts($(this.element));
    //     overlayGrow($(this.element));
    //     this.destroy()
    //   }
    // })


    // var typed = new Typed('.active-text', {
    //   strings: ["great.","greener.", "closer.", "smarter.", "faster.","stronger.","great.","greener.", "closer.", "smarter.", "faster.","stronger.","great.","greener.", "closer.", "smarter.", "faster.","stronger."],
    //   typeSpeed: 30,
    //   showCursor: true,
    //   //loop: true,
    //   backDelay: 1800,
    //   startDelay: 1200
    // });


    if( $('.hero').length ) {
      var typewords_array = $('.hero__text').attr("data-words").split(',');
      for (var i=typewords_array.length; i--;) {
          typewords_array[i] = typewords_array[i] + '&nbsp;';
      }

      var typed = new Typed('.active-text', {
        strings: typewords_array,
        typeSpeed: 80,
        showCursor: true,
        backDelay: 1200,
        startDelay: 300,
        fadeOut: true,
        loop: true
      });
    }

    /////////////////////////////////////-+++-
    // Offcanvas menu and accordians

      $(document).ready(function () {

        var $offcanvas = $('.offcanvas');
        var $offcanvas_trigger = $('.page-header__offcanvas-trigger');
        var $offcanvas_close = $('.offcanvas__close')
        var $document = $(document);
        var $body = $('body');
        var $page_wrapper = $('.page-wrapper');
        var $offcanvas_content = $('.offcanvas__content');
        var $offcanvas_height = $offcanvas_content.height();

        //loop through each item in the offcanvas nav that has children
        // and create a button to click that opens and closes the children menu items
        $('.offcanvas__primary-nav .expanded').each( function(index) {
          $(this).find('ul').slideUp();
          if ($(this).hasClass('active')) {
            $(this).find('ul').slideDown();
          }
        });

        // when you click the dropdown button ...
        $('.expanded > span').bind('click', function(){
            // add a class to the parent so you can style things
            $(this).parent().toggleClass('is-open');
            // use jquery to slide reveal the submenu (css animations wont work because we dont know the height)
            $(this).siblings('ul').slideToggle();
            setTimeout(function(){
              $offcanvas_height = $offcanvas_content.height();
              $page_wrapper.height($offcanvas_height);
            }, 400);
        });

        // a function that closes the offcanvas when you press escape key
        var PollForOffcanvasEscape = function(e) {
            var escapeKeyCode = 27;
            if (e.which !== undefined && e.which === escapeKeyCode) {
                OffcanvasClose();
            }
        };

        // a function that will close the offcanvas when you click outside of it
        var PollForOffcanvasOutsideClick = function(e) {
            if(!$(e.target).closest('.offcanvas').length) {
              OffcanvasClose();
            }
        };

        // a function to close the offcanvas
        var OffcanvasClose = function(e) {
            // close all accordians if a user closes the offcanvas nav
            $('.offcanvas__primary-nav').find('.is-open').removeClass('is-open');
            $('.offcanvas__primary-nav').find('a').siblings('ul').slideUp();

            // hide navigation
            $offcanvas.removeClass('is-open');
            $body.removeClass('offcanvas-open');

            // Stop polling for keypress.
            $document.unbind('keydown', PollForOffcanvasEscape);

            // Stop polling for click outside.
            $document.unbind('click touchstart', PollForOffcanvasOutsideClick);
        }

        // a function to open the offcanvas
        var OffcanvasOpen = function(e) {
          $offcanvas.addClass('is-open');
          $offcanvas_height = $offcanvas_content.height();

          // resize the rest of the screen
          $body.addClass('offcanvas-open');
          $page_wrapper.height($offcanvas_height);

          // start polling for escape keypress
          $document.keydown(PollForOffcanvasEscape);

          // start polling for click outside (after a slight delay)
          setTimeout(function(){
            $document.bind('click touchstart', PollForOffcanvasOutsideClick);
          }, 200);
        }
        // open the offcanvas menu
        $offcanvas_trigger.bind("click touchstart", function(e){
          OffcanvasOpen(e);
        });

        // close the offcanvas menu
        $offcanvas_close.bind("click touchstart", function(e){
          OffcanvasClose(e);
        });
      });

  }
}
}(jQuery, Drupal));
