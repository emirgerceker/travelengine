(function ($) {
  "use strict";

  var TopbarSwiperHandler = function ($scope, $) {
    var $element = $scope.find(".togo-swiper-close");

    $element.on("click", function () {
      $element.parents(".topbar-swiper-wrapper").remove();
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-topbar-carousel.default",
      TopbarSwiperHandler
    );
  });
})(jQuery);
