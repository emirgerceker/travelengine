(function ($) {
  "use strict";

  var MiniCartHandler = function ($scope, $) {
    var minicart = $scope.find(".togo-minicart-button");
    var lightbox_bg = $scope.find(".mini-cart-lightbox-bg");
    var lightbox_close = $scope.find(".mini-cart-lightbox-close");

    minicart.on("click", function (e) {
      e.preventDefault();
      $(this).closest(".togo-mini-cart").addClass("is-active");
      $("body").addClass("opened-minicart");
    });

    lightbox_close.on("click", function (e) {
      e.preventDefault();
      $(this).closest(".togo-mini-cart").removeClass("is-active");
      $("body").removeClass("opened-minicart");
    });

    lightbox_bg.on("click", function (e) {
      e.preventDefault();
      $(this).closest(".togo-mini-cart").removeClass("is-active");
      $("body").removeClass("opened-minicart");
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-mini-cart.default",
      MiniCartHandler
    );
  });
})(jQuery);
