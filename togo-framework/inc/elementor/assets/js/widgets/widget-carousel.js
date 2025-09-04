(function ($) {
  "use strict";

  var SwiperHandler = function ($scope, $) {
    var $element = $scope.find(".togo-swiper");
    var customNav = $element.attr("data-custom-nav");
    // Initialize the Swiper
    $element.TogoSwiper();

    if (customNav) return;
    // Add SVG icon to Swiper navigation buttons
    var svgPrevIcon = `
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.75 5.5L8.25 11L13.75 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
    `;

    var svgNextIcon = `
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.25 16.5L13.75 11L8.25 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
    `;

    // Left navigation button
    var $prevButton = $element.find(".swiper-button-prev");
    if (!$prevButton.find("svg").length) {
      $prevButton.append(svgPrevIcon);
    }

    // Right navigation button (reversing the icon for the right direction)
    var $nextButton = $element.find(".swiper-button-next");
    if (!$nextButton.find("svg").length) {
      $nextButton.append(svgNextIcon);
    }
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-topbar-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-gallery.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-services.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-trip-destinations-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-trip-destinations-rates-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-testimonials-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-trip-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-trip-related-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-destinations-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-posts-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-modern-carousel.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-trip-banner.default",
      SwiperHandler
    );
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-activities-carousel.default",
      SwiperHandler
    );
  });
})(jQuery);
