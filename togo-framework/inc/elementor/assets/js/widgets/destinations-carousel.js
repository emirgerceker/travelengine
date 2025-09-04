(function ($) {
  "use strict";

  var TogoDestinationsCarouselHandler = function ($scope, $) {
    setTimeout(function () {
      $(".destinations-swiper-wrapper.carousel-01 .swiper-slide").each(
        function () {
          var width = $(this).width();
          $(this)
            .find(".togo-destination-item")
            .css("height", width + "px");
        }
      );
    }, 1000);
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-destinations-carousel.default",
      TogoDestinationsCarouselHandler
    );
  });
})(jQuery);
