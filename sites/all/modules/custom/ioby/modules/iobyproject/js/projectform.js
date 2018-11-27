/**
 * Created by sbrackat on 8/18/15.
 */
(function ($) {
    $.fn.wordcount = function (options) {

        console.log(this);
        var defaults, settings, pattern, label;

        defaults = {
            countLimit : 10,
            countWords : true,
            wrapperAttrs: {
                class: "wordcount__container"
            }
        };

        settings = $.extend(true, {}, defaults, options);

        pattern = new RegExp(settings.countWords ? /\b/g : /./g);

        label  =  settings.countWords ? 'word count' : 'character count'


        return this.filter("textarea").each(function(){

            var wrapper = $("<div />").attr(settings.wrapperAttrs),
                counterHtml = $("<span class='wordcount__currentcount'>0</span>"),
                limitHtml = $("<span class='wordcount__countLimit' />"),
                checkTextBox,
                textBox = $(this);



            checkTextBox =  function (ele, count) {
                var textBox = ele,
                    container = textBox.parent();
                counter = textBox.next('.wordcount__container').find('.wordcount__currentcount');
                counter.html(count);
                if(count >= 0 ) {
                    container.removeClass('error');
                }
                else {
                    container.addClass('error');
                }
            }

            textBox.keyup(function () {
                var el = $(this),
                    counts = {},
                    matches = this.value.match(pattern),
                    currentCount = 0;

                console.log(pattern);

                if(settings.countWords) {
                    counts[this.id] = matches ? matches.length / 2 : 0;

                }
                else {
                    counts[this.id] = matches ? matches.length : 0;
                }

                console.log(counts);

                $.each(counts, function(k, v) {
                    currentCount += v;
                    console.log(currentCount);
                    if(currentCount > settings.countLimit) {
                        currentCount = settings.countLimit - currentCount;
                    }

                });

                checkTextBox(el, currentCount);




            }).keyup();


            textBox.after(wrapper);
            wrapper.append(label + ': ')
                .append(counterHtml)
                .append('/')
                .append(limitHtml);
            limitHtml.text(settings.countLimit);

        });

    };


    $.fn.iobyaccordion = function (options) {
        var defaults, settings;

        defaults = {
            fieldWrapper : {
                class : ".form-textarea-wrapper"
            }
        };

        settings = $.extend(true, {}, defaults, options);

        return this.each(function(){
            var el = $(this),
                _label = el.find('label'),
                _wrapper = el.find(settings.fieldWrapper.class);

            el.addClass('ioby-accordion');
            _wrapper.addClass('is-hidden');


            _label.click( function(event){
                var el = $(this),
                    container = el.parents('.form-wrapper').eq(0);

                container.toggleClass('is-open');

                el.toggleClass('is-active')
                    .parent()
                    .find(settings.fieldWrapper.class)
                    .toggleClass('is-hidden');


            });

        });

    };

}(jQuery));



jQuery(document).ready(function($) {

    if(!$('.project-form-version-1').length) {

        $('.field-name-field-project-leader-bio textarea, .field-widget-text-textarea-with-summary textarea ').wordcount({
            countWords: true,
            countLimit: 230
        });

        $('.field-name-field-project-inbrief textarea').wordcount({
            countWords: false,
            countlimit: 230
        })


        $('.group-campaign-page .field-widget-text-textarea, .group-campaign-page .field-widget-text-textarea-with-summary')
            .not('.field-name-field-project-vol-reason')
            .not('.field-name-field-incentive-description')
            .not('.field-name-field-budget-item-description')
            .iobyaccordion();


        var buildInfoPoup = function () {

            if ($('.description > span').length > 0) {
                var tipContent = $('.description > span'),
                    fieldContainer = tipContent.parents('.form-wrapper'),
                    _label;


                tipContent.hide().each(function () {
                    var toolIcon = $('<span class="tooltip_icon"/>');

                    $(this).parent().parent().addClass('ioby-tooltip').find('.form-item label').after(toolIcon)
                });



                Tipped.create('.ioby-tooltip .tooltip_icon', function (element) {
                    return $(element).parents('.form-wrapper').find('.description > span').html();


                });
            }
        };


        buildInfoPoup();
        $('body').delegate("#edit-submit", 'click', function () {
            $('.ioby-accordion label').trigger('click');
        });
    }

    //Scroll Spy
    var classList = [
        'group-about-you',
        'group-project-background',
        'group-campaign-page'
        ];

    //Add ID's to fieldsets
    $.each(classList, function(i, v){
        $('.'+ v).attr('id', v);
    });

    var lastId,
        topMenu = $('#form-nav'),
        topMenuHeight = topMenu.outerHeight() + 15,
        menuItems = topMenu.find("a"),
        scrollItems = menuItems.map(function (){

            var item = $($(this).attr("href"));
            if (item.length) { return item; }
        }),
        menuTopOffset = topMenu.offset().top;


    menuItems.click(function(e){
        var href = $(this).attr("href"),
            offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
        $('html, body').stop().animate({
            scrollTop: offsetTop
        }, 300);
        e.preventDefault();
    });

    menuItems.eq(0).parent().addClass('active');


    // Bind to scroll
    $(window).scroll(function(){


        // Get container scroll position
        var fromTop = $(this).scrollTop()+topMenuHeight;

        topMenu.toggleClass('sticky', $(window).scrollTop() > menuTopOffset);


        // Get id of current scroll item
        var cur = scrollItems.map(function(){
            if ($(this).offset().top < fromTop)
                return this;
        });
        // Get the id of the current element
        cur = cur[cur.length-1];
        var id = cur && cur.length ? cur[0].id : "";

        if (lastId !== id) {
            lastId = id;
            // Set/remove active class
            menuItems
                .parent().removeClass("active")
                .end().filter("[href=#"+id+"]").parent().addClass("active");
        }
    });


});

(function($){
    jQuery(document).ready(function() {
        $('#my-select').selectMultiple();
    });
})(jq213);

(function($){
    jQuery(document).ready(function() {
        $("select").multiselect(); 
    });
})(jq213);
