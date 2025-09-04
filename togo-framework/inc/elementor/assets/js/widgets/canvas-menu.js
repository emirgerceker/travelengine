(function ($) {
  "use strict";

  var CanvasMenuHandler = function ($scope, $) {
    var mb_menu = $scope.find(".mb-menu");
    var mb_menu_back = $scope.find(".mobie-menu-back");
    var mb_menu_user = $scope.find(".mobile-menu-user");
    var next = $scope.find(".mb-menu .togo-svg-icon");
    var sub_menu = $scope.find(".mb-menu .sub-menu");
    var nav_item = $scope.find(".mb-menu .nav-item");
    var destinations_box = $scope.find(".mb-menu .destinations-box");
    var lc_button = $scope.find(".lc-button");
    var user_icon = $scope.find(".togo-user-icon");
    var canvas_menu_icon = $scope.find(".canvas-menu-icon");
    var id = 0;

    next.on("click", function (e) {
      e.preventDefault();
      id++;
      var width = id * 100;
      sub_menu.removeClass("is-active newest");
      mb_menu.css("transform", "translateX(-" + width + "%)");
      $(this).closest(".sub-menu").addClass("is-active");
      $(this).closest("li").find("> .sub-menu").addClass("is-active newest");
      mb_menu_back.addClass("is-active");
      mb_menu_user.addClass("is-none");
      if ($(".sub-menu.is-active.newest").length > 0) {
        mb_menu.height($(".sub-menu.is-active.newest").height());
      }
    });

    mb_menu_back.on("click", function () {
      id--;
      var width = 0 - id * 100;
      mb_menu.css("transform", "translateX(" + width + "%)");
      var sub_menu = $(this)
        .closest(".mobile-menu-wrapper")
        .find(".sub-menu.newest");
      sub_menu.closest(".sub-menu").addClass("newest");
      sub_menu.removeClass("is-active newest");
      if (id == 0) {
        mb_menu_back.removeClass("is-active");
        mb_menu_user.removeClass("is-none");
      }
      if ($(".sub-menu.is-active").length > 0) {
        mb_menu.height($(".sub-menu.is-active").height());
      } else {
        mb_menu.height("auto");
      }
      if (width == 0) {
        mb_menu.height("auto");
      }
    });

    nav_item.on("click", function () {
      var index = $(this).index();
      destinations_box.removeClass("is-active");
      $(this)
        .closest(".togo-mega-destinations")
        .find(".destinations-box")
        .eq(index)
        .addClass("is-active");
    });

    lc_button.on("click", function () {
      $(this).next(".lc-content").toggleClass("is-active");
    });

    user_icon.on("click", function () {
      $(this).next(".user-submenu").toggleClass("is-active");
    });

    $(document).click(function (event) {
      // Check if the clicked target is outside the div with id 'myDiv'
      if (!$(event.target).closest(".mobile-menu-user").length) {
        $(".mobile-menu-user .user-submenu").removeClass("is-active");
      }
      if (!$(event.target).closest(".mobile-menu-bottom .lc-wapper").length) {
        $(".mobile-menu-bottom .lc-wapper .lc-content").removeClass(
          "is-active"
        );
      }
    });

    canvas_menu_icon.on("click", function () {
      $(this)
        .closest(".canvas-menu-wrapper")
        .find(".mobile-menu-wrapper")
        .toggleClass("is-active");
    });

    $(document).on(
      "click",
      ".mobile-menu-close, .mobile-menu-overlay",
      function () {
        $(this)
          .closest(".canvas-menu-wrapper")
          .find(".mobile-menu-wrapper")
          .removeClass("is-active");
      }
    );
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-canvas-menu.default",
      CanvasMenuHandler
    );
  });
})(jQuery);
