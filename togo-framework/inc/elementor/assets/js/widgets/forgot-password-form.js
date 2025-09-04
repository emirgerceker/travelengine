(function ($) {
  "use strict";

  var ForgotPasswordHandler = function ($scope, $) {
    var $element = $scope.find(".ajax-forgot-password-form");

    $element.on("submit", function (e) {
      e.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        type: "POST",
        url: theme_vars.ajax_url,
        data: formData,
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            $element
              .find(".message")
              .html('<p class="success">' + response.data.message + "</p");
          } else {
            $element
              .find(".message")
              .html('<p class="error">' + response.data.message + "</p");
          }
        },
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-forgot-password-form.default",
      ForgotPasswordHandler
    );
  });
})(jQuery);
