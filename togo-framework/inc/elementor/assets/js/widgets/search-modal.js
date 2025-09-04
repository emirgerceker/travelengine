(function ($) {
  "use strict";

  var SearchModalHandler = function ($scope, $) {
    var search_icon = $scope.find(".search-icon");
    var modal_overlay = $scope.find(".search-modal-overlay");
    var modal_close = $scope.find(".search-modal-close");
    var search_form = $scope.find(".togo-search-form");

    search_icon.on("click", function (e) {
      e.preventDefault();
      $(this).closest(".togo-mini-cart").addClass("is-active");
      $("body").addClass("opened-search");
    });

    modal_close.on("click", function (e) {
      e.preventDefault();
      $(this).closest(".togo-mini-cart").removeClass("is-active");
      $("body").removeClass("opened-search");
    });

    modal_overlay.on("click", function (e) {
      e.preventDefault();
      $(this).closest(".togo-mini-cart").removeClass("is-active");
      $("body").removeClass("opened-search");
    });

    search_form.on("keyup", function (e) {
      e.preventDefault();
      var key = $(this).find("input[name='s']").val();
      var post_type = $(this).find("input[name='post_type']").val();
      var minimum_characters = $(this)
        .find("input[name='minimum_characters']")
        .val();
      var number_of_items = $(this).find("input[name='number_of_items']").val();
      if ($(this).find('input[name="view_all_text"]').length > 0) {
        var view_all_text = $(this).find('input[name="view_all_text"]').val();
      } else {
        var view_all_text = "";
      }
      var charCount = key.length;
      if (charCount >= minimum_characters) {
        $.ajax({
          type: "POST",
          url: woocommerce_params.ajax_url,
          data: {
            action: "togo_search",
            key: key,
            post_type: post_type,
            number_of_items: number_of_items,
            view_all_text: view_all_text,
          },
          beforeSend: function () {
            $(".search-modal-loading").addClass("is-active");
            $(".search-modal-results").html("");
          },
          success: function (response) {
            if (response) {
              $(".search-modal-loading").removeClass("is-active");
              $(".search-modal-results").html(response);
            }
          },
        });
      } else {
        $(".search-modal-results").html("");
      }
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-search-modal.default",
      SearchModalHandler
    );
  });
})(jQuery);
