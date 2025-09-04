(function ($) {
  "use strict";

  var TogoFormBookingHandler = function ($scope, $) {
    const uniqueId = "calendar-" + Math.random().toString(36).substr(2, 9);
    const prices = "-"; // Set default price for days without pricing data
    let currentDate = new Date(); // Start with the current date
    currentDate.setHours(0, 0, 0, 0);
    let displayDate = new Date(
      currentDate.getFullYear(),
      currentDate.getMonth(),
      1
    ); // First day of the display month

    // Parse the pricing data from the data attribute
    let pricingData = [];
    let monthNames = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
    const $calendarWrapper = $scope.find(".calendar-wrapper");
    if ($calendarWrapper.length > 0) {
      pricingData = JSON.parse($calendarWrapper.attr("data-dates"));
    }
    if ($calendarWrapper.attr("data-months-name")) {
      monthNames = JSON.parse($calendarWrapper.attr("data-months-name"));
    }

    // Assign unique IDs to calendar elements
    $calendarWrapper
      .find("#calendar-dates-prev")
      .attr("id", `${uniqueId}-dates-prev`);
    $calendarWrapper
      .find("#month-year-prev")
      .attr("id", `${uniqueId}-month-year-prev`);
    $calendarWrapper
      .find("#calendar-dates-next")
      .attr("id", `${uniqueId}-dates-next`);
    $calendarWrapper
      .find("#month-year-next")
      .attr("id", `${uniqueId}-month-year-next`);
    $calendarWrapper.find("#calendar-next").attr("id", `${uniqueId}-next`);

    function getPriceForDate(date) {
      // Convert date to YYYY-MM-DD format for comparison
      const dateString = date.toISOString().split("T")[0];
      // Get the day of the week as a lowercase string (e.g., "monday")
      const dayOfWeek = date
        .toLocaleDateString("en-US", { weekday: "long" })
        .toLowerCase();

      // Array to store all valid prices for the given date and day
      const validPrices = [];
      var price = "-";

      // Loop through all pricing ranges
      for (let range of pricingData) {
        // Convert start and end dates to Date objects
        const startDate = new Date(range.start_date);
        const endDate =
          range.end_date === "no_end_date"
            ? new Date("9999-12-31")
            : new Date(range.end_date);

        // Normalize time to midnight for accurate comparison
        startDate.setHours(0, 0, 0, 0);
        endDate.setHours(0, 0, 0, 0);

        // Check if the given date falls within the date range
        if (date >= startDate && date <= endDate) {
          // Check if the day of the week is included in trip_days
          if (range.trip_days.includes(dayOfWeek)) {
            // Push the price and formatted price to the list of valid options
            validPrices.push({
              price: parseFloat(range.price), // Use number for comparison
              format_price: range.format_price, // Keep the formatted string for return
            });
          }
        }
      }

      // If no valid prices found, return default
      if (validPrices.length === 0) return price;

      // Find the entry with the lowest price
      const minPriceObj = validPrices.reduce((min, current) => {
        return current.price < min.price ? current : min;
      });

      if (minPriceObj) {
        price = minPriceObj.format_price;
      }

      // Return the formatted price of the lowest priced option
      return price;
    }

    function renderCalendar(date, calendarId, monthYearId) {
      const month = date.getMonth();
      const year = date.getFullYear();

      // Update month and year display
      $scope.find(`#${monthYearId}`).text(`${monthNames[month]} ${year}`);

      // Clear previous dates
      $scope.find(`#${calendarId}`).empty();

      // Determine first day of the month
      const firstDay = new Date(year, month, 1).getDay();
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      // Fill initial empty spaces if month does not start on Monday
      for (let i = 0; i < (firstDay || 7) - 1; i++) {
        $scope.find(`#${calendarId}`).append("<div></div>");
      }

      // Populate days with price
      for (let day = 1; day <= daysInMonth; day++) {
        const dayDate = new Date(year, month, day);
        dayDate.setHours(0, 0, 0, 0);

        const priceForDate = getPriceForDate(dayDate);
        const isDisabled = dayDate <= currentDate || priceForDate === "-";
        const dateDiv = $(`
          <div class="date ${
            isDisabled ? "disabled" : ""
          }" title="${priceForDate}">
            <span>${day}</span>
            <div class="price">${priceForDate}</div>
          </div>
        `);

        // Highlight today's date
        if (
          day === currentDate.getDate() &&
          month === currentDate.getMonth() &&
          year === currentDate.getFullYear()
        ) {
          dateDiv.addClass("today");
        }

        $scope.find(`#${calendarId}`).append(dateDiv);
      }
    }

    function updateCalendars() {
      // Always render the current month into calendar-prev
      renderCalendar(
        displayDate,
        `${uniqueId}-dates-prev`,
        `${uniqueId}-month-year-prev`
      );

      // Check if the screen is larger than 992px before displaying the next month
      if (window.innerWidth >= 992) {
        const nextMonthDate = new Date(
          displayDate.getFullYear(),
          displayDate.getMonth() + 1,
          1
        );
        renderCalendar(
          nextMonthDate,
          `${uniqueId}-dates-next`,
          `${uniqueId}-month-year-next`
        );
        $scope.find(`#${uniqueId}-next`).show();
      } else {
        $scope.find(`#${uniqueId}-next`).hide();
      }
    }

    // Event listeners for month navigation
    $("body").on("click", ".prev-month", function (e) {
      e.preventDefault();
      displayDate.setMonth(displayDate.getMonth() - 1);
      updateCalendars();
    });

    $("body").on("click", ".next-month", function (e) {
      e.preventDefault();
      displayDate.setMonth(displayDate.getMonth() + 1);
      updateCalendars();
    });

    $("body").on("click", ".calendar-check", function (e) {
      e.preventDefault();
      $(this).closest(".calendar-wrapper").fadeOut(0);
    });

    // Initial render
    $(document).ready(function () {
      updateCalendars();
    });

    $scope.on("click", ".calendar .date:not(.disabled)", function (e) {
      const selectedDate = $(this).find("span").text().padStart(2, "0");
      let month, year;

      // Check if the clicked date is in the left (previous) calendar or the right (next) calendar
      if ($(this).closest(`#${uniqueId}-dates-prev`).length) {
        month = String(displayDate.getMonth() + 1).padStart(2, "0");
        year = displayDate.getFullYear();
      } else if ($(this).closest(`#${uniqueId}-dates-next`).length) {
        const nextMonthDate = new Date(
          displayDate.getFullYear(),
          displayDate.getMonth() + 1,
          1
        );
        month = String(nextMonthDate.getMonth() + 1).padStart(2, "0");
        year = nextMonthDate.getFullYear();
      }

      // Format date as "Jul 03, 2025"
      const dateObj = new Date(`${year}-${month}-${selectedDate}`);
      const formattedDate = dateObj.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "2-digit",
      });

      // Update UI and input with selected date
      $(".calendar .date").removeClass("today");
      $(".calendar .date").not(this).removeClass("is-selected");
      $(this).addClass("is-selected");

      $('input[name="booking_date"]').val(formattedDate);
      var layout = $(this).closest(".booking-form").attr("data-layout");
      if (layout == "01") {
        $(this).closest(".form-field").find(".name").text(formattedDate);
      } else if (layout == "03") {
        $(this).closest(".form-field").find(".choose-date").text(formattedDate);
      }

      $(this).closest(".form-field").removeClass("is-active");
      $(this).closest(".form-field").next().addClass("is-active");
    });

    $("body").on(
      "click",
      ".togo-st-form-booking .quantity-input .minus",
      function (e) {
        e.preventDefault();
        let guests = -1;
        var minimum_guests = $(this)
          .closest(".togo-st-form-booking")
          .find('input[name="minimum_guests"]')
          .val();
        $(this)
          .closest(".togo-st-form-booking")
          .find('input[name="guests[]"]')
          .each(function () {
            guests += parseInt($(this).val());
          });
        $(this)
          .closest(".togo-st-form-booking")
          .find(".quantity-input .plus")
          .removeClass("disabled");
        if (parseInt(guests) < parseInt(minimum_guests)) {
          $(this)
            .closest(".togo-st-form-booking")
            .find(".apply-guest")
            .addClass("disabled");
          $(this)
            .closest(".togo-st-form-booking")
            .find("button[type='submit']")
            .prop("disabled", "disabled");
        }
        var input = $(this)
          .parents(".quantity-input")
          .find("input[type='number']");
        var min = input.attr("min");
        if (parseInt(input.val()) > parseInt(min)) {
          input.attr("value", parseInt(input.val()) - 1);
        } else {
          input.attr("value", min);
        }
        if (parseInt(input.val()) == 0) {
          $(this).addClass("disabled");
        }
      }
    );

    $(".togo-st-form-booking__main .form-field .label").on(
      "click",
      function (e) {
        e.preventDefault();
        $(".togo-st-form-booking__main .form-field").removeClass("is-active");
        $(this).closest(".form-field").toggleClass("is-active");
      }
    );

    $(document).on("click", function (event) {
      if (!$(event.target).closest(".togo-st-form-booking__main").length) {
        $(".togo-st-form-booking__main .form-field").removeClass("is-active");
      }
    });

    $("body").on(
      "click",
      ".togo-st-form-booking .quantity-input .plus",
      function (e) {
        e.preventDefault();
        let guests = 1;
        var maximum_guests = $(this)
          .closest(".togo-st-form-booking")
          .find('input[name="maximum_guests"]')
          .val();
        var minimum_guests = $(this)
          .closest(".togo-st-form-booking")
          .find('input[name="minimum_guests"]')
          .val();
        $(this)
          .closest(".togo-st-form-booking")
          .find('input[name="guests[]"]')
          .each(function () {
            guests += parseInt($(this).val());
          });

        if (parseInt(guests) >= parseInt(maximum_guests)) {
          $(this)
            .closest(".togo-st-form-booking")
            .find(".quantity-input .plus")
            .addClass("disabled");
        }

        if (parseInt(guests) >= parseInt(minimum_guests)) {
          $(this)
            .closest(".togo-st-form-booking")
            .find(".apply-guest")
            .removeClass("disabled");
          $(this)
            .closest(".togo-st-form-booking")
            .find("button[type='submit']")
            .prop("disabled", false);
        }
        var input = $(this)
          .parents(".quantity-input")
          .find("input[type='number']");
        var max = input.attr("max");

        if (max !== undefined && max !== "" && max !== "0") {
          if (parseInt(input.val()) < parseInt(max)) {
            input.attr("value", parseInt(input.val()) + 1);
            if (parseInt(max) - parseInt(input.val()) == 0) {
              $(this).addClass("disabled");
            }
          } else {
            input.attr("value", max);
          }
        } else {
          input.attr("value", parseInt(input.val()) + 1);
        }

        $(this)
          .closest(".form-field")
          .find(".quantity-button .notice")
          .fadeOut(0);

        $(this)
          .closest(".quantity-input")
          .find(".minus")
          .removeClass("disabled");
      }
    );

    $("body").on("click", ".apply-guest", function (e) {
      e.preventDefault();

      var guest = 0;
      var guest_text = "";

      $(".guest-box input[type='number']").each(function () {
        guest += parseInt($(this).val());
      });

      if (guest == 0) {
        $(this)
          .closest(".form-field")
          .find(".quantity-button .notice")
          .fadeIn(0);
      } else if (guest <= 1) {
        $(this).closest(".form-field").removeClass("is-active");
        guest_text = guest + " " + theme_vars.guest;
        $(this).closest(".form-field").find(".label .name").text(guest_text);
      } else {
        $(this).closest(".form-field").removeClass("is-active");
        guest_text = guest + " " + theme_vars.guests;
        $(this).closest(".form-field").find(".label .name").text(guest_text);
      }
    });

    $("body").on("submit", ".booking-form", function (e) {
      e.preventDefault();
      var form = $(this);
      var formData = form.serialize();
      // Check offset top of div
      var offsetTop = $(".availability-wrapper").offset().top;
      // Scroll to the top of the availability-wrapper
      $("html, body").animate({ scrollTop: offsetTop }, 100);
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: formData,
        dataType: "json",
        beforeSend: function () {
          var booking_date = form.find("input[name='booking_date']");
          var guest = form.find("input[name='guests[]']");
          $(".availability-content > *").remove();
          if (booking_date.val() == "") {
            booking_date.closest(".form-field").addClass("is-active");
            return false;
          } else if (guest.val() == 0) {
            guest.closest(".form-field").addClass("is-active");
          } else {
            $(
              ".elementor-widget-togo-st-availability .togo-skeleton-wrapper"
            ).addClass("is-active");
          }
        },
        success: function (response) {
          $(
            ".elementor-widget-togo-st-availability .togo-skeleton-wrapper"
          ).removeClass("is-active");
          if (response.success) {
            $(".availability-content").append(response.html);
          } else {
            alert(response.message);
          }
        },
        error: function (xhr, status, error) {
          console.log(error);
        },
      });
    });

    $("body").on("click", ".item-availability__info > label", function (e) {
      e.preventDefault();
      $(
        ".list-availability .item-availability .item-availability__info > label"
      )
        .not(this)
        .closest(".item-availability")
        .removeClass("is-active");
      $(this).closest(".item-availability").addClass("is-active");
      $(this)
        .closest(".item-availability")
        .find('input[name="package"]')
        .prop("checked", true);
      $(this)
        .closest(".item-availability")
        .find('input[name="trip_time"]:first')
        .prop("checked", true);
      $(this)
        .closest(".item-availability")
        .find('input[name="trip_time"]:first')
        .trigger("click");
    });

    $("body").on("click", "input[name='trip_time']", function () {
      var _this = $(this);
      var trip_time = _this.val();
      var booking_date = _this.attr("data-booking-date");
      var trip_id = _this.attr("data-trip-id");
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: {
          action: "get_cancel_time",
          trip_time: trip_time,
          booking_date: booking_date,
          trip_id: trip_id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            _this
              .closest(".item-availability")
              .find(".trip-cancellation-time p")
              .html(response.html);
          }
        },
        error: function (xhr, status, error) {
          console.log(error);
        },
      });
    });

    $("body").on(
      "change",
      ".enquiry-form input, .enquiry-form textarea",
      function () {
        if ($(this).val() !== "") {
          $(this).addClass("has-value");
        } else {
          $(this).removeClass("has-value");
        }
      }
    );

    $("body").on("submit", ".enquiry-form", function (e) {
      e.preventDefault();
      var form = $(this);
      var formData = form.serialize();
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: formData,
        dataType: "json",
        beforeSend: function () {
          form.closest(".togo-st-form-booking").addClass("is-loading");
          form.closest(".togo-modal-content").addClass("is-loading");
          form.find(".notice").removeClass("error success");
          form.find(".notice").text("");
        },
        success: function (response) {
          form.closest(".togo-st-form-booking").removeClass("is-loading");
          form.closest(".togo-modal-content").removeClass("is-loading");
          if (response.success) {
            form.append(response.html);
            form.find(".notice").addClass("success");
          } else {
            form.find(".notice").addClass("error");
          }
          form.find(".notice").text(response.data.message);
        },
      });
    });

    $("body").on("click", ".booking-tabs nav li a", function (e) {
      e.preventDefault();
      var id = $(this).attr("href");
      $(".booking-tabs nav li").removeClass("is-active");
      $(this).closest("li").addClass("is-active");
      $(".booking-tabs .booking-tab-content-item").removeClass("is-active");
      $(id).addClass("is-active");
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-form-booking.default",
      TogoFormBookingHandler
    );
  });
})(jQuery);
