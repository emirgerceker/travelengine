(function ($) {
  "use strict";

  var TripTabHandler = function ($scope, $) {
    var $link = $scope.find(".togo-trip-tab-link");

    $link.on("click", function (e) {
      e.preventDefault();

      // Find the current item
      var $currentItem = $(this).attr("data-tab");

      // Ajax request to fetch the content based on the clicked tab
      $.ajax({
        url: trip_tab_data.ajax_url,
        type: "POST",
        data: {
          action: "togo_trip_tab_content",
          data_tab: $currentItem,
          security: trip_tab_data.nonce,
        },
        beforeSend: function () {
          // Optionally show a loading indicator
          $scope.find(".togo-trip-grid").addClass("loading");
        },
        success: function (response) {
          // Update the content area with the response
          $scope.find(".togo-trip-grid").html(response);
          // Remove loading indicator
          $scope.find(".togo-trip-grid").removeClass("loading");
          // Remove active class from all links and add to the clicked one
          $link.removeClass("active");
          $(e.currentTarget).addClass("active");
        },
        error: function () {
          console.error("Failed to load tab content.");
        },
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-trip-tab.default",
      TripTabHandler
    );
  });
})(jQuery);
