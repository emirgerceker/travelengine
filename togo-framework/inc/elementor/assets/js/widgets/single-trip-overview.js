(function ($) {
  "use strict";

  var TogoOverviewHandler = function ($scope, $) {
    var read_more = $scope.find(".togo-st-overview-read-more");
    read_more.on("click", function (e) {
      e.preventDefault();
      $(this).parent().find(".description").removeClass("enable-readmore");
      $(this).remove();
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-overview.default",
      TogoOverviewHandler
    );
  });
})(jQuery);
