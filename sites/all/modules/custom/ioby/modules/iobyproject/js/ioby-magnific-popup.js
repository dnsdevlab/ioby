(function ($) {
    $('.project-gallery').magnificPopup({
        delegate: 'a', // child items selector, by clicking on it popup will open
        // other options
        gallery: {
            // options for gallery
            enabled: true
        },
        preload: [1, 2],

        callbacks: {
            /**
             * Adds custom parameters to markup
             * For example data-description on <a> can be used as mfp-description in markup html
             *
             * @param template
             * @param values
             * @param item
             */
            markupParse: function(template, values, item) {
                values.title = item.el.attr('title');
                values.description = item.el.data('description'); // or item.img.data('description');
            }
        },

        image: {
            markup: '<div class="mfp-figure">' +
            '<div class="mfp-close"></div>' +
            '<div class="mfp-img"></div>' +
            '<div class="mfp-bottom-bar">' +
            '<div class="mfp-title"></div>' +
            '<div class="mfp-description"></div>' +
            '<div class="mfp-counter"></div>' +
            '<div class="mfp-arrows"></div>' +
            '</div>' +
            '</div>'
        },

        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
            '<div class="mfp-close"></div>' +
            '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
            '<div class="mfp-bottom-bar">' +
            '<div class="mfp-title"></div>' +
            '<div class="mfp-description"></div>' +
            '<div class="mfp-counter"></div>' +
            '<div class="mfp-arrows"></div>' +
            '</div>' +
            '</div>'
        }
    });
})(jq213);
