(function ($) {
  "use strict";

  var TestimonialsSwiperHandler = function ($scope, $) {};

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-testimonials-carousel.default",
      TestimonialsSwiperHandler
    );
  });
})(jQuery);
