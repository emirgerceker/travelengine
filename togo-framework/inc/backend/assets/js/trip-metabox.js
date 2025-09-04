(function ($) {
  "use strict";
  $(document).ready(function () {
    $(".trip-nav a").on("click", function (e) {
      e.preventDefault();
      var href = $(this).attr("href");
      $(".trip-nav .trip-nav-item").removeClass("is-active");
      $(this).parent().addClass("is-active");
      $(".trip-metaboxs").find(".trip-section-item").removeClass("is-active");
      $(href).addClass("is-active");
    });
    $(".add-time").on("click", function (e) {
      e.preventDefault();
      var html = $(this).next().html();
      $(this).parent().find(".field-time").append(html);
    });

    $("body").on("click", ".open-modal", function (e) {
      e.preventDefault();
      var modal = $(this).attr("href");
      $(modal).addClass("is-active");
      var package_name = $(this).attr("data-package");
      if (modal === "#modal-package") {
        var modal_title = $(this).attr("data-modal-title");
        var modal_button = $(this).attr("data-modal-button");
        $("#modal-package").find(".modal-title").text(modal_title);
        $("#modal-package").find(".create-package").text(modal_button);
        $("#modal-package").find('input[name="package_name"]').val("");
        $("#modal-package").find('input[name="package_action"]').val("create");
        $("#modal-package").find('input[name="package_old_name"]').val("");
        tinymce.get("package_description").setContent("");
      }
      if (package_name) {
        $(modal).find('input[name="schedule_package_name"]').val(package_name);
      }
    });

    $("body").on("click", ".action-delete-package", function (e) {
      e.preventDefault();
      var result = confirm(togo_trip_metabox.validation_delete);
      if (result) {
        var package_name = $(this).attr("data-package");
        var postid = $(this).attr("data-postid");
        var package_panel = $(this).closest(".package-panel");
        $.ajax({
          type: "POST",
          url: togo_trip_metabox.ajax_url,
          data: {
            action: "delete_package",
            postid: postid,
            package_name: package_name,
          },
          beforeSend: function () {
            package_panel.addClass("is-loading");
          },
          success: function (response) {
            package_panel.removeClass("is-loading");
            package_panel.remove();
          },
        });
      }
    });

    $("body").on("click", ".close-modal, .modal-overlay", function (e) {
      e.preventDefault();
      $(this).closest(".modal").removeClass("is-active");
      $("#modal-schedule-price .modal-title").text(
        togo_trip_metabox.modal_schedule_title
      );
      $("#modal-schedule-price button.save-schedule").text(
        togo_trip_metabox.modal_schedule_button
      );
      $("#modal-schedule-price input[name='start_date']").val("");
      $("#modal-schedule-price input[name='end_date']").val("");
      if ($('input[name="tiered_pricing"]').is(":checked")) {
        $("#modal-schedule-price")
          .find('input[name="tiered_pricing"]')
          .prop("checked", false);
        $(".group-price").removeClass("is-tiered");
        $(".remove_group_price").closest("tr:not(.clone)").remove();
      }
      $("#modal-schedule-price .group-price input").each(function () {
        $(this).val("");
      });
      $("#modal-schedule-price")
        .find('input[name="trip_days[]"]')
        .prop("checked", false);
      $(
        "#modal-schedule-price .opening-hours input[name='opening_hours_days[]']"
      ).prop("checked", false);
      $("#modal-schedule-price .opening-hours input[type='time']").val("");
      $("#modal-schedule-price input[name='schedule_old_start_date']").val("");
      $("#modal-schedule-price input[name='schedule_old_end_date']").val("");
      $("#modal-schedule-price input[name='schedule_action']").val("");
      $(
        "#modal-schedule-price .opening-hours .times .time:first-child input[type='time']"
      ).prop("disabled", true);
      $(
        "#modal-schedule-price .opening-hours .times .remove_opening-hours-time"
      ).trigger("click");
      $("#modal-schedule-price")
        .find("input[name='many_days_start_time']")
        .val("");
      $("#modal-schedule-price .field-time").children("div").remove();
      $(".modal-notice").removeClass("error");
      $(".modal-notice").text("");
    });

    $("body").on("click", ".action-edit", function (e) {
      e.preventDefault();
      var package_name = $(this).attr("data-package");
      var postid = $(this).attr("data-postid");
      $.ajax({
        type: "POST",
        url: togo_trip_metabox.ajax_url,
        data: {
          action: "edit_package",
          postid: postid,
          package_name: package_name,
        },
        success: function (response) {
          response = JSON.parse(response);
          $("#modal-package")
            .find('input[name="package_name"]')
            .val(response.package_name);
          $("#modal-package")
            .find('input[name="package_old_name"]')
            .val(response.package_name);
          $("#modal-package")
            .find('input[name="package_action"]')
            .val("update");
          tinymce
            .get("package_description")
            .setContent(response.package_description);
          $("#modal-package").find(".modal-title").text(response.modal_title);
          $("#modal-package")
            .find(".create-package")
            .text(response.modal_button);
        },
      });
    });

    $("body").on("click", ".create-package", function (e) {
      e.preventDefault();
      var formData = $(this).closest("form").serializeArray();
      var actionFound = false;
      var editorContent = tinymce.get("package_description").getContent();
      var modal_notice = $(this).closest(".modal").find(".modal-notice");
      var package_name = $(this)
        .closest(".modal")
        .find('input[name="package_name"]')
        .val();
      $.each(formData, function (index, field) {
        if (field.name === "action") {
          field.value = "create_package"; // Change the value of 'action' key
          actionFound = true;
        }
        if (field.name === "package_description") {
          field.value = editorContent;
        }
      });

      // If the action field is not found in the form, you can add it manually
      if (!actionFound) {
        formData.push({ name: "action", value: "create_package" });
      }
      $.ajax({
        type: "POST",
        dataType: "json",
        url: togo_trip_metabox.ajax_url,
        data: formData,
        beforeSend: function () {
          modal_notice.text("");
          modal_notice.removeClass("updated");
          modal_notice.removeClass("error");
          if (!package_name) {
            modal_notice.addClass("error");
            modal_notice.text(togo_trip_metabox.validation_package_name);
            return false;
          }
        },
        success: function (response) {
          modal_notice.text(response.message);
          modal_notice.addClass(response.class);
          if (response.success) {
            //reload page
            window.location.reload();
          }
        },
      });
    });

    $("body").on("click", ".save-schedule", function (e) {
      e.preventDefault();
      var formData = $(this).closest("form").serializeArray();
      var actionFound = false;
      var modal = $(this).closest(".modal");
      var modal_notice = $(this).closest(".modal").find(".modal-notice");
      $.each(formData, function (index, field) {
        if (field.name === "action") {
          field.value = "save_trip_schedule"; // Change the value of 'action' key
          actionFound = true;
        }
      });

      // If the action field is not found in the form, you can add it manually
      if (!actionFound) {
        formData.push({ name: "action", value: "save_trip_schedule" });
      }

      $.ajax({
        type: "POST",
        dataType: "json",
        url: togo_trip_metabox.ajax_url,
        data: formData,
        beforeSend: function () {
          var start_date = modal.find('input[name="start_date"]').val();
          var regular_price = modal
            .find('input[name="regular_price[adult][]"]')
            .val();
          var time_unit = modal.find('input[name="time_unit"]:checked').val();
          var price_type = modal.find('input[name="price_type"]:checked').val();
          var trip_days = modal.find('input[name="trip_days[]"]:checked').val();
          var trip_times = modal.find('input[name="trip_times[]"]').val();
          var opening_hours_checked_days = modal
            .find('input[name="opening_hours_days[]"]:checked')
            .val();
          var many_days_start_time = modal
            .find('input[name="many_days_start_time"]')
            .val();
          modal_notice.text("");
          modal_notice.removeClass("updated");
          modal_notice.removeClass("error");
          if (!start_date) {
            modal_notice.text(togo_trip_metabox.validation_start_date);
            modal_notice.addClass("error");
            return false;
          } else if (!regular_price && price_type == "per_person") {
            modal_notice.text(togo_trip_metabox.validation_regular_price);
            modal_notice.addClass("error");
            return false;
          } else if (!trip_days) {
            modal_notice.text(togo_trip_metabox.validation_trip_days);
            modal_notice.addClass("error");
            return false;
          } else if (!trip_times && time_unit == "start_times") {
            modal_notice.text(togo_trip_metabox.validation_trip_times);
            modal_notice.addClass("error");
            return false;
          } else if (
            time_unit == "opening_hours" &&
            !opening_hours_checked_days
          ) {
            modal_notice.text(togo_trip_metabox.validation_trip_times);
            modal_notice.addClass("error");
            return false;
          } else if (time_unit == "many_days" && !many_days_start_time) {
            modal_notice.text(togo_trip_metabox.validation_trip_times);
            modal_notice.addClass("error");
            return false;
          }
        },
        success: function (response) {
          modal_notice.text(response.message);
          modal_notice.addClass(response.class);
          if (response.success) {
            //reload page
            window.location.reload();
          }
        },
      });
    });

    $("body").on("change", "#tiered_pricing", function (e) {
      e.preventDefault();
      if ($(this).is(":checked")) {
        $(".group-price").addClass("is-tiered");
        $(".group-price .origin td:nth-child(2) input").val(0);
      } else {
        $(".group-price").removeClass("is-tiered");
        $(".remove_group_price").closest("tr:not(.clone)").remove();
      }
    });

    $("body").on("click", ".add_group_price", function (e) {
      e.preventDefault();
      var key = $(this).attr("data-key");
      var html = $("#" + key).html();
      var next_tr = $("<tr>" + html + "</tr>");
      $("#" + key).before(next_tr);
    });

    $("body").on("click", ".remove_group_price", function (e) {
      e.preventDefault();
      $(this).closest("tr").remove();
    });

    $("body").on("change", "input[name='end_date']", function (e) {
      e.preventDefault();
      var start_date = $(this)
        .closest(".modal")
        .find('input[name="start_date"]')
        .val();
      var end_date = $(this).val();
      var modal_notice = $(this).closest(".modal").find(".modal-notice");
      var button = $(this).closest(".modal").find("button.save-schedule");
      if (end_date < start_date) {
        modal_notice.text(togo_trip_metabox.validation_end_date);
        modal_notice.addClass("error");
        $(this).addClass("error");
        button.prop("disabled", true);
      } else {
        modal_notice.text("");
        modal_notice.removeClass("error");
        $(this).removeClass("error");
        button.prop("disabled", false);
      }
      if (end_date === "") {
        modal_notice.text("");
        modal_notice.removeClass("error");
        $(this).removeClass("error");
        button.prop("disabled", false);
      }
    });

    $("body").on("keyup", 'input[name="min_guests[adult][]"]', function (e) {
      e.preventDefault();
      var modal_notice = $(this).closest(".modal").find(".modal-notice");
      var button = $(this).closest(".modal").find("button.save-schedule");
      var value = $(this).val();
      var tr = $(this).closest("tr");
      if (tr.hasClass("origin")) {
        if (value == "" || value != 0) {
          modal_notice.text(togo_trip_metabox.validation_first_min_guests);
          modal_notice.addClass("error");
          $(this).addClass("error");
          button.prop("disabled", true);
          return false;
        } else {
          modal_notice.text("");
          modal_notice.removeClass("error");
          $(this).removeClass("error");
          button.prop("disabled", false);
        }
      } else {
        var min = $(this)
          .closest("tr")
          .prev()
          .find('input[name="min_guests[adult][]"]')
          .val();
        var max = $(this)
          .closest("tr")
          .prev()
          .find('input[name="max_guests[adult][]"]')
          .val();
        if (min == "" || max == "") {
          modal_notice.text(togo_trip_metabox.validation_min_guests);
          modal_notice.addClass("error");
          $(this).addClass("error");
          button.prop("disabled", true);
          return false;
        } else if (
          value != "" &&
          min != "" &&
          max != "" &&
          parseInt(value) <= parseInt(max)
        ) {
          modal_notice.text(
            togo_trip_metabox.validation_min_max_guests + " " + max
          );
          modal_notice.addClass("error");
          $(this).addClass("error");
          button.prop("disabled", true);
          return false;
        } else {
          modal_notice.text("");
          modal_notice.removeClass("error");
          $(this).removeClass("error");
          button.prop("disabled", false);
        }
      }
    });

    $("body").on("keyup", 'input[name="max_guests[adult][]"]', function (e) {
      var modal_notice = $(this).closest(".modal").find(".modal-notice");
      var button = $(this).closest(".modal").find("button.save-schedule");
      var value = $(this).val();
      var min = $(this)
        .closest("tr")
        .find('input[name="min_guests[adult][]"]')
        .val();

      if (parseInt(value) < parseInt(min)) {
        modal_notice.text(
          togo_trip_metabox.validation_min_max_guests + " " + min
        );
        modal_notice.addClass("error");
        $(this).addClass("error");
        button.prop("disabled", true);
        return false;
      } else {
        modal_notice.text("");
        modal_notice.removeClass("error");
        $(this).removeClass("error");
        button.prop("disabled", false);
      }
    });

    $("body").on("click", "input[name='time_unit']", function () {
      var val = $(this).val();
      if (val == "start_times") {
        $(".start-times").removeClass("hide");
        $(".opening-hours").addClass("hide");
        $(".many-days").addClass("hide");
      } else if (val == "opening_hours") {
        $(".start-times").addClass("hide");
        $(".opening-hours").removeClass("hide");
        $(".many-days").addClass("hide");
      } else if (val == "many_days") {
        $(".start-times").addClass("hide");
        $(".opening-hours").addClass("hide");
        $(".many-days").removeClass("hide");
      }
    });

    $("body").on(
      "change",
      ".opening-hours .day-name input[type='checkbox']",
      function () {
        if ($(this).is(":checked")) {
          $(this)
            .closest(".opening-hours-item")
            .find('input[type="time"]')
            .prop("disabled", false);
        } else {
          $(this)
            .closest(".opening-hours-item")
            .find('input[type="time"]')
            .prop("disabled", true);
        }
      }
    );

    $("body").on("click", ".add-opening-hours-time", function (e) {
      e.preventDefault();
      var html = $(this)
        .closest(".opening-hours-item")
        .find(".field-time-clone")
        .html();
      $(this).closest(".opening-hours-item").find(".times").append(html);
    });

    $("body").on("click", ".remove_opening-hours-time", function (e) {
      e.preventDefault();
      $(this).closest(".time").remove();
    });

    $("body").on("click", ".action-delete-schedule", function (e) {
      e.preventDefault();
      var result = confirm(togo_trip_metabox.validation_delete);
      if (result) {
        var package_name = $(this).attr("data-package");
        var post_id = $(this).attr("data-postid");
        var start_date = $(this).attr("data-start-date");
        var end_date = $(this).attr("data-end-date");
        var package_schedule = $(this).closest(".package-schedule");
        $.ajax({
          type: "POST",
          url: togo_trip_metabox.ajax_url,
          data: {
            action: "delete_schedule",
            post_id: post_id,
            start_date: start_date,
            end_date: end_date,
            package_name: package_name,
          },
          beforeSend: function () {
            package_schedule.addClass("is-loading");
          },
          success: function (response) {
            package_schedule.removeClass("is-loading");
            package_schedule.remove();
          },
        });
      }
    });

    $("body").on("click", ".action-edit-schedule", function (e) {
      e.preventDefault();
      var package_name = $(this).attr("data-package");
      var post_id = $(this).attr("data-postid");
      var start_date = $(this).attr("data-start-date");
      var end_date = $(this).attr("data-end-date");
      $.ajax({
        type: "POST",
        url: togo_trip_metabox.ajax_url,
        data: {
          action: "edit_schedule",
          post_id: post_id,
          start_date: start_date,
          end_date: end_date,
          package_name: package_name,
        },
        beforeSend: function () {
          $(".origin-price").remove();
        },
        success: function (response) {
          response = JSON.parse(response);
          console.log(response);
          $("#modal-schedule-price")
            .find('input[name="schedule_start_date"]')
            .val(response.start_date);
          $("#modal-schedule-price")
            .find('input[name="schedule_old_start_date"]')
            .val(response.start_date);
          $("#modal-schedule-price")
            .find('input[name="schedule_end_date"]')
            .val(response.end_date);
          $("#modal-schedule-price")
            .find('input[name="schedule_old_end_date"]')
            .val(response.end_date);
          $("#modal-schedule-price")
            .find('input[name="schedule_action"]')
            .val("update");
          $("#modal-schedule-price")
            .find('input[name="start_date"]')
            .val(response.start_date);
          if (response.end_date == "no_end_date") {
            $("#modal-schedule-price").find('input[name="end_date"]').val("");
          } else {
            $("#modal-schedule-price")
              .find('input[name="end_date"]')
              .val(response.end_date);
          }
          $.each(response.pricing_clone, function (index, value) {
            
            for (var i = 0; i <= value; i++) {
              if (i > 0) {
                const origin = $(".group-price").find(".origin").last();
                var originHtml = $(origin).html();
                var origin_tr = $("<tr class='origin-price'>" + originHtml + "</tr>");
                origin.after(origin_tr);                
              }
              if (i < value) {
                $(".add_group_price[data-key=" + index + "]").trigger("click");
              }
            }
          });
          $.each(response.pricing, function (index, value) {
            $.each(value, function (i, v) {
              if (v != "" && v != 0) {
                $("#modal-schedule-price")
                  .find('input[name="' + index + '[]"]')
                  .eq(i)
                  .val(v);
              }
            });
          });
          $("#modal-schedule-price")
            .find('input[value="' + response.time_unit + '"]')
            .trigger("click");
          $("#modal-schedule-price")
            .find('input[value="' + response.price_type + '"]')
            .trigger("click");
          if (response.time_unit == "start_times") {
            $("#modal-schedule-price").find(".start-times").removeClass("hide");
            $("#modal-schedule-price").find(".opening-hours").addClass("hide");
            $("#modal-schedule-price").find(".many-days").addClass("hide");
            if (
              $("#modal-schedule-price .field-time").children("div").length <= 0
            ) {
              $.each(response.trip_times, function (index, value) {
                if (value != "") {
                  $("#modal-schedule-price .field-time").append(
                    '<div class="time-wrapper"><input type="time" name="trip_times[]" value="' +
                      value +
                      '"><a href="#" class="remove-time"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.5 5.50004L5.5 16.5M5.49995 5.5L16.4999 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a></div>'
                  );
                }
              });
            }
          } else if (response.time_unit == "opening_hours") {
            $("#modal-schedule-price").find(".start-times").addClass("hide");
            $("#modal-schedule-price")
              .find(".opening-hours")
              .removeClass("hide");
            $("#modal-schedule-price").find(".many-days").addClass("hide");
            $("#modal-schedule-price")
              .find(".opening-hours")
              .html(response.opening_hours);
          } else if (response.time_unit == "many_days") {
            $("#modal-schedule-price")
              .find("input[name='many_days_start_time']")
              .val(response.many_days_start_time);
            $("#modal-schedule-price").find(".start-times").addClass("hide");
            $("#modal-schedule-price").find(".opening-hours").addClass("hide");
            $("#modal-schedule-price").find(".many-days").removeClass("hide");
          }
          if (response.tiered_pricing == "on") {
            $("#modal-schedule-price")
              .find('input[name="tiered_pricing"]')
              .prop("checked", true);
            $("#tiered_pricing").trigger("change");
          } else {
            $("#modal-schedule-price")
              .find('input[name="tiered_pricing"]')
              .prop("checked", false);
          }
          $.each(response.trip_days, function (index, value) {
            $(
              "#modal-schedule-price .form-group-days input[value='" +
                value +
                "']"
            ).prop("checked", true);
          });

          $("#modal-schedule-price")
            .find(".modal-title")
            .text(response.modal_title);
          $("#modal-schedule-price")
            .find(".save-schedule")
            .text(response.modal_button);
        },
      });
    });

    $("body").on("click", ".remove-time", function (e) {
      e.preventDefault();
      $(this).closest(".time-wrapper").remove();
    });

    $("body").on("click", "input[name='price_type']", function () {
      var val = $(this).val();
      if (val == "per_group") {
        $("tbody.price-per_person").addClass("hide");
        $("tbody.price-per_group").removeClass("hide");
      } else {
        $("tbody.price-per_group").addClass("hide");
        $("tbody.price-per_person").removeClass("hide");
      }
    });
  });
})(jQuery);
