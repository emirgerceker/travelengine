(function ($) {
  "use strict";

  var MyReviewHandler = function ($scope, $) {
    $scope.on("click", ".rating .togo-svg-icon", function (e) {
      e.preventDefault();
      var index = $(this).index() + 1;
      $(this)
        .closest(".rating")
        .find(".togo-svg-icon")
        .not(this)
        .removeClass("active");
      $(this).addClass("active");
      $(this).closest(".rating").addClass("active");
      $(this).closest(".form-group").find("input").val(index);
    });

    // let fileInput = $("#review_images");
    // let previewContainer = $("#image-preview");

    // fileInput.on("change", function (event) {
    //   previewContainer.empty();
    //   let files = event.target.files;
    //   let dt = new DataTransfer();

    //   $.each(files, function (index, file) {
    //     let reader = new FileReader();
    //     reader.onload = function (e) {
    //       let imgContainer = $("<div>").addClass("preview-item");
    //       let img = $("<img>").attr("src", e.target.result).attr("width", 100);
    //       let removeBtn = $("<button>")
    //         .html(
    //           '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.76172 7.85742L6.80934 18.3336H15.1903L16.2379 7.85742" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.375 14.2083V9.625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.625 14.2083V9.625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.19141 5.76125H8.38188M8.38188 5.76125L8.73211 4.36036C8.83412 3.95229 9.20078 3.66602 9.6214 3.66602H12.3805C12.8011 3.66602 13.1677 3.95229 13.2698 4.36036L13.62 5.76125M8.38188 5.76125H13.62M13.62 5.76125H17.8105" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    //         )
    //         .addClass("remove-image")
    //         .attr("data-index", index);

    //       imgContainer.append(img).append(removeBtn);
    //       previewContainer.append(imgContainer);
    //     };
    //     reader.readAsDataURL(file);
    //     dt.items.add(file);
    //   });

    //   fileInput[0].files = dt.files;
    // });

    // previewContainer.on("click", ".remove-image", function () {
    //   let indexToRemove = $(this).data("index");
    //   let dt = new DataTransfer();

    //   $.each(fileInput[0].files, function (index, file) {
    //     if (index !== indexToRemove) {
    //       dt.items.add(file);
    //     }
    //   });

    //   fileInput[0].files = dt.files;
    //   $(this).parent().remove();

    //   // Reset input file if no file selected
    //   if (dt.files.length === 0) {
    //     fileInput.val("");
    //   }
    // });

    $("#review_images").on("change", function () {
      var files = $("#review_images")[0].files; // Get all selected files
      var form_data = new FormData();

      // Add each file to FormData
      for (var i = 0; i < files.length; i++) {
        form_data.append("file[]", files[i]);
      }

      // Add nonce and action to form_data
      form_data.append("action", "upload_images_to_gallery");
      form_data.append("security", theme_vars.upload_images_nonce);

      var $this = $(this); // Reference to the current element
      $this.closest(".upload-button").addClass("loading");
      $this.closest("form").find("button").prop("disabled", true);
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: form_data,
        contentType: false, // Do not send the default content type
        processData: false, // Do not process data before sending
        success: function (response) {
          $this.closest(".upload-button").removeClass("loading");
          $this.closest("form").find("button").prop("disabled", false);
          if (response.success) {
            let previewContainer = $("#image-preview");
            // Add images to gallery after uploading successfully
            response.data.urls.forEach(function (url) {
              let imgContainer = $("<div>").addClass("preview-item");
              let input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "review_images[]")
                .val(url);
              let img = $("<img>").attr("src", url).attr("width", 100);
              let removeBtn = $("<button>")
                .html(
                  '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.76172 7.85742L6.80934 18.3336H15.1903L16.2379 7.85742" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.375 14.2083V9.625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.625 14.2083V9.625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.19141 5.76125H8.38188M8.38188 5.76125L8.73211 4.36036C8.83412 3.95229 9.20078 3.66602 9.6214 3.66602H12.3805C12.8011 3.66602 13.1677 3.95229 13.2698 4.36036L13.62 5.76125M8.38188 5.76125H13.62M13.62 5.76125H17.8105" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                )
                .addClass("remove-image");

              imgContainer.append(img).append(input).append(removeBtn);
              previewContainer.append(imgContainer);
            });
          } else {
            alert(theme_vars.upload_failed);
          }
        },
        error: function () {
          alert(theme_vars.error_while_upload);
        },
      });
    });

    $("#image-preview").on("click", ".remove-image", function () {
      $(this).parent().remove();
      var image_url = $(this).parent().find("input").val();

      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: {
          action: "remove_image_from_gallery",
          security: theme_vars.remove_images_nonce,
          image_url: image_url,
        },
        success: function (response) {
          if (response.success) {
            console.log("Image removed successfully.");
          } else {
            alert(theme_vars.failed_to_remove_image);
          }
        },
        error: function () {
          alert(theme_vars.error_while_remove);
        },
      });
    });

    $("body").on("submit", ".add-review-form", function (event) {
      event.preventDefault();

      var form = $(this);
      var formData = form.serialize();
      // Add nonce and action to form_data
      formData += "&action=add_review";
      formData += "&security=" + theme_vars.add_review_nonce;

      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: formData,
        beforeSend: function () {
          form.find("button").prop("disabled", true);
          form.find("button").addClass("loading");
        },
        success: function (response) {
          form.find("button").prop("disabled", false);
          form.find("button").removeClass("loading");
          if (response.success) {
            console.log(response.data);
            window.location.href = response.data.redirect;
          }
        },
      });
    });

    $("body").on("submit", ".edit-review-form", function (event) {
      event.preventDefault();

      var form = $(this);
      var formData = form.serialize();
      // Add nonce and action to form_data
      formData += "&action=edit_review";
      formData += "&security=" + theme_vars.edit_review_nonce;

      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: formData,
        beforeSend: function () {
          form.find("button").prop("disabled", true);
          form.find("button").addClass("loading");
        },
        success: function (response) {
          form.find("button").prop("disabled", false);
          form.find("button").removeClass("loading");
          if (response.success) {
            console.log(response.data);
            window.location.href = response.data.redirect;
          }
        },
      });
    });

    $(".delete-review").on("click", function (event) {
      var review_id = $(this).attr("data-review-id");
      $(".togo-modal-delete-review")
        .find("input[name='review_id']")
        .val(review_id);
    });

    $(".action-delete-review").on("click", function (event) {
      event.preventDefault();
      var _this = $(this);
      var review_id = $(".togo-modal-delete-review")
        .find("input[name='review_id']")
        .val();
      $.ajax({
        url: theme_vars.ajax_url,
        type: "POST",
        data: {
          action: "delete_review",
          security: theme_vars.delete_review_nonce,
          review_id: review_id,
        },
        beforeSend: function () {
          _this.addClass("loading");
        },
        success: function (response) {
          _this.removeClass("loading");
          if (response.success) {
            $(".togo-modal-delete-review")
              .find(".togo-modal-footer")
              .prepend('<p class="notice">' + response.data.message + "</p>");
            // Reload page
            window.location.reload();
          } else {
            alert(theme_vars.failed_to_delete_review);
          }
        },
      });
    });
  };

  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/togo-my-reviews.default",
      MyReviewHandler
    );
  });
})(jQuery);
