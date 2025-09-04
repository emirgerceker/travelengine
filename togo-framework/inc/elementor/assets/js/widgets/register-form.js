(function ($) {
  "use strict";

  var RegisterFormHandler = function ($scope, $) {
    var $element = $scope.find(".ajax-register-form");

    $element.on("submit", function (e) {
      e.preventDefault();

      var register_send = $(this).find(".register_send").val();

      var formData = $(this).serialize();
      $.ajax({
        type: "POST",
        url: theme_vars.ajax_url, // URL to WordPress AJAX handler
        data: formData,
        beforeSend: function () {
          $element
            .find(".register-message")
            .html('<p class="loading">' + register_send + "</p>");
        },
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            $element
              .find(".register-message")
              .html('<p class="success">' + response.data.message + "</p>");
            window.location.href = response.data.redirect_url;
          } else {
            $element
              .find(".register-message")
              .html('<p class="error">' + response.data.message + "</p>");
          }
        },
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-register-form.default",
      RegisterFormHandler
    );
  });
})(jQuery);
