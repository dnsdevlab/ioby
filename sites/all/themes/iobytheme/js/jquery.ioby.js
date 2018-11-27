(function ($) {

  Drupal.behaviors.iobytheme = {
    attach: function(context, settings) {

      //wysiwyg hiding
      $('.page-add-project-step-2 fieldset.filter-wrapper').remove();

      //login form hiding
      $('a.login', context).click(function () {
        $(this).toggleClass('activated').siblings(".region").fadeToggle("fast");
        return false;
      });

      //search & signup form labels
      if ( "" != $("#edit-search-block-form--2").val() ) {
        $("#search-block-form label").hide();
      }

      $("#search-block-form label, #mc_embed_signup label, #mc_home_embed_signup label").click( function() {
        $(this).fadeOut('fast').next('input').focus();
      });
      $("#search-block-form input, #mc_embed_signup input, #mc_home_embed_signup input").focus( function() {
        $(this).prev("label:visible").fadeOut('fast');
      } ).blur( function() {
        if ( $(this).val() == "") {$(this).prev('label').fadeIn('fast');}
      });

      //project add preview page tweaks
      if ( $("body.page-add-project-step-2 .preview").length ) {
        $(".ioby-step-indicator li").removeClass("current").last().addClass("current");
        $(".preview h3:first-child:visible").hide();
      }

      // project page tabs
      $("body.node-type-project-2 .project-tabs li a").click(function() {
        //do nothing if already active
        if ( $(this).parent("li").hasClass("active")) return false;
        //else, do the fun thing
        $(".project-tabs li").removeClass("active");
        $(this).parent("li").addClass("active");
        $(".pane:visible").hide();
        $( $(this).attr('href') ).show();
        return false;
      });

      //Hide/show project miniview tab
      function miniview_tabs( element ) {
        $( element ).hover(
          function() {
            $(this).children('.extra-info').addClass('is-active')
          },
          function() {
            $(this).children('.extra-info').removeClass('is-active')

          }
        );
      }

      miniview_tabs('.project-miniview');

      //jump to project updates if provided in URL
      if ( $("body.node-type-project-2").length && location.hash.length) {
        //trim off the # and split any slashes into an array
        my_query = location.hash.substr(1).split("/");
        if ( $(".pane[id='"+my_query[0]+"']").length ) {
          //activate the tab and show the pane
          $(".project-tabs li").removeClass("active");
          $(".project-tabs li a[href='#"+my_query[0]+"']").parent("li").addClass("active");
          $(".pane:visible").hide();
          $("#"+my_query[0]).show(0, function() {
            //then scroll, if necessary
            if (my_query[0] == "updates" && my_query.length > 1) {
              update_id = my_query[1];
              var destination = $("article#update-"+update_id).offset().top;
              $("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination-30}, 500 );
            }
          });
        }
      }

      $("body.node-type-project-2 a.updates-jump").click(function() {
        $(".project-tabs li a[href='#updates']").click();
        return false;
      });

      //sponsor ribbon action!
      $("body.node-type-project-2 .sponsor-ribbon").insertAfter("#pageheader").delay("2000").slideDown('slow');

      //AJAX call to change the view on the front page
      $('#display_links a:not(.processed,.last)', context).click(function () {
        $.ajax({
          type: 'GET',
          url: this.href,
          context: this,
          success: function(data) {
                    $('#project_display').html(data.projects);
                    $('.current').removeClass('current');
                    $(this).addClass('current');
                    miniview_tabs( '.project-miniview' );
                   },
          dataType: 'json',
          data: 'js=1'
        });
        return false;
      }).addClass('processed');

      // For curvy corners
      $('#display_links ul.tabs div').click(function() {
        $(this).parent().click();
      });

      //project browser map/grid switcher
      $(".view-header a.browse-switch").click(function() {
        new_href = $(this).attr('href') + location.search;
        $(this).attr('href',new_href);
      });

      //vertical centering on microprojects
      $(".project-microview h3").each(function() {
        margin = ($(this).parent().height() - $(this).height()) / 2;
        $(this).css("marginTop", parseInt(margin) - 3);
      });

      //try to prevent people from removing gratuity, if they've got projects....
      trueRemove = false;

      if (Drupal.settings.iobypopup != undefined && $(".page-cart .views-field-line-item-title:contains('gratuity')").length && $("td.views-field").length > 1) {
        $(".page-cart .views-field-line-item-title:contains('gratuity')").siblings('.views-field-edit-delete').children('input.delete-line-item').click(function() {
          if (trueRemove) return true;
          remove_element = $(this);

          //make sure there's only one?
          $("#dialog").remove();

          //build a dialog and open it
          $(this).prepend('<div id="dialog" class="region-content content">' + Drupal.settings.iobypopup["iobypopupGratuityRemove"] + '</div>');
          $("#dialog")
          .dialog({
            buttons: { "remove": function() {
                trueRemove = true;
                remove_element.click();
              }, "include gratuity": function() {
                $(this).dialog('close').remove();
              }
            },
            closeOnEscape: false,
            modal: true,
            open: function() {
              $(".ui-dialog").find("button:last").focus();
            },
            resizable: false
          }); //end dialog

          //return false, since only the dialog "remove" can trigger gratuity removal
          return false;
        });
      }

      //gratuity "what's this?" popup
      if ($.isFunction($.colorbox) && Drupal.settings.iobypopup != undefined) {
        $(".page-cart .views-field-line-item-title:contains('gratuity')").once('linked')
            .prepend('<div style="display:none"><div id="gratuity-info-popup" class="region-content content">' + Drupal.settings.iobypopup["iobypopupGratuityInfo"] + '</div></div>')
            .append(' <a href="#gratuity-info-popup" class="colorbox-load">what\'s this?</a>').children("a")
            .colorbox({
              innerWidth:500,
              inline:true
            });
      }

      //donation form value check
      $("#iobyproject-donation-form").submit(function() {
        if (!$(this).find("#edit-donation").val().match(/^\d+(\.\d{2})?$/)) {
          alert("To give to this project, please enter a valid dollar amount.");
          $(this).find("#edit-donation").focus();
          return false;
        }
      });

    }
  };

})(jQuery);
