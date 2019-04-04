(function ($) {
    // based on http://drupal.stackexchange.com/questions/62662/
    // Copy hideColumns() method
    var hideColumns = Drupal.tableDrag.prototype.hideColumns;
    Drupal.tableDrag.prototype.hideColumns = function() {
        // Call the original hideColumns() method
        hideColumns.call(this);
        // Remove the 'Show row weights' string
        $('.tabledrag-toggle-weight').text('');
    }

    // Copy showColumns() method
    var showColumns = Drupal.tableDrag.prototype.showColumns;
    Drupal.tableDrag.prototype.showColumns = function () {
        // Call the original showColumns() method
        showColumns.call(this);
        // Remove the 'Hide row weights' string
        $('.tabledrag-toggle-weight').text('');
    }

    if ($.cookie('Drupal.tableDrag.showWeight') == 1) {
        // Make sure the cookie is set to not show the weights
        $.cookie('Drupal.tableDrag.showWeight', 0, {
            path: Drupal.settings.basePath,
            // The cookie expires in one year.
            expires: 365
        });
    }
})(jQuery);
