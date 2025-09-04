var TOGO_FRAMEWORK = TOGO_FRAMEWORK || {};

(function ($) {
  "use strict";

  TOGO_FRAMEWORK.ready_element = {
    init: function () {},
  };

  TOGO_FRAMEWORK.load_element = {
    init: function () {
      TOGO_FRAMEWORK.load_element.general();
      TOGO_FRAMEWORK.load_element.custom_select();
      TOGO_FRAMEWORK.load_element.mega_menu();
      TOGO_FRAMEWORK.load_element.price_range();
      TOGO_FRAMEWORK.load_element.trip_filter();
      TOGO_FRAMEWORK.load_element.trip_card();
      TOGO_FRAMEWORK.load_element.trip_map();
      TOGO_FRAMEWORK.load_element.calendar();
      TOGO_FRAMEWORK.load_element.my_account();
      TOGO_FRAMEWORK.load_element.validate_form();
      TOGO_FRAMEWORK.load_element.forget_password();
    },

    tour_maps_intinerary: function () {
      // Get the coordinates from the data attributes
      const $mapElement = $("#togo-st-tour-maps-map");
      const coordinatesData = $mapElement.data("coordinates");
      const lineColor = $mapElement.data("line-color");
      const arrowColor = $mapElement.data("arrow-color");
      const arrowSpeed = parseInt($mapElement.data("arrow-speed"), 10);
      const mapZoom = parseInt($mapElement.data("map-zoom"), 10);

      // Animation function for moving the icon along the polyline
      function animateCircle(line) {
        let count = 0;

        setInterval(() => {
          count = (count + 1) % 200;

          const icons = line.get("icons");
          icons[0].offset = count / 2 + "%";
          line.set("icons", icons);
        }, arrowSpeed);
      }

      // Function to add custom HTML markers with an index
      function CustomMarker(position, map, iconClass, index) {
        this.position = position;
        this.map = map;
        this.index = index;

        // Create the custom div element for the marker with index
        this.div = $("<div>")
          .addClass(`custom-marker ${iconClass}`)
          .css("position", "absolute")
          .html(`<span class="marker-index">${index}</span>`)[0]; // jQuery element converted to DOM

        // Add the custom marker to the map as an overlay
        this.setMap(map);
      }

      // Extend OverlayView to position the custom marker
      CustomMarker.prototype = new google.maps.OverlayView();
      CustomMarker.prototype.onAdd = function () {
        const panes = this.getPanes();
        $(panes.overlayMouseTarget).append(this.div);
      };

      CustomMarker.prototype.draw = function () {
        const overlayProjection = this.getProjection();
        const pos = overlayProjection.fromLatLngToDivPixel(this.position);

        // Position the marker
        $(this.div).css({
          left: `${pos.x - 8}px`, // Center by offsetting half the icon width
          top: `${pos.y - 9}px`, // Center by offsetting half the icon height
        });
      };

      CustomMarker.prototype.onRemove = function () {
        $(this.div).remove();
      };

      // Convert the coordinate strings into an array of objects
      const pathCoordinates = coordinatesData.map((coord) => {
        const [lat, lng] = coord.split(",").map(Number); // Convert to numbers
        return { lat, lng }; // Return an object
      });

      const map = new google.maps.Map($mapElement[0], {
        center: pathCoordinates[0], // Center the map on the first coordinate
        zoom: mapZoom,
        mapTypeId: "terrain",
      });

      // Define the symbol, using one of the predefined paths ('CIRCLE')
      const lineSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        scale: 3,
        strokeColor: arrowColor,
      };

      // Create the polyline and add the symbol to it via the 'icons' property
      const line = new google.maps.Polyline({
        path: pathCoordinates,
        icons: [
          {
            icon: lineSymbol,
            offset: "100%",
          },
        ],
        geodesic: true,
        strokeColor: lineColor,
        strokeOpacity: 1.0,
        strokeWeight: 3,
        map: map,
      });

      // Add a marker at each coordinate along the polyline path
      $.each(pathCoordinates, (index, position) => {
        new CustomMarker(position, map, "custom-marker-icon", index + 1); // Pass index + 1 for 1-based index
      });

      animateCircle(line);

      $(".togo-st-itinerary-item-title").on("click", function (e) {
        e.preventDefault();
        $(this).parent().find(".togo-st-itinerary-item-content").slideToggle();
        $(this).toggleClass("is-active");
      });
    },

    hide_element_on_click_outside: function (elementSelector, elementHide) {
      $(document).on("click", function (event) {
        // Check if the clicked target is not the element or its descendants
        if (!$(event.target).closest(elementSelector).length) {
          if (elementHide) {
            $(elementHide).hide();
          } else {
            $(elementSelector).hide();
          }
        }
      });
    },

    general: function () {
      TOGO_FRAMEWORK.load_element.hide_element_on_click_outside(
        ".togo-select",
        ".togo-select__content"
      );
    },

    custom_select: function () {
      $(".togo-select__label").on("click", function (e) {
        e.preventDefault();
        $(".togo-select__label")
          .not(this)
          .parent()
          .find(".togo-select__content")
          .fadeOut(0);
        $(this).parent().find(".togo-select__content").fadeToggle(0);
      });
    },

    mega_menu: function () {
      $(".desktop-menu .sub-menu.mega-menu").each(function () {
        var $simpleMenu = $(".desktop-menu .sub-menu.simple-menu");
        if ($simpleMenu.length > 0) {
          var top = $simpleMenu.offset().top;
          $(this).css({
            top: top - 10 + "px",
          });
        } else {
          var top =
            $(this).parent().offset().top + $(this).parent().outerHeight();
          $(this).css({
            top: top + "px",
          });
        }
      });
    },

    price_range: function () {
      var $range_one = $('.filter-price input[name="min_price"]'),
        $range_two = $('.filter-price input[name="max_price"]'),
        $min_price = $(".filter-price .show-min-price"),
        $max_price = $(".filter-price .show-max-price"),
        $incl_range = $(".incl-range");

      function updateView() {
        var maxVal = parseInt($(this).attr("max"), 10),
          rangeOneVal = parseInt($range_one.val(), 10),
          rangeTwoVal = parseInt($range_two.val(), 10),
          price = TOGO_FRAMEWORK.load_element.formatPrice($(this).val());

        if ($(this).attr("name") === "min_price") {
          $min_price.text(price);
        } else {
          $max_price.text(price);
        }

        if (rangeOneVal > rangeTwoVal) {
          $incl_range.css({
            width: ((rangeOneVal - rangeTwoVal) / maxVal) * 100 + "%",
            left: (rangeTwoVal / maxVal) * 100 + "%",
          });
        } else {
          $incl_range.css({
            width: ((rangeTwoVal - rangeOneVal) / maxVal) * 100 + "%",
            left: (rangeOneVal / maxVal) * 100 + "%",
          });
        }
      }

      // Initialize view on load
      updateView.call($range_one[0]);
      updateView.call($range_two[0]);

      // Attach event listeners
      $('.filter-price input[type="range"]')
        .on("mouseup", function () {
          $(this).blur();
        })
        .on("mousedown input", function () {
          updateView.call(this);
        });
    },

    number_format: function (number, decimals, dec_point, thousands_sep) {
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
    },

    formatPrice: function (number) {
      var symbol = theme_vars.symbol;
      var currency_position = theme_vars.currency_position;
      var currency_thousand_separator = theme_vars.currency_thousand_separator;
      var currency_decimal_separator = theme_vars.currency_decimal_separator;
      var currency_number_of_decimals = theme_vars.currency_number_of_decimals;

      if (currency_position == "right") {
        return (
          TOGO_FRAMEWORK.load_element.number_format(
            number,
            currency_number_of_decimals,
            currency_decimal_separator,
            currency_thousand_separator
          ) + symbol
        );
      } else if (currency_position == "right_space") {
        return (
          TOGO_FRAMEWORK.load_element.number_format(
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
          TOGO_FRAMEWORK.load_element.number_format(
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
          TOGO_FRAMEWORK.load_element.number_format(
            number,
            currency_number_of_decimals,
            currency_decimal_separator,
            currency_thousand_separator
          )
        );
      }
    },

    trip_filter: function () {
      // Filter toggle
      $("body").on("click", ".filter-item__top", function (e) {
        e.preventDefault();
        $(this).closest(".filter-item").toggleClass("active");
        $(this)
          .closest(".filter-item")
          .find(".filter-item__content")
          .slideToggle();
      });

      // Show more
      $("body").on("click", ".show-more a", function (e) {
        e.preventDefault();
        $(this).closest(".show-more").toggleClass("active");
        $(this)
          .closest(".filter-item__content")
          .find(".filter-checkbox.hide")
          .toggleClass("show");
      });

      // Trigger form submission on any field change
      $(".togo-trip-filter").on("change", "input", function () {
        $(this).closest(".togo-trip-filter").submit();
      });

      // Filter canvas
      $("body").on("click", ".open-filter-canvas", function (e) {
        e.preventDefault();
        $(".filter-canvas-wrapper").addClass("open");
        $("body").addClass("no-scroll");
      });

      // Close filter canvas
      $("body").on("click", ".filter-canvas-overlay", function (e) {
        e.preventDefault();
        $(this).parent().removeClass("open");
        $("body").removeClass("no-scroll");
      });

      // Focus search input location
      $("input[name='location']").focus(function () {
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__result")
          .fadeIn(0);
        $(this)
          .closest(".trip-search-form")
          .find(".field-dates .calendar-wrapper")
          .fadeOut(0);
      });

      // Focus search input guests
      $("input[name='guests']").focus(function () {
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__result")
          .fadeOut(0);
        $(this)
          .closest(".trip-search-form")
          .find(".field-dates .calendar-wrapper")
          .fadeOut(0);
      });

      // Get location
      $(".near-me").on("click", function (e) {
        e.preventDefault();
        var _this = $(this);
        var apiKey = theme_vars.google_map_api;
        // Check if geolocation is supported
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            function (position) {
              var lat = position.coords.latitude;
              var lng = position.coords.longitude;

              // Reverse Geocoding API (Google Maps)
              var geocodeURL = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${apiKey}`;

              // Make an AJAX request to get city details
              $.get(geocodeURL, function (data) {
                if (data.status === "OK") {
                  var results = data.results[0];
                  var city = "";

                  // Find the city in the address components
                  results.address_components.forEach(function (component) {
                    if (component.types.includes("locality")) {
                      city = component.long_name;
                    }
                  });

                  // Populate inputs
                  _this
                    .closest(".trip-search-form")
                    .find("input[name='location']")
                    .val(city);
                } else {
                  alert("Unable to fetch location details.");
                }
              });
            },
            function (error) {
              alert("Error getting location: " + error.message);
            }
          );
        } else {
          alert("Geolocation is not supported by your browser.");
        }
        var text = $(this).find(".near-me__text").text();
        $(this)
          .closest(".trip-search-form")
          .find("input[name='location']")
          .val(text);
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__result")
          .fadeOut(0);
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__remove")
          .css("display", "flex");
      });

      // Keyup location
      $("input[name='location']").on("keyup", function () {
        var value = $(this).val();
        var found = false;
        $(".location-list")
          .find("li")
          .each(function () {
            var text = $(this).text();
            if (text.toLowerCase().indexOf(value.toLowerCase()) > -1) {
              $(this).show();
              found = true;
            } else {
              $(this).hide();
            }
          });
        if (!found) {
          $(".location-list").find(".no-result").removeClass("hide");
        } else {
          $(".location-list").find(".no-result").addClass("hide");
        }
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__remove")
          .css("display", "flex");
      });

      // Select location
      $("body").on("click", ".location-list li a", function (e) {
        e.preventDefault();
        var value = $(this).text();
        $(this)
          .closest(".trip-search-form")
          .find("input[name='location']")
          .val(value);
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__result")
          .fadeOut(0);
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__remove")
          .css("display", "flex");
      });

      // Remove location
      $("body").on("click", ".field-location__remove", function (e) {
        e.preventDefault();
        $(this)
          .closest(".trip-search-form")
          .find('input[name="location"]')
          .val("");
        $(this)
          .closest(".trip-search-form")
          .find(".field-location__result")
          .fadeOut(0);
        $(this)
          .closest(".trip-search-form")
          .find(".location-list")
          .find(".no-result")
          .addClass("hide");
        $(this).closest(".trip-search-form").find(".location-list li").show();
        $(this).css("display", "none");
      });

      // Remove dates
      $("body").on("click", ".field-dates__remove", function (e) {
        e.preventDefault();
        $(this)
          .closest(".trip-search-form")
          .find('input[name="dates"]')
          .val("");
        $(this).css("display", "none");
      });
    },

    trip_card: function () {
      $(
        ".trip-list .type-trip .trip-video, .togo-trip-grid .type-trip .trip-video"
      ).each(function () {
        const $videoBox = $(this);
        const iframe = $videoBox.find("iframe").get(0);
        const video = $videoBox.find("video").get(0);

        if (iframe) {
          const src = iframe.getAttribute("src");
          if (src.includes("vimeo.com")) {
            const player = new Vimeo.Player(iframe);
            let isHovering = false; // trạng thái hover để kiểm tra

            $videoBox.on("mouseenter", function () {
              isHovering = true;
              $videoBox.addClass("playing");

              player
                .setMuted(true)
                .then(() => {
                  if (isHovering) {
                    return player.play();
                  }
                })
                .catch((e) => {
                  console.warn("Vimeo play error:", e);
                });
            });

            $videoBox.on("mouseleave", function () {
              isHovering = false;
              $videoBox.removeClass("playing");

              // Chờ một chút để tránh xung đột play-pause
              setTimeout(() => {
                player.pause().catch((e) => {
                  console.warn("Vimeo pause error:", e);
                });
              }, 100);
            });
          } else if (src.includes("youtube.com") || src.includes("youtu.be")) {
            $videoBox.on("mouseenter", function () {
              $videoBox.addClass("playing");

              iframe.contentWindow.postMessage(
                JSON.stringify({
                  event: "command",
                  func: "playVideo",
                  args: [],
                }),
                "*"
              );
            });

            $videoBox.on("mouseleave", function () {
              $videoBox.removeClass("playing");

              iframe.contentWindow.postMessage(
                JSON.stringify({
                  event: "command",
                  func: "pauseVideo",
                  args: [],
                }),
                "*"
              );
            });
          }
        }

        if (video) {
          // If the video is HTML5
          $videoBox.on("mouseenter", function () {
            $videoBox.addClass("playing");
            video.muted = true;
            video.play().catch(() => {});
          });

          $videoBox.on("mouseleave", function () {
            $videoBox.removeClass("playing");
            video.pause();
            video.currentTime = 0;
          });
        }
      });

      // Add custom width to trip gallery
      $(".trip-list.togo-row .trip-inner").each(function () {
        var width = $(this).width();
        $(this)
          .find(".trip-gallery")
          .css("width", width + "px");
      });

      if ($(window).width() <= 767) {
        $(".trip-list .type-trip-list .trip-inner").each(function () {
          var width = $(this).width();
          $(this)
            .find(".trip-gallery")
            .css("width", width + "px");
        });
      }

      // Swiper
      $(".trip-gallery-slider").TogoSwiper();

      // Add SVG icon to Swiper navigation buttons
      var svgPrevIcon = `
    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.75 5.5L8.25 11L13.75 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
  `;

      var svgNextIcon = `
    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.25 16.5L13.75 11L8.25 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
  `;

      // Left navigation button
      var $prevButton = $(".trip-gallery-slider").find(".swiper-button-prev");
      if (!$prevButton.find("svg").length) {
        $prevButton.append(svgPrevIcon);
      }

      // Right navigation button (reversing the icon for the right direction)
      var $nextButton = $(".trip-gallery-slider").find(".swiper-button-next");
      if (!$nextButton.find("svg").length) {
        $nextButton.append(svgNextIcon);
      }

      // Add to wishlist
      $("body").on("click", ".add-to-wishlist", function (e) {
        e.preventDefault();
        var _this = $(this);
        var trip_id = _this.data("trip-id");
        $.ajax({
          url: theme_vars.ajax_url,
          type: "POST",
          data: {
            action: "togo_add_to_wishlist",
            trip_id: trip_id,
            nonce: theme_vars.togo_add_to_wishlist_nonce,
          },
          beforeSend: function () {
            _this.addClass("loading");
            _this.removeClass("added");
          },
          success: function (response) {
            _this.removeClass("loading");
            _this
              .closest(".trip-wishlist")
              .find(".togo-tooltip-content p")
              .text(response.message);
            if (response.success == true && response.data.add == true) {
              _this.addClass("added");
            }
          },
        });
      });

      // Show map
      $("body").on("click", ".show-map", function (e) {
        e.preventDefault();
        var _this = $(this);
        var trip_id = _this.data("trip-id");

        $.ajax({
          url: theme_vars.ajax_url,
          type: "POST",
          data: {
            action: "togo_get_itinerary",
            trip_id: trip_id,
            security: theme_vars.get_itinerary_nonce,
          },
          beforeSend: function () {
            $(".itinerary-popup").html("");
            _this.find(".togo-svg-icon").addClass("loading");
          },
          success: function (response) {
            $(".itinerary-popup").html(response.data.html);
            TOGO_FRAMEWORK.load_element.tour_maps_intinerary();
            _this.find(".togo-svg-icon").removeClass("loading");
          },
        });
      });
    },

    trip_map: function () {
      $("body").on("click", ".view-full-map", function (e) {
        e.preventDefault();
        $(this).closest(".with-maps").toggleClass("full-maps");
      });
    },

    calendar: function () {
      $('.trip-search-form input[name="dates"]').on("focus", function () {
        $(this)
          .closest(".trip-search-form")
          .find(".calendar-wrapper.full-date")
          .fadeIn(0);
        $(this)
          .closest(".trip-search-form")
          .find(".field-location .field-location__result")
          .fadeOut(0);
      });

      let currentDate = new Date(); // Start with the current date
      currentDate.setHours(0, 0, 0, 0);
      let displayDate = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        1
      ); // First day of the display month

      // Parse the pricing data and month names from attributes
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
      if ($(".calendar-wrapper.full-date").length > 0) {
        pricingData = JSON.parse(
          $(".calendar-wrapper.full-date").attr("data-dates") || "[]"
        );
      }
      if ($(".calendar-wrapper.full-date").attr("data-months-name")) {
        monthNames = JSON.parse(
          $(".calendar-wrapper.full-date").attr("data-months-name")
        );
      }

      let startDate = null;
      let endDate = null;

      function renderCalendar(date, calendarId, monthYearId) {
        const month = date.getMonth();
        const year = date.getFullYear();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Update month and year display
        $(`.calendar-wrapper.full-date #${monthYearId}`).text(
          `${monthNames[month]} ${year}`
        );

        // Clear previous dates
        $(`.calendar-wrapper.full-date #${calendarId}`).empty();

        // Determine first day of the month
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Fill initial empty spaces if the month does not start on Monday
        for (let i = 0; i < (firstDay || 7) - 1; i++) {
          $(`.calendar-wrapper.full-date #${calendarId}`).append("<div></div>");
        }

        // Populate days with date information
        for (let day = 1; day <= daysInMonth; day++) {
          const dayDate = new Date(year, month, day);
          const formattedDate = `${dayDate.getFullYear()}-${String(
            dayDate.getMonth() + 1
          ).padStart(2, "0")}-${String(dayDate.getDate()).padStart(2, "0")}`;

          const dateDiv = $(`<div class="date" data-date="${formattedDate}">
        <span>${day}</span>
      </div>`);

          // Highlight today's date
          if (
            day === currentDate.getDate() &&
            month === currentDate.getMonth() &&
            year === currentDate.getFullYear()
          ) {
            dateDiv.addClass("today");
          }

          // Highlight selected range
          if (
            startDate &&
            endDate &&
            new Date(formattedDate) >= new Date(startDate) &&
            new Date(formattedDate) <= new Date(endDate)
          ) {
            dateDiv.addClass("in-range");
            if (formattedDate === startDate) {
              dateDiv.addClass("first-range");
            }
            if (formattedDate === endDate) {
              dateDiv.addClass("last-range");
            }
          } else if (formattedDate === startDate || formattedDate === endDate) {
            dateDiv.addClass("is-selected");
          }

          // Disable past dates
          if (dayDate <= today) {
            dateDiv.addClass("disabled").attr("aria-disabled", "true");
          }

          $(`.calendar-wrapper.full-date #${calendarId}`).append(dateDiv);
        }
      }

      function updateCalendars() {
        // Always render the current month into calendar-prev
        renderCalendar(displayDate, "calendar-dates-prev", "month-year-prev");

        // Check if the screen is larger than 992px before displaying the next month
        if (window.innerWidth >= 992) {
          const nextMonthDate = new Date(
            displayDate.getFullYear(),
            displayDate.getMonth() + 1,
            1
          );
          renderCalendar(
            nextMonthDate,
            "calendar-dates-next",
            "month-year-next"
          );
          $("#calendar-next").show();
        } else {
          $("#calendar-next").hide();
        }
      }

      function updateInputField() {
        const inputField = $(
          ".trip-search-form .field-dates__input input[name='dates']"
        );
        if (startDate && endDate) {
          inputField.val(`${startDate}, ${endDate}`);
        } else if (startDate) {
          inputField.val(startDate);
        } else {
          inputField.val("");
        }
      }

      // Event listeners for month navigation
      $(".calendar-wrapper .prev-month").on("click", function (e) {
        e.preventDefault();
        displayDate.setMonth(displayDate.getMonth() - 1);
        updateCalendars();
      });

      $(".calendar-wrapper .next-month").on("click", function (e) {
        e.preventDefault();
        displayDate.setMonth(displayDate.getMonth() + 1);
        updateCalendars();
      });

      $("body").on("click", ".calendar-check", function (e) {
        e.preventDefault();
        $(this).closest(".calendar-wrapper").fadeOut(0);
      });

      // Handle date selection for range
      $("body").on(
        "click",
        ".calendar-wrapper.full-date .calendar .date:not(.disabled)",
        function () {
          const selectedDate = $(this).data("date");

          // If no start date is selected, set it
          if (!startDate || (startDate && endDate)) {
            startDate = selectedDate;
            endDate = null; // Reset end date
          } else if (!endDate) {
            // Set the end date if it's valid
            if (new Date(selectedDate) >= new Date(startDate)) {
              endDate = selectedDate;
            } else {
              // If the selected date is before the start date, reset
              startDate = selectedDate;
              endDate = null;
            }
          }

          $(".field-dates__remove").css("display", "flex");

          // Update UI
          updateCalendars();

          updateInputField();
        }
      );

      // Initial render
      $(document).ready(function () {
        updateCalendars();
      });
    },

    my_account: function () {
      $(".dashboard-nav-close").on("click", function (e) {
        e.preventDefault();
        $(this).closest("body").toggleClass("hide-dashboard");
      });
    },

    validate_form: function () {
      $("#togo-login").validate({
        rules: {
          email: {
            required: true,
          },
          password: {
            required: true,
            minlength: 5,
            maxlength: 30,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: ajax_url,
            type: "POST",
            cache: false,
            dataType: "json",
            data: {
              email: $("#ip_email").val(),
              password: $("#ip_password").val(),
              action: "get_login_user",
              security: theme_vars.login_nonce,
            },
            beforeSend: function () {
              $(".form-account p.msg").removeClass(
                "text-error text-success text-warning"
              );
              $(".form-account p.msg").text(theme_vars.send_user_info);
              $("#togo-login p.msg").show();
              $(".form-account .loading-effect").fadeIn();
            },
            success: function (data) {
              $(".form-account p.msg").text(data.messages);
              if (data.success != true) {
                $("#togo-login p.msg").addClass(data.class);
              }
              $(".form-account .loading-effect").fadeOut();
              if (data.redirect != "") {
                window.location.href = data.redirect;
              }
            },
          });
        },
      });
      $("#togo-register").validate({
        rules: {
          reg_firstname: {
            required: true,
          },
          reg_lastname: {
            required: true,
          },
          reg_email: {
            required: true,
            email: true,
          },
          reg_password: {
            required: true,
            minlength: 5,
            maxlength: 20,
          },
          accept_account: {
            required: true,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: ajax_url,
            type: "POST",
            cache: false,
            dataType: "json",
            data: {
              account_type: $('input[name="account_type"]:checked').val(),
              firstname: $("#ip_reg_firstname").val(),
              lastname: $("#ip_reg_lastname").val(),
              email: $("#ip_reg_email").val(),
              password: $("#ip_reg_password").val(),
              action: "get_register_user",
              security: theme_vars.register_nonce,
            },
            beforeSend: function () {
              $(".form-account p.msg").removeClass(
                "text-error text-success text-warning"
              );
              $(".form-account p.msg").text(theme_vars.send_user_info);
              $("#togo-register p.msg").show();
              $(".form-account .loading-effect").fadeIn();
            },
            success: function (data) {
              $(".form-account p.msg").text(data.messages);
              if (data.success != true) {
                $("#togo-register p.msg").addClass(data.class);
              } else {
                if (data.redirect != "") {
                  window.location.href = data.redirect;
                }
              }
              $(".form-account .loading-effect").fadeOut();
            },
          });
        },
      });
      jQuery.extend(jQuery.validator.messages, {
        required: "This field is required",
        remote: "Please fix this field",
        email: "A valid email address is required",
        url: "Please enter a valid URL",
        date: "Please enter a valid date",
        dateISO: "Please enter a valid date (ISO)",
        number: "Please enter a valid number.",
        digits: "Please enter only digits",
        creditcard: "Please enter a valid credit card number",
        equalTo: "Please enter the same value again",
        accept: "Please enter a value with a valid extension",
        maxlength: jQuery.validator.format(
          "Please enter no more than {0} characters"
        ),
        minlength: jQuery.validator.format(
          "Please enter at least {0} characters"
        ),
        rangelength: jQuery.validator.format(
          "Please enter a value between {0} and {1} characters long"
        ),
        range: jQuery.validator.format(
          "Please enter a value between {0} and {1}"
        ),
        max: jQuery.validator.format(
          "Please enter a value less than or equal to {0}"
        ),
        min: jQuery.validator.format(
          "Please enter a value greater than or equal to {0}"
        ),
      });
    },

    forget_password: function ($this) {
      $("#togo_forgetpass").on("click", function (e) {
        e.preventDefault();
        var $form = $(this).parents("form");

        $.ajax({
          type: "post",
          url: ajax_url,
          dataType: "json",
          data: $form.serialize(),
          beforeSend: function () {
            $(".forgot-form p.msg").removeClass(
              "text-error text-success text-warning"
            );
            $(".forgot-form p.msg").text(theme_vars.forget_password);
            $(".forgot-form p.msg").show();
            $(".forgot-form .loading-effect").fadeIn();
          },
          success: function (data) {
            $(".forgot-form p.msg").text(data.message);
            $(".forgot-form p.msg").addClass(data.class);
            $(".forgot-form .loading-effect").fadeOut();
          },
        });
      });
    },
  };

  TOGO_FRAMEWORK.onReady = {
    init: function () {
      TOGO_FRAMEWORK.ready_element.init();
    },
  };

  TOGO_FRAMEWORK.onLoad = {
    init: function () {
      TOGO_FRAMEWORK.load_element.init();
    },
  };

  TOGO_FRAMEWORK.onScroll = {
    init: function () {},
  };

  TOGO_FRAMEWORK.onResize = {
    init: function () {
      TOGO_FRAMEWORK.onResize.trip_card();
    },

    trip_card: function () {
      // Add custom width to trip gallery
      $(".trip-list.togo-row .trip-inner").each(function () {
        var width = $(this).width();
        $(this)
          .find(".trip-gallery")
          .css("width", width + "px");
      });

      if ($(window).width() <= 767) {
        $(".trip-list .type-trip-list .trip-inner").each(function () {
          var width = $(this).width();
          $(this)
            .find(".trip-gallery")
            .css("width", width + "px");
        });
      }
    },
  };

  TOGO_FRAMEWORK.onMouseMove = {
    init: function (e) {},
  };

  $(document).on("ready", TOGO_FRAMEWORK.onReady.init);
  $(window).on("scroll", TOGO_FRAMEWORK.onScroll.init);
  $(window).on("resize", TOGO_FRAMEWORK.onResize.init);
  $(window).on("load", TOGO_FRAMEWORK.onLoad.init);
  $(window).on("mousemove", TOGO_FRAMEWORK.onMouseMove.init);
})(jQuery);
