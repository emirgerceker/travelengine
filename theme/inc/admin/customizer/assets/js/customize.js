(function ($) {
  "use strict";

  wp.customize.bind("ready", function () {
    // Send Data to Preview
    wp.customize.previewer.bind("ready", function () {
      $(".devices button").on("click", function () {
        var device = $(this).attr("data-device");
        wp.customize.previewer.send("device", device);
      });
    });

    // Listen Data from Preview
    wp.customize.previewer.bind("device", function (data) {
      $(".devices .preview-" + data).click();
    });
    wp.customize.previewer.bind("header_column", function (data) {
      wp.customize.control(data).focus();
    });
    wp.customize.previewer.bind("header_row", function (data) {
      wp.customize.control(data).focus();
    });
    wp.customize.previewer.bind("header_control", function (data) {
      if (typeof wp.customize.control(data) != "undefined") {
        wp.customize.control(data).focus();
      }
    });

    $("#togo-customizer-reset").on("click", function (e) {
      e.preventDefault();

      var data = {
        wp_customize: "on",
        action: "customizer_reset",
        nonce: customizeScript.nonce.reset,
      };

      var r = confirm(customizeScript.confirm);

      if (!r) return;

      $(this).attr("disabled", "disabled");

      $.post(customizeScript.ajaxurl, data, function () {
        wp.customize.state("saved").set(true);
        location.reload();
      });
    });

    $("#togo-customizer-import").on("click", function (e) {
      e.preventDefault();

      if (confirm(customizeScript.import)) {
        $("#import-file").on("change", function () {
          $(this).off("change");
          var file = $(this).prop("files")[0];
          var form_data = new FormData();
          form_data.append("action", "customizer_import");
          form_data.append("file", file);

          $.ajax({
            url: customizeScript.ajaxurl,
            dataType: "json",
            type: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
              if (response.status) {
                alert(response.message);
                location.reload();
              }
            },
          });
        });

        $("#import-file").trigger("click");
      }
    });
  });
})(jQuery);
