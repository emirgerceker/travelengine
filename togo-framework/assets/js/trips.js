jQuery(document).ready(function ($) {
  // Open the media uploader
  $(".upload_image_button").click(function (e) {
    e.preventDefault();

    var button = $(this);
    var customUploader = wp
      .media({
        title: "Select Image",
        button: {
          text: "Use this image",
        },
        multiple: false,
      })
      .on("select", function () {
        var attachment = customUploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        // Update the input field and preview image
        button.prev().val(attachment.url);
        $(".trip_location_image_preview").html(
          '<img src="' +
            attachment.url +
            '" style="max-width: 100px; height: auto;" />'
        );
      })
      .open();
  });

  // Display preview image when the page loads (for existing terms)
  if ($(".trip_location_image").val()) {
    $(".trip_location_image_preview").html(
      '<img src="' +
        $(".trip_location_image").val() +
        '" style="max-width: 100px; height: auto;" />'
    );
  }

  function toggle() {
    const CURRENT_SECTION = "glf_theme_options_current_section";
    var _current_page = $("#_current_page").val(),
      currentSection = localStorage.getItem(
        CURRENT_SECTION + "_" + _current_page
      );
    if (currentSection === null) {
      var sectionActive = $(".uxper-tab li:first").data("id");
      currentSection = sectionActive;
      if (typeof sectionActive != "undefined") {
        localStorage.setItem(
          CURRENT_SECTION + "_" + _current_page,
          sectionActive
        );
      } else {
        /**
         * Off reset section if not exist section
         */
        $(".glf-theme-options-reset-section").remove();
      }
    }
    $(".uxper-tab li").removeClass("active");
    $(".uxper-fields-wrapper > .uxper-section-container").hide();
    $('.uxper-tab li[data-id="' + currentSection + '"]').addClass("active");
    $(
      '.uxper-fields-wrapper > .uxper-section-container[id="' +
        currentSection +
        '"]'
    ).show();

    /**
     * Store currentSection when section clicked
     */
    $(".uxper-tab li").on("click", function () {
      localStorage.setItem(
        CURRENT_SECTION + "_" + _current_page,
        $(this).data("id")
      );
    });
  }

  toggle();
});
