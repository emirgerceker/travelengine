(function ($) {
  "use strict";

  var TogoItineraryHandler = function ($scope, $) {
    var title = $scope.find(".togo-st-itinerary-item-title");
    title.on("click", function (e) {
      e.preventDefault();
      $(this).parent().find(".togo-st-itinerary-item-content").slideToggle();
      $(this).toggleClass("is-active");
    });

    $(".expand-all").on("click", function (e) {
      e.preventDefault();
      var id = $(this).attr("href");
      var expand_text = itinerary_data.expand_text;
      var collapse_text = itinerary_data.collapse_text;
      $(this)
        .find(".togo-st-heading-link-title")
        .text(
          $(this).find(".togo-st-heading-link-title").text() == expand_text
            ? collapse_text
            : expand_text
        );
      $(id).find(".togo-st-itinerary-item-content").slideToggle();
      $(id).find(".togo-st-itinerary-item-title").toggleClass("is-active");
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-itinerary.default",
      TogoItineraryHandler
    );
  });
})(jQuery);
