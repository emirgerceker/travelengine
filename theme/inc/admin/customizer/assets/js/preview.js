(function ($) {
  "use strict";
  // Layout
  wp.customize("layout_content", function (value) {
    // When the value changes.
    value.bind(function (newval) {
      $("body").removeClass("boxed");
      $("body").addClass(newval);
      $("body").css("max-width", "auto");
    });
  });
})(jQuery);
