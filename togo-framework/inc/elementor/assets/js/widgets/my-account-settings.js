(function ($) {
  "use strict";

  var MySettingsHandler = function ($scope, $) {
    var $form = $scope.find(".togo-my-settings-form");
    $form.find("input").focus(function () {
      $(this).closest(".form-field").addClass("focus");
    });
    $form.find("input").blur(function () {
      if ($(this).val() === "") {
        $(this).closest(".form-field").removeClass("focus");
      }
    });
    $form.find(".form-field").each(function () {
      if ($(this).find("input").val() !== "") {
        $(this).addClass("focus");
      }
    });
    const today = new Date().toISOString().split("T")[0];
    $("#birthday").attr("max", today);

    // Show preview of the uploaded image
    $("#avatar").on("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          $(".field-avatar__image-avatar img").attr("src", event.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-my-settings.default",
      MySettingsHandler
    );
  });
})(jQuery);
