(function ($) {
  "use strict";

  var $body = $("body");

  var ajax_url = togo_panel_vars.ajax_url;

  function purchase_form() {
    $(".hidden-code input").prop("disabled", true);

    $(".purchase-form.verified .purchase-icon").on("click", function () {
      if ($(this).closest(".purchase-form").hasClass("hidden-code")) {
        $(this).closest(".purchase-form").removeClass("hidden-code");
        $(".purchase-form input").prop("disabled", false);
      } else {
        $(this).closest(".purchase-form").addClass("hidden-code");
        $(".purchase-form input").prop("disabled", true);
      }
    });
  }

  function plugin_action() {
    $(".togo-plugin-action").on("click", function (e) {
      e.preventDefault();

      var $el = $(e.currentTarget),
        $pluginsTable = $(".togo-box--plugins table"),
        $pluginRow = $el.closest(".togo-plugin--required"),
        pluginAction = $el.attr("data-plugin-action"),
        $icon = $pluginRow.find("i, .svg-inline--fa"),
        ajaxData = {
          action: "process_plugin_actions",
          slug: $el.attr("data-slug"),
          source: $el.attr("data-source"),
          plugin_action: $el.attr("data-plugin-action"),
          _wpnonce: $el.attr("data-nonce"),
        };

      if ("deactivate-plugin" === pluginAction) {
        $el.html('<i class="fas fa-spin la-spin"></i>Deactivating');
      }

      if ("activate-plugin" === pluginAction) {
        $el.html('<i class="fas la-circle-notch la-spin"></i>Activating');
      }

      $(this).attr("disabled", true);

      $.ajax({
        type: "POST",
        url: ajax_url,
        data: ajaxData,
        timeout: 20000,
      }).done((response) => {
        if (response.success) {
          if ("deactivate-plugin" === pluginAction) {
            $pluginRow
              .removeClass("togo-plugin--activated")
              .addClass("togo-plugin--deactivated");
            $el
              .text("Activate")
              .attr("data-plugin-action", "activate-plugin")
              .attr("data-nonce", response.data)
              .removeClass("plugin-deactivate")
              .addClass("plugin-activate");
            $icon.addClass("fa-times").removeClass("fa-check");
          }

          if ("activate-plugin" === pluginAction) {
            $pluginRow
              .removeClass("togo-plugin--deactivated")
              .addClass("togo-plugin--activated");
            $el
              .text("Deactivate")
              .attr("data-plugin-action", "deactivate-plugin")
              .attr("data-nonce", response.data)
              .removeClass("plugin-activate")
              .addClass("plugin-deactivate");
            $icon.addClass("fa-check").removeClass("fa-times");
          }

          var requiredPluginCount = $pluginsTable.find(
              ".togo-plugin--required.togo-plugin--deactivated"
            ).length,
            $pluginCount = $(".togo-box--plugins .togo-box__footer span");

          if (requiredPluginCount) {
            $pluginCount
              .css("color", "#dc433f")
              .text(
                "Please install and activate all required plugins (" +
                  requiredPluginCount +
                  ")"
              );
          } else {
            $pluginCount
              .css("color", "#6fbcae")
              .text(
                "All required plugins are activated. Now you can import the demo data."
              );
          }
        } else {
          $el.text("Error");
        }
      });
    });
  }

  $(document).ready(function () {
    purchase_form();

    plugin_action();
  });
})(jQuery);
