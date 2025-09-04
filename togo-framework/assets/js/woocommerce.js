(function ($) {
  "use strict";

  $(document).ready(function () {
    $(".woocommerce-filter-form").on("change", function () {
      var checkedValues = [];
      $(this)
        .find("input[name='product_cat[]']:checked")
        .each(function () {
          checkedValues.push($(this).val());
        });
    });
    $(document.body).on("updated_checkout", function () {
      $(".open-coupon").on("click", function (e) {
        e.preventDefault();
        $(this).parent().find(".coupon-field").slideToggle();
      });
      $("#togo-coupon-code").on("click", function (e) {
        e.preventDefault();
        var couponCode = $("#custom_coupon_code").val();

        if (!couponCode) {
          return;
        }

        $.ajax({
          url: wc_checkout_params.ajax_url,
          type: "POST",
          data: {
            action: "apply_custom_coupon",
            coupon_code: couponCode,
            security: theme_vars.apply_coupon_nonce,
          },
          beforeSend: function () {
            $(".coupon-notice").css("display", "none");
            $(".coupon-notice").css("margin-top", "0");
            $(".coupon-notice").removeClass("success error");
          },
          success: function (response) {
            $(".coupon-notice").css("display", "block");
            $(".coupon-notice").css("margin-top", "8px");
            if (response.success) {
              $(".coupon-notice").addClass("success");
              $(".coupon-notice").text(
                response.data.title + ": " + response.data.message
              );
              location.reload(); // Reload checkout to reflect applied coupon
            } else {
              $(".coupon-notice").addClass("error");
              $(".coupon-notice").text(
                response.data.title + ": " + response.data.message
              );
            }
          },
          error: function () {
            alert("An unexpected error occurred. Please try again.");
          },
        });
      });
    });

    $(".schedule-time").each(function () {
      var _this = $(this);
      var time = _this.data("time");
      // Get the target date from the data-time attribute
      var targetDate = time * 1000;

      // Update the countdown every 1 second
      var countdownInterval = setInterval(function () {
        // Get the current date and time
        var now = new Date().getTime();

        // Calculate the time remaining
        var remainingTime = targetDate - now;

        // Calculate hours, minutes, and seconds
        var hours = Math.floor(
          (remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        );
        var minutes = Math.floor(
          (remainingTime % (1000 * 60 * 60)) / (1000 * 60)
        );
        var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        // Format the timer as H:i:s
        var formattedTime;
        if (hours > 0) {
          formattedTime =
            (hours < 10 ? "0" : "") +
            hours +
            ":" +
            (minutes < 10 ? "0" : "") +
            minutes +
            ":" +
            (seconds < 10 ? "0" : "") +
            seconds;
        } else {
          formattedTime =
            (minutes < 10 ? "0" : "") +
            minutes +
            ":" +
            (seconds < 10 ? "0" : "") +
            seconds;
        }
        // Display the result
        _this.find("span").text(formattedTime);

        // If the countdown is over, display "EXPIRED"
        if (remainingTime < 0) {
          clearInterval(countdownInterval);
          _this.closest(".item").remove();
        }
      }, 1000);
    });
  });
})(jQuery);
