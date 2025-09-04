(function ($) {
  "use strict";

  var SingleTripAvailabilityHandler = function ($scope, $) {
    $("body").on("click", ".add-to-cart", function (e) {
      e.preventDefault();
      var _this = $(this);
      var form = _this.next("form");
      var item = $(".item-availability.is-active");
      var package_name = item.find("input[name='package']").val();
      var trip_id = item.find("input[name='trip_id']").val();
      var booking_date = item.find("input[name='booking_date']").val();
      var service_quantity = item
        .find('input[name="service_quantity[]"]')
        .map(function () {
          return $(this).val();
        })
        .get();
      var guests = $(".booking-form")
        .find('input[name="guests[]"]')
        .map(function () {
          return $(this).val();
        })
        .get();
      var guests_price = item.find("input[name='guests_price']").val();
      var total_price = item.find("input[name='total_price']").val();
      var pricing_type = item.find("input[name='pricing_type']").val();
      var time_type = item.find("input[name='time_type']").val();
      var time = item.find("input[name='trip_time']:checked").val();
      var opening_hours = item.find("input[name='opening_hours']").val();
      var many_days_start_time = item
        .find("input[name='many_days_start_time']")
        .val();
      var nonce = item.find("input[name='nonce_cart']").val();
      _this.addClass("loading");
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
          action: "trip_add_to_cart",
          package_name: package_name,
          trip_id: trip_id,
          booking_date: booking_date,
          service_quantity: service_quantity,
          guests: guests,
          guests_price: guests_price,
          total_price: total_price,
          pricing_type: pricing_type,
          time_type: time_type,
          time: time,
          opening_hours: opening_hours,
          many_days_start_time: many_days_start_time,
          nonce: nonce,
        },
        success: function (response) {
          _this.removeClass("loading");
          if (response.success) {
            form.trigger("submit");
          }
        },
      });
    });

    $("body").on("click", ".book-now", function (e) {
      e.preventDefault();
      var _this = $(this);
      var form = _this.next("form");
      var item = $(".item-availability.is-active");
      var package_name = item.find("input[name='package']").val();
      var trip_id = item.find("input[name='trip_id']").val();
      var booking_date = item.find("input[name='booking_date']").val();
      var service_quantity = item
        .find('input[name="service_quantity[]"]')
        .map(function () {
          return $(this).val();
        })
        .get();
      var guests = $(".booking-form")
        .find('input[name="guests[]"]')
        .map(function () {
          return $(this).val();
        })
        .get();
      var guests_price = item.find("input[name='guests_price']").val();
      var total_price = item.find("input[name='total_price']").val();
      var pricing_type = item.find("input[name='pricing_type']").val();
      var time_type = item.find("input[name='time_type']").val();
      var time = item.find("input[name='trip_time']:checked").val();
      var opening_hours = item.find("input[name='opening_hours']").val();
      var many_days_start_time = item
        .find("input[name='many_days_start_time']")
        .val();
      var nonce = item.find("input[name='nonce_checkout']").val();
      _this.addClass("loading");
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
          action: "trip_book_now",
          package_name: package_name,
          trip_id: trip_id,
          booking_date: booking_date,
          service_quantity: service_quantity,
          guests: guests,
          guests_price: guests_price,
          total_price: total_price,
          pricing_type: pricing_type,
          time_type: time_type,
          time: time,
          opening_hours: opening_hours,
          many_days_start_time: many_days_start_time,
          nonce: nonce,
        },
        success: function (response) {
          _this.removeClass("loading");
          if (response.success) {
            form.trigger("submit");
          }
        },
      });
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
      // Ensure proper types
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      const n = !isFinite(+number) ? 0 : +number;
      const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
      const sep = thousands_sep === undefined ? "," : thousands_sep;
      const dec = dec_point === undefined ? "." : dec_point;
      let s = "";

      const toFixedFix = function (n, prec) {
        const k = Math.pow(10, prec);
        return "" + Math.round(n * k) / k;
      };

      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
      }
      return s.join(dec);
    }

    function formatPrice(number) {
      var symbol = theme_vars.symbol;
      var currency_position = theme_vars.currency_position;
      var currency_thousand_separator = theme_vars.currency_thousand_separator;
      var currency_decimal_separator = theme_vars.currency_decimal_separator;
      var currency_number_of_decimals = theme_vars.currency_number_of_decimals;

      if (currency_position == "right") {
        return (
          number_format(
            number,
            currency_number_of_decimals,
            currency_decimal_separator,
            currency_thousand_separator
          ) + symbol
        );
      } else if (currency_position == "right_space") {
        return (
          number_format(
            number,
            currency_number_of_decimals,
            currency_decimal_separator,
            currency_thousand_separator
          ) +
          " " +
          symbol
        );
      } else if (currency_position == "left") {
        return (
          symbol +
          number_format(
            number,
            currency_number_of_decimals,
            currency_decimal_separator,
            currency_thousand_separator
          )
        );
      } else if (currency_position == "left_space") {
        return (
          symbol +
          " " +
          number_format(
            number,
            currency_number_of_decimals,
            currency_decimal_separator,
            currency_thousand_separator
          )
        );
      }
    }

    $("body").on("click", ".trip-service .quantity-input .minus", function (e) {
      e.preventDefault();
      var input = $(this)
        .parents(".quantity-input")
        .find("input[type='number']");
      var min = input.attr("min");
      if (parseInt(input.val()) > parseInt(min)) {
        input.attr("value", parseInt(input.val()) - 1);
      } else {
        input.attr("value", min);
      }
      var price = parseInt(
        $(
          ".item-availability.is-active input[name='total_price_without_service']"
        ).val()
      );
      var total_price = $(".item-availability.is-active .total-price");
      $(
        ".item-availability.is-active .item-availability__price ul li.service-view"
      ).remove();
      $(".item-availability.is-active input[name='trip_services[]']").each(
        function () {
          if ($(this).is(":checked")) {
            var qty = $(this)
              .closest(".trip-service")
              .find('input[type="number"]')
              .val();
            var service_price = $(this)
              .closest(".trip-service")
              .find('input[type="number"]')
              .attr("data-price");
            var service_name = $(this)
              .closest(".trip-service")
              .find(".trip-service__name label")
              .text();
            price += parseInt(qty) * parseInt(service_price);

            if (qty > 0) {
              $(
                ".item-availability.is-active .item-availability__price ul"
              ).append(
                "<li class='service-view'>" +
                  formatPrice(service_price) +
                  " x " +
                  qty +
                  " " +
                  service_name +
                  "</li>"
              );
            }
          }
        }
      );
      $(".item-availability.is-active input[name='total_price']").val(price);
      total_price.text(formatPrice(price));
    });

    $("body").on("click", ".trip-service .quantity-input .plus", function (e) {
      e.preventDefault();
      var input = $(this)
        .parents(".quantity-input")
        .find("input[type='number']");
      var max = input.attr("max");
      if (max !== undefined && max !== "" && max !== "0") {
        if (parseInt(input.val()) < parseInt(max)) {
          input.attr("value", parseInt(input.val()) + 1);
        } else {
          input.attr("value", max);
        }
      } else {
        input.attr("value", parseInt(input.val()) + 1);
      }
      var price = parseInt(
        $(
          ".item-availability.is-active input[name='total_price_without_service']"
        ).val()
      );
      var total_price = $(".item-availability.is-active .total-price");
      $(
        ".item-availability.is-active .item-availability__price ul li.service-view"
      ).remove();
      $(".item-availability.is-active input[name='trip_services[]']").each(
        function () {
          if ($(this).is(":checked")) {
            var qty = $(this)
              .closest(".trip-service")
              .find('input[type="number"]')
              .val();
            var service_price = $(this)
              .closest(".trip-service")
              .find('input[type="number"]')
              .attr("data-price");
            var service_name = $(this)
              .closest(".trip-service")
              .find(".trip-service__name label")
              .text();
            price += parseInt(qty) * parseInt(service_price);

            if (qty > 0) {
              $(
                ".item-availability.is-active .item-availability__price ul"
              ).append(
                "<li class='service-view'>" +
                  formatPrice(service_price) +
                  " x " +
                  qty +
                  " " +
                  service_name +
                  "</li>"
              );
            }
          }
        }
      );
      $(".item-availability.is-active input[name='total_price']").val(price);
      total_price.text(formatPrice(price));
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-availability.default",
      SingleTripAvailabilityHandler
    );
  });
})(jQuery);
