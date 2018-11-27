(function ($) {

  Drupal.behaviors.iobytheme = {
    attach: function(context, settings) {

    var inview1 = new Waypoint.Inview({
      element: $('.happening-callout:nth-of-type(1) .progress-bar__number')[0],
      entered: function(direction, element) {
        numberCounts($(this.element));
        overlayGrow($(this.element));
        this.destroy()
      }
    })

    var inview2 = new Waypoint.Inview({
      element: $('.happening-callout:nth-of-type(2) .progress-bar__number')[0],
      enter: function(direction, element) {
        numberCounts($(this.element));
        overlayGrow($(this.element));
        this.destroy()
      }
    })

    var inview3 = new Waypoint.Inview({ 
      element: $('.happening-callout:nth-of-type(3) .progress-bar__number')[0],
      enter: function(direction, element) {
        numberCounts($(this.element));
        overlayGrow($(this.element));
        this.destroy()
      }
    })


    var typed = new Typed('.active-text', {
      strings: ["great.","greener.", "closer.", "smarter.", "faster.","stronger.","great.","greener.", "closer.", "smarter.", "faster.","stronger.","great.","greener.", "closer.", "smarter.", "faster.","stronger."],
      typeSpeed: 30,
      showCursor: true,
      //loop: true,
      backDelay: 1800,
      startDelay: 1200
    });

    $(".page-header").clone().prependTo("body").wrap("<div id='mobile-header'></div>")
    $(".refresh-hamburger").click(function(){
      $("body").toggleClass("mobile-open")
      $("#mobile-header").fadeToggle()
    })

    $(".page-header__main-nav > ul > li").each(function(){
      if($(this).has("ul").length){
        $(this).addClass("hasChildren")
      }
    })

    $(".hasChildren > a").click(function(e){
      e.preventDefault();
      $(this).parent().find("ul").slideToggle();
    })

    $("li.login > a").click(function(e){      
      e.preventDefault();      
      $(this).parent().toggleClass("form-hidden")
    })

    $("li.search > a").click(function(e){      
      e.preventDefault();      
      $(this).parent().toggleClass("form-hidden")
    })

    $("li.hasChildren").hover(function(){
      $(this).addClass("show-children")
    },function(){
      $(this).removeClass("show-children")
    })

    //$("li ul").mouseout(function(){
      //console.log("ASdx");
      //$(this).removeClass("show-children")
    //})

    function numberCounts($el) {      
      var countTo = $el.text();
                
      $({ countNum: 0}).animate({
        countNum: countTo
      },    
      {    
        duration: 1200,
        easing:'linear',
        step: function() {
          $el.text(Math.floor(this.countNum));
        },
        complete: function() {
          $el.text(this.countNum);
        }    
      });  
    }

    function overlayGrow($el){
      var $number = $el.attr("data-percent");
      console.log($el.siblings(".progress-bar__bar").find(".overlay").css("width"))
      $el.siblings(".progress-bar__bar").find(".overlay").animate({
        "width": (100 - $number)+"%"
      },{
        duration: 800,
        easing: 'linear',
        complete: function(){
          //
        }
      })
    }

  }
}
})(jQuery);
