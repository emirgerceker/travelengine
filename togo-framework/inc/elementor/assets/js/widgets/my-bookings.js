(function ($) {
  "use strict";

  var MyBookingsHandler = function ($scope, $) {
    var status = $scope.find("select[name='status']");
    // auto submit form when status is changed
    status.on("change", function () {
      $(this).closest("form").submit();
    });

    $(".action-cancel-booking").on("click", function (event) {
      event.preventDefault();
      var _this = $(this);
      var booking_id = $(".togo-modal-cancel-booking")
        .find("input[name='booking_id']")
        .val();
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: {
          action: "cancel_booking",
          security: theme_vars.cancel_booking_nonce,
          booking_id: booking_id,
        },
        beforeSend: function () {
          _this.addClass("loading");
        },
        success: function (response) {
          _this.removeClass("loading");
          if (response.success) {
            $(".togo-modal-cancel-booking")
              .find(".togo-modal-footer")
              .prepend('<p class="notice">' + response.data.message + "</p>");
            // Reload page
            window.location.reload();
          } else {
            alert(theme_vars.failed_to_cancel_booking);
          }
        },
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-my-bookings.default",
      MyBookingsHandler
    );
  });
})(jQuery);
