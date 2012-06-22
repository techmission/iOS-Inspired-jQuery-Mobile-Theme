// All dialog buttons should close their parent dialog.
// Wrapped in a closure so that the code is compatible with Drupal.
// @see http://drupal.org/node/171213
(function ($) {
  $(".ui-dialog button").live("click", function() {		
    $("[data-role='dialog']").dialog("close");		
  });
})(jQuery);