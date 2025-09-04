(function ($) {
  "use strict";

  var STGalleryHandler = function ($scope, $) {
    let currentIndex = 0;
    const images = $scope.find(".lightbox-trigger");
    const show_all = $scope.find(".togo-st-gallery-show-all");
    const lightbox_content = $scope.find(".lightbox-img-wrapper");
    const lightbox_caption = $scope.find(".lightbox-caption");

    // Open Lightbox
    $(".lightbox-trigger").on("click", function () {
      currentIndex = $(this).data("index");
      showLightbox(currentIndex);
    });

    // Show Lightbox
    function showLightbox(index) {
      const imgSrc = $(images[index]).attr("src");
      const captionText = $(images[index]).attr("alt");
      lightbox_content
        .empty()
        .append(
          `<img src="${imgSrc}" class="class="lightbox-img" alt="${captionText}">`
        );
      lightbox_caption.text(captionText);
      $(".togo-lightbox").fadeIn();
      $(".lightbox-progress").text(index + 1 + "/" + images.length);
      if (index == 0 && $(".togo-lightbox").hasClass("has-video")) {
        $(".lightbox-content").addClass("with-video");
      } else {
        $(".lightbox-content").removeClass("with-video");
      }
    }

    // Close Lightbox
    $(".lightbox-close").on("click", function (e) {
      e.preventDefault();
      $(".togo-lightbox").fadeOut();
    });

    // Navigate through images
    $(".lightbox-next").on("click", function (e) {
      e.preventDefault();
      currentIndex = (currentIndex + 1) % images.length;
      showLightbox(currentIndex);
      $(".lightbox-progress").text(currentIndex + 1 + "/" + images.length);
      if (currentIndex == 0 && $(".togo-lightbox").hasClass("has-video")) {
        $(".lightbox-content").addClass("with-video");
      } else {
        $(".lightbox-content").removeClass("with-video");
      }
    });

    $(".lightbox-prev").on("click", function (e) {
      e.preventDefault();
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      showLightbox(currentIndex);
      $(".lightbox-progress").text(currentIndex + 1 + "/" + images.length);
      if (currentIndex == 0 && $(".togo-lightbox").hasClass("has-video")) {
        $(".lightbox-content").addClass("with-video");
      } else {
        $(".lightbox-content").removeClass("with-video");
      }
    });

    show_all.on("click", function (e) {
      e.preventDefault();
      $(".lightbox-trigger:first").trigger("click");
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-st-gallery.default",
      STGalleryHandler
    );
  });
})(jQuery);
