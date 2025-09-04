(function ($) {
  "use strict";

  var SingleTripFaqsHandler = function ($scope, $) {
    var $element = $scope.find(".togo-st-faqs-question");

    $element.on("click", function (e) {
      e.preventDefault();

      // Find the current item
      var $currentItem = $(this).closest(".togo-st-faqs-item");

      // Close all other items except the clicked one
      $scope
        .find(".togo-st-faqs-item")
        .not($currentItem)
        .removeClass("is-active")
        .find(".togo-st-faqs-answer")
        .slideUp();

      // Toggle the clicked item
      $currentItem.toggleClass("is-active");
      $currentItem.find(".togo-st-faqs-answer").slideToggle();
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-faqs.default",
      SingleTripFaqsHandler
    );
  });
})(jQuery);
