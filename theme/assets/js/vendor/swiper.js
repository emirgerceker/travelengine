(function ($) {
  "use strict";

  $.fn.TogoSwiper = function (options) {
    var defaults = {};
    var settings = $.extend({}, defaults, options);

    this.each(function () {
      var slider = $(this);
      var sliderSettings = slider.data();
      var lgItems = sliderSettings.lgItems || 1,
        mdItems = sliderSettings.mdItems || lgItems,
        smItems = sliderSettings.smItems || mdItems,
        lgGutter = sliderSettings.lgGutter || 0,
        mdGutter = sliderSettings.mdGutter || lgGutter,
        smGutter = sliderSettings.smGutter || mdGutter,
        speed = sliderSettings.speed || 1000,
        paginationType = sliderSettings.paginationType || "bullets",
        customNav = sliderSettings.customNav || 0,
        customNavNext = sliderSettings.nextButtonClass || "",
        customNavPrev = sliderSettings.prevButtonClass || "";

      var swiperOptions = $.extend(
        {},
        {
          init: false,
          watchSlidesVisibility: true,
          slidesPerView: smItems,
          spaceBetween: smGutter,
          breakpoints: {
            720: {
              slidesPerView: mdItems,
              spaceBetween: mdGutter,
            },
            1200: {
              slidesPerView: lgItems,
              spaceBetween: lgGutter,
            },
          },
          watchOverflow: true,
        },
        settings
      );

      if (sliderSettings.slidesPerGroup === "inherit") {
        swiperOptions.slidesPerGroup = smItems;
        swiperOptions.breakpoints[720].slidesPerGroup = mdItems;
        swiperOptions.breakpoints[1200].slidesPerGroup = lgItems;
      }

      if (sliderSettings.autoHeight) swiperOptions.autoHeight = true;
      if (sliderSettings.simulateTouch === false)
        swiperOptions.simulateTouch = false;
      if (sliderSettings.hashNavigation) swiperOptions.hashNavigation = true;
      if (speed) swiperOptions.speed = speed;

      if (sliderSettings.effect) {
        swiperOptions.effect = sliderSettings.effect;
        swiperOptions.fadeEffect = {
          crossFade: sliderSettings.fadeEffect !== "custom",
        };
      }

      if (sliderSettings.loop) {
        swiperOptions.loop = true;
        if (sliderSettings.loopedSlides)
          swiperOptions.loopedSlides = sliderSettings.loopedSlides;
      }

      if (sliderSettings.centered) swiperOptions.centeredSlides = true;
      if (sliderSettings.autoplay) {
        swiperOptions.autoplay = {
          delay: sliderSettings.autoplay,
          disableOnInteraction: false,
        };
      }

      var uniqueId = Math.random().toString(36).substring(2, 15);

      if (sliderSettings.nav) {
        var nextClass = `swiper-button-next-${uniqueId}`;
        var prevClass = `swiper-button-prev-${uniqueId}`;
        slider.append(
          `<div class="swiper-button-next swiper-nav-button ${nextClass}"></div>`
        );
        slider.append(
          `<div class="swiper-button-prev swiper-nav-button ${prevClass}"></div>`
        );
        swiperOptions.navigation = {
          nextEl: `.${nextClass}`,
          prevEl: `.${prevClass}`,
        };
      }

      if (customNav) {
        swiperOptions.navigation = {
          nextEl: `.${customNavNext}`,
          prevEl: `.${customNavPrev}`,
        };
      }

      if (sliderSettings.pagination) {
        var paginationClass = `swiper-pagination-${uniqueId}`;
        slider.append(
          `<div class="swiper-pagination ${paginationClass}"></div>`
        );
        swiperOptions.pagination = {
          el: `.${paginationClass}`,
          type: paginationType,
          clickable: true,
        };
      }

      if (sliderSettings.scrollbar) {
        var scrollbarClass = `swiper-scrollbar-${uniqueId}`;
        slider.prepend(
          `<div class="swiper-scrollbar ${scrollbarClass}"></div>`
        );
        swiperOptions.scrollbar = {
          el: `.${scrollbarClass}`,
          draggable: true,
        };
        swiperOptions.loop = false;
      }

      if (sliderSettings.mousewheel)
        swiperOptions.mousewheel = { enabled: true };
      if (sliderSettings.vertical) swiperOptions.direction = "vertical";
      if (sliderSettings.slideToClickedSlide) {
        swiperOptions.slideToClickedSlide = true;
        swiperOptions.touchRatio = 0.2;
      }

      var swiper = new Swiper(slider[0], swiperOptions);

      swiper.on("init", function () {
        var index = swiper.activeIndex;
        var slides = swiper.$wrapperEl.find(".swiper-slide");
        var currentSlide = slides.eq(index);
        currentSlide.addClass("animated");
      });

      swiper.on("slideChangeTransitionEnd", function () {
        var index = swiper.activeIndex;
        var slides = swiper.$wrapperEl.find(".swiper-slide");
        var currentSlide = slides.eq(index);
        currentSlide.addClass("animated");
      });

      swiper.on("slideChangeTransitionStart", function () {
        var slides = swiper.$wrapperEl.find(".swiper-slide");
        slides.removeClass("animated");
      });
      swiper.init();

      $(document).trigger("TogoSwiperInit", [swiper, slider, swiperOptions]);
    });

    return this;
  };
})(jQuery);
