(function ($) {

  Drupal.behaviors.iobytheme = {
    attach: function(context, settings) {
      //wysiwyg hiding
      $('.page-add-project-step-2 fieldset.filter-wrapper').remove();

      // hiding lat and long field, project-2 form
      $('.node-project_2-form .location .form-item-field-project-address-und-0-locpick-user-latitude').remove();
      $('.node-project_2-form .location .form-item-field-project-address-und-0-locpick-user-longitude').remove();

      $('.node-project_2-form .location .location-current-coordinates-fieldset').remove();

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
            $(this).children('.extra-info').addClass('is-active');
          },
          function() {
            $(this).children('.extra-info').removeClass('is-active');

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

      //project page add behaviors
      //part of group or org?
      $(".field-name-field-with-group input").click(function() {
        if ( ($(this).val() == 0 && $("fieldset.group-info .fieldset-wrapper:first:visible").length) ||  $(this).val() == 1 && $("fieldset.group-info .fieldset-wrapper:first:hidden").length  ) {
          $("fieldset.group-info legend:first a").click();
        }
      });

      //tax exempt?
      $(".field-name-field-group-503 input").click(function() {
        if ( ($(this).val() == 0 && $("fieldset.group-tax-info .fieldset-wrapper:visible").length) ||  $(this).val() == 1 && $("fieldset.group-tax-info .fieldset-wrapper:hidden").length  ) {
          $("fieldset.group-tax-info legend a").click();
        }
      });

      //fiscal sponsor
      $(".field-name-field-group-tax-fisc-ioby").hide();
      $(".field-name-field-group-tax-fiscal input").click(function() {
        if ($(this).val() == 0) {
          $(".field-name-field-group-tax-fisc-ioby:hidden").show();
          $(".field-name-field-group-tax-fisc-spon:visible").hide();
        } else {
          $(".field-name-field-group-tax-fisc-ioby:visible").hide();
          $(".field-name-field-group-tax-fisc-spon:hidden").show();
        }
      });

      //volunteer
      $(".field-name-field-project-volunteers input").click(function() {
        if ( ($(this).val() == 0 && $("fieldset.group-volunteer-info .fieldset-wrapper:visible").length) ||  $(this).val() == 1 && $("fieldset.group-volunteer-info .fieldset-wrapper:hidden").length  ) {
          $("fieldset.group-volunteer-info legend a").click();
        }
      });

      //inbrief length counter
      $("#edit-field-project-inbrief textarea").change(function(){
        $("span.inbrief-length").html( $(this).val().length );
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
          $(this).prepend('<div id="dialog" class="region-content content">' + Drupal.settings.iobypopup.iobypopupGratuityRemove + '</div>');
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
        .prepend('<div style="display:none"><div id="gratuity-info-popup" class="region-content content">' + Drupal.settings.iobypopup.iobypopupGratuityInfo + '</div></div>')
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


      /****************\
      *  IDEA FORM JS  *
      \****************/

      // mimic basic form assembly validation, return true if there is a value in the field
      function checkIfValidById(e) {
        //requires wrapper, eg: '#{id}-D'
        if ($('#' + e + '-D').length > 0) {
          $('#' + e + '-D').find('.errMsg').remove();
          // if empty add error message with id '#{id}-E'
          if (!$('#' + e).val()) {
            $('#' + e + '-D').append('<div id="' + e + '-E" class="errMsg"><span>This field is required.</span></div>');
            return false;
          } else {
            return true;
          }
        }
      }

      // if there are tabs on the page
      if ($('.idea-tabs .tabs a.tab').length > 0) {

        // handle tab clicks at the top of the sections
        $('.idea-tabs .tabs a.tab').click(function(e){

          // special handling to check if step1 has empty fields
          if (this == $('li.about-you a')[0]) {
            $valid1 = checkIfValidById('tfa_10');
            $valid2 = checkIfValidById('tfa_11');
            $valid3 = checkIfValidById('tfa_14');
            if ($valid1 && $valid2 && $valid3) {
                $('.idea-tabs .tabs a.tab.active').removeClass('active');
                $(this).addClass('active');
                if (this.hasAttribute('href') && $(this).attr('href').length > 0) {
                  href = $(this).attr('href');
                  if (href.length > 0 && $('.idea-panel' + href).length > 0) {
                    $('.wFormContainer .idea-panel.active').removeClass('active');
                    $('.idea-panel' + href).addClass('active');
                  }
                }

            // if empty step1 fields then disable the step2 tab
            } else {
              $('li.about-you-disabled').removeClass('hidden');
              $('li.about-you').addClass('hidden');
              $(window).scrollTop($("#step1").offset().top);
            }

          // normal handling for clicking on a different tab than we're currently on
          } else if (!$(this).hasClass('active')) {
            $('.idea-tabs .tabs a.tab.active').removeClass('active');
            $(this).addClass('active');
            if (this.hasAttribute('href') && $(this).attr('href').length > 0) {
              href = $(this).attr('href');
              if (href.length > 0 && $('.idea-panel' + href).length > 0) {
                $('.wFormContainer .idea-panel.active').removeClass('active');
                $('.idea-panel' + href).addClass('active');
              }
            }
          }
        });


        // handle button clicks at the bottom of the form sections
        $('.idea-tabs .panel-button').click(function(e){

          // special handling for the #step1 next button
          if (this == $('.button.panel-button.next-button')[0]) {
            $valid1 = checkIfValidById('tfa_10');
            $valid2 = checkIfValidById('tfa_11');
            $valid3 = checkIfValidById('tfa_14');
            if ($valid1 && $valid2 && $valid3) {
              $('li.about-you-disabled').addClass('hidden');
              $('li.about-you').removeClass('hidden');
              $('.idea-tabs .tabs a.tab.active').removeClass('active');
              if (this.hasAttribute('href') && $(this).attr('href').length > 0) {
                href = $(this).attr('href');
                if (href.length > 0 && $('.idea-panel' + href).length > 0) {
                  $('.wFormContainer .idea-panel.active').removeClass('active');
                  $('.idea-panel' + href).addClass('active');
                  $('.idea-tabs a.tab[href=' + href + ']').addClass('active');
                }
              }

            // if empty step1 fields then disable the #step2 tab
            } else {
              $('li.about-you-disabled').removeClass('hidden');
              $('li.about-you').addClass('hidden');
              $(window).scrollTop($("#step1").offset().top);
            }

          // normal behavior once the first step is passed
          } else {
            $('.idea-tabs .tabs a.tab.active').removeClass('active');
            if (this.hasAttribute('href') && $(this).attr('href').length > 0) {
              href = $(this).attr('href');
              if (href.length > 0 && $('.idea-panel' + href).length > 0) {
                $('.wFormContainer .idea-panel.active').removeClass('active');
                $('.idea-panel' + href).addClass('active');
                $('.idea-tabs a.tab[href=' + href + ']').addClass('active');
              }
            }
          }
        });

        // pre-fill user data
        first_name = $('#user_data').data('first-name');
        if (first_name) {
          $('.wFormContainer input#tfa_1').val(first_name);
        }
        last_name = $('#user_data').data('last-name');
        if (last_name) {
          $('.wFormContainer input#tfa_2').val(last_name);
        }
        email = $('#user_data').data('email');
        if (email) {
          $('.wFormContainer input#tfa_5').val(email);
        }
      }

      // see html--form-assembly.tpl.php for the idea-tabs validation fail JS code

      // leader-toolkit active state, with JS as it's coming from a block (this is bad, I know)
      // ex: /leader-toolkit/Overview
      if ($('#block-ioby-ioby-project-creation-indicator').length > 0) {
        current_path = window.location.pathname.toLowerCase();
        $('#block-ioby-ioby-project-creation-indicator .ioby-step-indicator li').each(function(e){
          $li = $(this);
          $a = $(this).find('a');
          if ($a.length > 0) {
            a_lower = $a.attr('href').toLowerCase();
            if (a_lower.indexOf(current_path) !== -1){
              $li.addClass('current');
            }
          }
        });
      }

      // Analytics Events

      function gaEventSend(elEv){
        // Send the event object to Google Analytics
        ga('send', 'event', elEv.category.toLowerCase(), elEv.action.toLowerCase(), elEv.label.toLowerCase());
      }

      // Click to switch from Grid to Map view
      $('a#quicktabs-tab-campaign_projects-1').click(function(){
        elEv = [];
        elEv.category = "switch to map";
        elEv.action = "click-map";
        elEv.label = window.location.pathname;
        gaEventSend(elEv);
      });

      $('.region-footer #block-block-1 .social li a').click(function(){
        elEv = [];
        elEv.category = "footer social click";
        elEv.action = "click-" + $(this).attr('class');
        elEv.label = window.location.pathname;
        gaEventSend(elEv);
      });

      $('.block-multistep .active-step').parent('li').addClass('current');

      $('#project-2-node-form .form-submit').addClass('button');

      $('.or-after').after('<div class="or-divider"><span>Or</span><hr /></div>');

  }

};
$(function(){
  // table fixes for actioncorps

  if(window.location.href.indexOf("actioncorps") > -1) {
    $("td").not(":has(p)").addClass("empty-td");
  }
  
});


  $(function(){
    $('.hamburger').click(function() {
    $(this).toggleClass('is-active');
    $('#off-canvas').toggleClass('slide-in');
    });
  });

  $(function(){

    $("#page").fitVids();

    $('span.nolink').click(function(){
        $('.hidden-2').toggleClass('show');
      });
  });

})(jQuery);
