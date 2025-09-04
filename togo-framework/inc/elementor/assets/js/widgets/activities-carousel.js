(function ($) {
  "use strict";

  var TogoActivitiesCarouselHandler = function ($scope, $) {
    setTimeout(function () {
      $(".activities-swiper-wrapper.layout-02 .swiper-slide").each(function () {
        var width = $(this).width();
        $(this)
          .find(".togo-activity-layout-2 img")
          .css("height", width + "px");
      });
    }, 1000);
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-activities-carousel.default",
      TogoActivitiesCarouselHandler
    );
  });
})(jQuery);
