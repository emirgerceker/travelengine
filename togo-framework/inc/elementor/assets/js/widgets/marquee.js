(function ($) {
  "use strict";

  var MarqueeHandler = function ($scope, $) {
    $scope.find(".togo-marquee").each(function () {
      const $container = $(this);
      const $track = $container.find(".togo-marquee-inner");
      const speed = Number($container.data("speed")) || 1; // tốc độ
      const type = $container.data("type") || "left-to-right";

      let animationId = null;
      let isPaused = false;

      if (!$track.children().length) {
        console.warn(
          "MarqueeHandler: No content in .togo-marquee-inner",
          $container
        );
        return;
      }

      if (!$track.data("cloned")) {
        const content = $track.html();
        $track.append(content); // clone 1 lần (tổng 2 lần)
        $track.data("cloned", true);
      }

      let singleWidth = $track[0].scrollWidth / 2;

      // Hướng dịch chuyển
      const direction = type === "right-to-left" ? -1 : 1;

      // Khởi tạo posX
      let posX = type === "left-to-right" ? -singleWidth : 0;

      function animate() {
        if (isPaused) return;

        posX += speed * direction;

        // Reset posX khi chạy hết 1 phần nội dung gốc
        if (type === "left-to-right" && posX >= 0) {
          posX = -singleWidth;
        } else if (type === "right-to-left" && posX <= -singleWidth) {
          posX = 0;
        }

        $track.css("transform", `translateX(${posX}px)`);

        animationId = requestAnimationFrame(animate);
      }

      function start() {
        if (!animationId && !isPaused) {
          animate();
        }
      }

      function stop() {
        if (animationId) {
          cancelAnimationFrame(animationId);
          animationId = null;
        }
      }

      $container
        .on("mouseenter", () => {
          isPaused = true;
          stop();
        })
        .on("mouseleave", () => {
          isPaused = false;
          start();
        });

      // Cập nhật lại width khi resize cửa sổ
      $(window).on("resize.marquee", () => {
        singleWidth = $track[0].scrollWidth / 2;
        if (type === "left-to-right") {
          posX = Math.max(posX, -singleWidth);
        }
      });

      start();

      $scope.on("destroy", () => {
        stop();
        $container.off("mouseenter mouseleave");
        $(window).off("resize.marquee");
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-marquee.default",
      MarqueeHandler
    );
  });
})(jQuery);
