var TOGO = TOGO || {};

(function ($) {
  "use strict";

  var $body = $("body"),
    $window = $(window),
    ajax_url = theme_vars.ajax_url,
    header_sticky = theme_vars.header_sticky,
    content_protected_enable = theme_vars.content_protected_enable,
    scroll_top_enable = theme_vars.scroll_top_enable;

  function isInViewport(node) {
    var rect = node.getBoundingClientRect();
    return (
      (rect.height > 0 || rect.width > 0) &&
      rect.bottom >= 0 &&
      rect.right >= 0 &&
      rect.top <=
        (window.innerHeight || document.documentElement.clientHeight) &&
      rect.left <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  TOGO.element = {
    init: function () {
      TOGO.element.rtl();
      TOGO.element.cookie_notices();
      TOGO.element.general();
      TOGO.element.retina_logo();
      TOGO.element.scroll_to_top();
      TOGO.element.main_menu();
      TOGO.element.header_sticky();
      TOGO.element.toggle_popup();
      TOGO.element.widget_toggle();
      TOGO.element.scroll_bar();
      TOGO.element.modal();
      TOGO.element.contact_form_7();

      if (content_protected_enable == 1) {
        TOGO.element.content_protected();
      }
    },

    windowLoad: function () {
      TOGO.element.page_loading_effect();
    },

    rtl: function () {
      $(window).load(function () {
        if ($("body").attr("dir") == "rtl") {
          $(".elementor-section-stretched").each(function () {
            var val = $(this).css("left");
            $(this).css("left", "auto");
            $(this).css("right", val);
          });
        }
      });
    },

    general: function () {
      $(".block-search.search-icon").on("click", function (e) {
        e.preventDefault();
        $(".search-form-wrapper.canvas-search").addClass("on");
      });

      $(".canvas-search").on("click", ".btn-close,.bg-overlay", function (e) {
        e.preventDefault();
        $(this).parents(".canvas-search").removeClass("on");
      });

      $(".togo-pricing-plan-main .togo-list > .item").each(function (
        index,
        el
      ) {
        var list_height = $(el).outerHeight();
        var idx = index + 1;
        $(el)
          .parents(".togo-pricing-plan-main")
          .find(".togo-pricing-features .item:nth-child(" + idx + ")")
          .css("height", list_height);
        $(el)
          .parents(".togo-pricing-plan-main")
          .find(".togo-pricing-features .item:nth-child(" + idx + ")")
          .css("line-height", list_height + "px");
      });

      $("i.togo-svg").each(function () {
        var color = $(this).css("color"); // Get the color of the element
        var style = $(
          "<style>::before { background-color: " + color + "; }</style>"
        );

        // Create a unique class to apply
        var uniqueClass = "before-bg-" + Math.floor(Math.random() * 10000);

        // Append the generated style to the head
        style.text(
          "." + uniqueClass + "::before { background-color: " + color + "; }"
        );
        $("head").append(style);

        // Add the class to the element to apply the style
        $(this).addClass(uniqueClass);
      });
    },

    retina_logo: function () {
      if (
        window.matchMedia("only screen and (min--moz-device-pixel-ratio: 1.5)")
          .matches ||
        window.matchMedia("only screen and (-o-min-device-pixel-ratio: 3/2)")
          .matches ||
        window.matchMedia(
          "only screen and (-webkit-min-device-pixel-ratio: 1.5)"
        ).matches ||
        window.matchMedia("only screen and (min-device-pixel-ratio: 1.5)")
          .matches
      ) {
        $(".site-logo img").each(function () {
          $(this).addClass("logo-retina");
          $(this).attr("src", $(this).data("retina"));
        });
      }
    },

    cookie_notices: function () {
      if (
        theme_vars.notice_cookie_enable == 1 &&
        theme_vars.notice_cookie_confirm != "yes" &&
        theme_vars.notice_cookie_messages != ""
      ) {
        $.growl({
          location: "br",
          fixed: true,
          duration: 3600000,
          size: "large",
          title: "",
          message: theme_vars.notice_cookie_messages,
        });

        $("#togo-button-cookie-notice-ok").on("click", function () {
          $(this)
            .parents(".growl-message")
            .first()
            .siblings(".growl-close")
            .trigger("click");

          var _data = {
            action: "notice_cookie_confirm",
          };

          _data = $.param(_data);

          $.ajax({
            url: theme_vars.ajax_url,
            type: "POST",
            data: _data,
            dataType: "json",
            success: function (results) {},
            error: function (errorThrown) {
              console.log(errorThrown);
            },
          });
        });
      }
    },

    page_loading_effect: function () {
      setTimeout(function () {
        $body.addClass("loaded");
      }, 200);

      var $loader = $("#page-preloader");

      setTimeout(function () {
        $loader.remove();
      }, 2000);
    },

    process_item_queue: function (
      itemQueue,
      queueDelay,
      queueTimer,
      queueResetDelay
    ) {
      clearTimeout(queueResetDelay);
      queueTimer = window.setInterval(function () {
        if (itemQueue !== undefined && itemQueue.length) {
          $(itemQueue.shift()).addClass("animate");
          TOGO.element.process_item_queue();
        } else {
          window.clearInterval(queueTimer);
        }
      }, queueDelay);
    },

    scroll_to_top: function () {
      if (scroll_top_enable != 1) {
        return;
      }
      var $scrollUp = $("#page-scroll-up");
      var lastScrollTop = 0;
      $window.on("scroll", function () {
        var st = $(this).scrollTop();
        if (st > lastScrollTop) {
          $scrollUp.removeClass("show");
        } else {
          if ($window.scrollTop() > 200) {
            $scrollUp.addClass("show");
          } else {
            $scrollUp.removeClass("show");
          }
        }
        lastScrollTop = st;
      });

      $scrollUp.on("click", function (evt) {
        $("html, body").animate({ scrollTop: 0 }, 600);
        evt.preventDefault();
      });
    },

    main_menu: function () {
      $(".site-menu .sub-menu:not(.mega-menu)").each(function () {
        var width = $(this).outerWidth();

        if (width > 0) {
          var offset = $(this).offset();
          var w_body = $("body").outerWidth();
          var left = offset.left;
          if (w_body < left + width) {
            $(this).css("left", "-100%");
          }
        }
      });
    },

    header_sticky: function () {
      if (header_sticky == "yes") {
        return;
      }

      var offset = 0,
        height = 0;

      if ($("header.site-header").length > 0) {
        offset = $("header.site-header").offset().top;
        height = $("header.site-header").outerHeight();
      }
      var has_wpadminbar = $("#wpadminbar").length;
      var wpadminbar = 0;

      var lastScroll = 0;
      if (has_wpadminbar > 0) {
        wpadminbar = $("#wpadminbar").height();
        $(".header-sticky").addClass("has-wpadminbar");
      }

      $(window).on("scroll", function () {
        var currentScroll = $(window).scrollTop();
        if (currentScroll > offset + wpadminbar + height + 100) {
          $(".header-sticky").addClass("scroll");
        } else {
          $(".header-sticky").removeClass("scroll");
        }
        if (currentScroll > lastScroll) {
          $(".header-sticky").removeClass("on");
        } else {
          if (currentScroll < offset + wpadminbar + height + 100) {
            $(".header-sticky").removeClass("on");
          } else {
            $(".header-sticky").addClass("on");
          }
        }
        lastScroll = currentScroll;
      });
    },

    toggle_popup: function () {
      $(".togo-popup").on("click", ".bg-overlay, .btn-close", function (e) {
        e.preventDefault();
        $("html").css("overflow", "auto");
        $("html").css("margin-right", "0");
        $(this).closest(".togo-popup").removeClass("open");
        $("iframe").each(function (index) {
          $(this).attr("src", $(this).attr("src"));
          return false;
        });
      });

      $(".btn-togo-popup").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr("href");
        $("html").css("overflow", "hidden");
        $("html").css("margin-right", "15px");
        $(".togo-popup").removeClass("open");
        $(id).addClass("open");
      });
    },

    content_protected: function () {
      var $contentProtectedAlert = $("#togo-content-protected-box");
      var delayTime = 3000;

      /**
       * Prevent right click.
       */
      $(document).on("contextmenu", function () {
        $contentProtectedAlert.show().delay(delayTime).fadeOut();
        return false;
      });

      $(window).on("keydown", function (event) {
        /**
         * Prevent open chrome dev tools on Win OS.
         */
        // Prevent F12.
        if (event.keyCode == 123) {
          $contentProtectedAlert.show().delay(delayTime).fadeOut();
          return false;
        }

        /**
         * CTRL + SHIFT + I
         * CTRL + SHIFT + J
         * CTRL + SHIFT + C
         */
        if (
          event.ctrlKey &&
          event.shiftKey &&
          (event.keyCode == 67 || event.keyCode == 73 || event.keyCode == 74)
        ) {
          $contentProtectedAlert.show().delay(delayTime).fadeOut();
          return false;
        }

        /**
         * Prevent open chrome dev tools on Mac OS.
         */

        /**
         * COMMAND + OPTION + I
         * COMMAND + OPTION + J
         * COMMAND + OPTION + C
         */
        if (
          event.metaKey &&
          event.altKey &&
          (event.keyCode == 67 || event.keyCode == 73 || event.keyCode == 74)
        ) {
          $contentProtectedAlert.show().delay(delayTime).fadeOut();
          return false;
        }

        // COMMAND + SHIFT + C
        if (event.metaKey && event.shiftKey && event.keyCode == 67) {
          $contentProtectedAlert.show().delay(delayTime).fadeOut();
          return false;
        }
      });

      $("html").bind("cut copy paste", function (e) {
        e.preventDefault();
      });
    },

    widget_toggle: function ($this) {
      $(".togo-pricing-plan .switch").on("click", function (e) {
        e.preventDefault();

        var _this = $(this),
          item = $(this)
            .parents(".togo-pricing-plan")
            .find(".pricing-plan-item");

        _this.toggleClass("active");

        item.each(function () {
          if ($(this).hasClass("active")) {
            $(this).removeClass("active");
          } else {
            $(this).addClass("active");
          }
        });
      });
    },

    scroll_bar: function ($this) {
      $(".scroll-bar-wrap").each(function () {
        var height = $("body").outerHeight();
        var current = $(window).scrollTop();
        var top = (current / height) * 100;
        $(this)
          .find(".scroll-bar-current")
          .css("top", top + "%");
      });
    },

    modal: function ($this) {
      $(".togo-open-modal").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr("href");
        $(id).addClass("is-active");
        $("html").css("overflow", "hidden");
      });

      $("body").on(
        "click",
        ".togo-modal-close, .togo-modal-overlay",
        function (e) {
          e.preventDefault();
          $(this).closest(".togo-modal").removeClass("is-active");
          $("html").css("overflow", "auto");
        }
      );
    },

    contact_form_7: function () {
      $(".wpcf7-form").each(function () {
        var $this = $(this);
        if ($this.find("input").val() !== "") {
          $this.addClass("focus");
        }
        $this.find("input").focus(function () {
          $(this).closest(".form-field").addClass("focus");
        });
        $this.find("input").blur(function () {
          if ($(this).val() === "") {
            $(this).closest(".form-field").removeClass("focus");
          }
        });
      });
    },
  };

  TOGO.woocommerce = {
    init: function () {
      TOGO.woocommerce.quantity();
    },

    quantity: function () {
      $("body").on("click", ".entry-quantity .plus", function (e) {
        var input = $(this).parents(".entry-quantity").find(".input-text.qty");
        // eslint-disable-next-line radix
        var val = parseInt(input.val()) + 1;
        input.attr("value", val);
        $('.button[name="update_cart"]').prop("disabled", false);
      });
      $("body").on("click", ".entry-quantity .minus", function (e) {
        var input = $(this).parents(".entry-quantity").find(".input-text.qty");
        // eslint-disable-next-line radix
        var val = parseInt(input.val()) - 1;
        if (input.val() > 0) {
          input.attr("value", val);
        }
        $('.button[name="update_cart"]').prop("disabled", false);
      });
    },
  };

  TOGO.onReady = {
    init: function () {
      TOGO.element.init();
      TOGO.woocommerce.init();
    },
  };

  TOGO.onLoad = {
    init: function () {
      TOGO.element.windowLoad();
    },
  };

  TOGO.onScroll = {
    init: function () {
      var scrolled = $(window).scrollTop();
      $(".togo-parallax").each(function (index, element) {
        var initY = $(this).offset().top;
        var height = $(this).height();
        var endY = initY + $(this).height();
        var direction = $(this).data("direction");
        var size = $(this).data("size");
        var targetHeight = $(this).data("height");
        var targetHeightTop = $(this).offset().top;

        // Check if the element is in the viewport.
        var visible = isInViewport(this);
        if (visible && $(window).width() > 767) {
          var diff = scrolled - initY;
          var ratio = Math.round((diff / height) * 100);
          if (direction == "up") {
            $(this)
              .find("> .elementor-widget-container")
              .css(
                "transform",
                "translateY(" + parseInt(-(ratio * size)) + "px)"
              );
          } else if (direction == "down") {
            $(this)
              .find("> .elementor-widget-container")
              .css("transform", "translateY(" + parseInt(ratio * size) + "px)");
          } else if (direction == "left") {
            $(this)
              .find("> .elementor-widget-container")
              .css(
                "transform",
                "translateX(" + parseInt(-(ratio * size)) + "px)"
              );
          } else if (direction == "right") {
            $(this)
              .find("> .elementor-widget-container")
              .css("transform", "translateX(" + parseInt(ratio * size) + "px)");
          } else if (direction == "out-in") {
            if (window.scrollY > targetHeightTop) {
              var scrollPercent =
                (targetHeight - (window.scrollY - targetHeightTop)) /
                targetHeight;
              if (scrollPercent >= 0) {
                $(this).css("opacity", scrollPercent);
              }
            }
          }
        }
      });

      $(".elementor-element").each(function () {
        if ($(this).hasClass("elementor-invisible")) {
          var _this = $(this),
            settings = _this.data("settings"),
            animation = settings["_animation"]
              ? settings["_animation"]
              : settings["animation"],
            animationDelay = 400;

          if (settings["_animation_delay"]) {
            animationDelay = settings["_animation_delay"];
          } else if (settings["animation_delay"]) {
            animationDelay = settings["animation_delay"];
          }

          var visible = isInViewport(this);
          if (visible) {
            setTimeout(function () {
              _this
                .removeClass("elementor-invisible")
                .addClass("animated " + animation);
            }, animationDelay);
          }
        }
      });
      TOGO.element.scroll_bar();
    },
  };

  TOGO.onResize = {
    init: function () {},
  };

  TOGO.onMouseMove = {
    init: function (e) {
      var w = $(window).width();
      var h = $(window).height();

      if ($(window).width() > 767) {
        $(".togo-mousetrack").each(function (i, el) {
          var offset = parseInt($(el).data("size"));
          var direction = $(this).data("direction");
          var type = $(this).data("type");
          $(el).removeClass("togo-tilt");

          var offsetX = 0.5 - e.pageX / w;
          var offsetY = 0.5 - (e.pageY - $(window).scrollTop()) / h;

          if (type == "mousetrack") {
            if (direction == "direct") {
              var offsetX = e.pageX / w;
              var offsetY = (e.pageY - $(window).scrollTop()) / h;
            }

            var translate =
              "translate3d(" +
              Math.round(offsetX * offset) +
              "px," +
              Math.round(offsetY * offset) +
              "px, 0px)";
            $(el).css({
              "-webkit-transform": translate,
              transform: translate,
              "moz-transform": translate,
            });
          } else if (type == "tilt") {
            if (direction == "opposite") {
              var tiltX = Math.round(offsetY * offset);
              var tiltY = Math.round(offsetX * offset);
            } else if (direction == "direct") {
              var tiltX = -Math.round(offsetY * offset);
              var tiltY = -Math.round(offsetX * offset);
            }
            var translate = "rotateX(var(--rotateX))rotateY(var(--rotateY))";
            $(el).addClass("togo-tilt");
            $(el)
              .find("> .elementor-widget-container")
              .css({
                "--rotateX": tiltX + "deg",
                "--rotateY": tiltY + "deg",
                "-webkit-transform": translate,
                transform: translate,
                "moz-transform": translate,
              });
          }
        });
      }
    },
  };

  $(document).on("ready", TOGO.onReady.init);
  $(window).on("scroll", TOGO.onScroll.init);
  $(window).on("resize", TOGO.onResize.init);
  $(window).on("load", TOGO.onLoad.init);
  $(window).on("mousemove", TOGO.onMouseMove.init);
})(jQuery);
