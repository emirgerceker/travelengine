(function ($) {
  "use strict";

  var loadYouTubeAPI = function () {
    if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
      var tag = document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }
  };

  var players = {};

  window.onYouTubeIframeAPIReady = function () {
  };

  var VideoHandler = function ($scope, $) {
    loadYouTubeAPI();

    $scope.find('.togo-play-button').each(function () {
      $(this).on('click', function (e) {
        e.preventDefault();

        const widget = $(this).closest('.togo-video-widget');
        widget.find('.togo-video-overlay').hide();
        widget.find('.togo-video-player').show();

        widget.css('background-image', 'none');

        const shouldAutoplay = widget.data('autoplay') === 'yes';
        const shouldMute = widget.data('mute') === 'yes';

        const video = widget.find('video').get(0);
        if (video) {
          video.muted = shouldMute;
          if (shouldAutoplay) {
            video.play().catch(() => {});
          }
        }

        const iframe = widget.find('iframe').get(0);
        if (iframe) {
          const iframeId = iframe.id || 'ytplayer-' + $scope.data('id');
          iframe.id = iframeId;

          if (typeof YT !== 'undefined' && YT.Player) {
            if (players[iframeId]) {
              players[iframeId].playVideo();
              if (shouldMute) {
                players[iframeId].mute();
              } else {
                players[iframeId].unMute();
              }
            } else {
              players[iframeId] = new YT.Player(iframeId, {
                events: {
                  onReady: function (event) {
                    event.target.playVideo();
                    if (shouldMute) {
                      event.target.mute();
                    } else {
                      event.target.unMute();
                    }
                  },
                  onError: function () {
                  }
                }
              });
            }
          } else {
            setTimeout(() => VideoHandler($scope, $), 1000);
          }
        } else {
        }
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-video.default",
      VideoHandler
    );
  });
})(jQuery);
