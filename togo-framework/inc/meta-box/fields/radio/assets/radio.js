/**
 * radio field script
 *
 */

var Uxper_RadioClass = function ($container) {
  this.$container = $container;
};

(function ($) {
  "use strict";

  /**
   * Define class field prototype
   */
  Uxper_RadioClass.prototype = {
    init: function () {
      this.$container.find("input.uxper-radio").on("change", function () {
        var $field = $(this).closest(".uxper-field"),
          value = UxperFieldsConfig.fields.getValue($field);
        UxperFieldsConfig.required.checkRequired($field, value);
      });
    },
  };

  /**
   * Define object field
   */
  var Uxper_RadioObject = {
    init: function () {
      /**
       * Init Fields after make clone template
       */
      var $configWrapper = $(".uxper-meta-config-wrapper");
      $configWrapper = $configWrapper.length ? $configWrapper : $("body");

      $configWrapper.on("uxper_make_template_done", function () {
        $(".uxper-field-radio-inner").each(function () {
          var field = new Uxper_RadioClass($(this));
          field.init();
        });
      });

      /**
       * Init Clone Field after field cloned
       */
      $(".uxper-field.uxper-field-radio").on(
        "uxper_add_clone_field",
        function (event) {
          var $items = $(event.target).find(".uxper-field-radio-inner");
          if ($items.length) {
            var field = new Uxper_RadioClass($items);
            field.init();
          }
        }
      );

      $("body").on("keyup", ".togo-icon-search", function (e) {
        var value = $(this).val();
        $(".uxper-field-radio-inner label").each(function () {
          var label = $(this).attr("data-name");
          if (label.includes(value)) {
            $(this).fadeIn();
          } else {
            $(this).fadeOut();
          }
        });
      });

      $(
        ".modal .uxper-field-radio-inner label input[type='radio']:checked"
      ).each(function () {
        var modal = $(this).closest(".modal");
        var html = $(this).parent().find("span.togo-svg-icon").html();
        modal.prev(".uxper-field-select-icon").find("svg").remove();
        modal.prev(".uxper-field-select-icon").prepend(html);
      });

      $("body").on(
        "change",
        ".modal .uxper-field-radio-inner label input[type='radio']",
        function () {
          $(this).closest(".modal").removeClass("is-active");
          var html = $(this).parent().find("span.togo-svg-icon").html();
          $(this)
            .closest(".modal")
            .prev(".uxper-field-select-icon")
            .find("svg")
            .remove();
          $(this)
            .closest(".modal")
            .prev(".uxper-field-select-icon")
            .prepend(html);
        }
      );

      $("body").on("click", ".uxper-field-select-icon", function (e) {
        e.preventDefault();
        $(this).next(".modal").addClass("is-active");
      });
    },
  };

  /**
   * Init Field when document ready
   */
  $(document).ready(function () {
    Uxper_RadioObject.init();
    UxperFieldsConfig.fieldInstance.push(Uxper_RadioObject);
  });
})(jQuery);
