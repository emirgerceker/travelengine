(function ($) {
  "use strict";

  var TripDestinationsFaqsHandler = function ($scope, $) {
    var $element = $scope.find(".togo-td-faqs-question");

    $element.on("click", function (e) {
      e.preventDefault();

      // Find the current item
      var $currentItem = $(this).closest(".togo-td-faqs-item");

      // Close all other items except the clicked one
      $scope
        .find(".togo-td-faqs-item")
        .not($currentItem)
        .removeClass("is-active")
        .find(".togo-td-faqs-answer")
        .slideUp();

      // Toggle the clicked item
      $currentItem.toggleClass("is-active");
      $currentItem.find(".togo-td-faqs-answer").slideToggle();
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-td-faqs.default",
      TripDestinationsFaqsHandler
    );
  });
})(jQuery);
