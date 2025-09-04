(function ($) {
  "use strict";

  var TogoDestinationsHandler = function ($scope, $) {
    var nav_item = $(".main-menu.desktop-menu .destinations-nav .nav-item");
    nav_item.on("mouseenter", function (e) {
      var index = $(this).index();

      $($scope)
        .find(".destinations-content .destinations-box")
        .removeClass("is-active");
      $($scope)
        .find(".destinations-content .destinations-box")
        .eq(index)
        .addClass("is-active");
      $($scope).find(".destinations-nav .nav-item").removeClass("is-active");
      $(this).addClass("is-active");
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-mega-destinations.default",
      TogoDestinationsHandler
    );
  });
})(jQuery);
