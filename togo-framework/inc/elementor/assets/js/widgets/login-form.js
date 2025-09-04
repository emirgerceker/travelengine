(function ($) {
  "use strict";

  var LoginFormHandler = function ($scope, $) {
    var $element = $scope.find(".ajax-login-form");

    $element.on("submit", function (e) {
      e.preventDefault();

      var login_send = $(this).find(".login_send").val();
      var formData = $(this).serialize();

      $.ajax({
        type: "POST",
        url: theme_vars.ajax_url, // URL to WordPress AJAX handler
        data: formData,
        beforeSend: function () {
          $(".login-message").html('<p class="loading">' + login_send + "</p>");
        },
        success: function (response) {
          response = JSON.parse(response);
          if (response.success) {
            $(".login-message").html(
              '<p class="success">' + response.data.message + "</p>"
            );
            window.location.href = response.data.redirect_url;
          } else {
            $(".login-message").html(
              '<p class="error">' + response.data.message + "</p>"
            );
          }
        },
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-login-form.default",
      LoginFormHandler
    );
  });
})(jQuery);
