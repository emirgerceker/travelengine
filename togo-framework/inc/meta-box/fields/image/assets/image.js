/**
 * image field script
 *
 */

var Uxper_ImageClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_ImageClass.prototype = {
		init: function() {
			this.selectMedia();
		},
		selectMedia: function() {
			var self = this,
				$idField = self.$container.find('.uxper-image-id'),
				$urlField = self.$container.find('.uxper-image-url'),
				$chooseImage = self.$container.find('.uxper-image-choose-image'),
				$removeButton = self.$container.find('.uxper-image-remove'),
				$preview = self.$container.find('.uxper-image-preview img'),
				$selectImageDefaultDir = self.$container.find('.uxper-image-choose-image-dir');

			if ($selectImageDefaultDir.length) {
				$.ajax({
					url: UxperMetaData.ajax_url,
					data: {
						action: 'uxper_select_default_image'
					},
					success: function (res) {
						var $popup = $('.uxper-image-default-popup');
						if ($popup.length == 0) {
							$popup = $(res);
							$('body').append($popup);
							self.imageDefaultPopupEvent($popup);
						}
					}
				});
			}
			$selectImageDefaultDir.on('click', function() {
				var $popup = $('.uxper-image-default-popup');
				if (!$popup.length) {
					return;
				}
				$popup.data('urlField', $urlField);
				$popup.data('idField', $idField);
				$popup.data('previewField', $preview);
				$popup.show();
			});

			/**
			 * Init Media
			 */
			var _media = new UxperMedia();
			_media.selectImage($chooseImage, {filter: 'image'}, function(attachment) {
				if (attachment) {
					var thumb_url = '';
					if (attachment.sizes.thumbnail == undefined) {
						if( attachment == 'svg' ){
							thumb_url = attachment.url;
						}else{
							thumb_url = attachment.sizes.full.url;
						}
					}
					else {
						thumb_url = attachment.sizes.thumbnail.url;
					}
					$preview.attr('src', thumb_url);
					$preview.show();
					$idField.val(attachment.id);
					$urlField.val(attachment.url);

					self.changeField(self.$container);
				}
			});

			/**
			 * Remove Image
			 */
			$removeButton.on('click', function() {
				$preview.attr('src', '');
				$preview.hide();
				$idField.val('');
				$urlField.val('');

				self.changeField(self.$container);
			});

			$urlField.on('change', function() {
				$.ajax({
					url: UxperMetaData.ajax_url,
					data: {
						action: 'uxper_get_attachment_id',
						url: $urlField.val()
					},
					type: 'GET',
					error: function() {
						$idField.val('0');
					},
					success: function(res) {
						$idField.val(res);
					}
				});
				if ($urlField.val() == '') {
					$preview.attr('src', '');
					$preview.hide();
				}
				else {
					$preview.attr('src', $urlField.val());
					$preview.show();
				}
			});
		},
		imageDefaultPopupEvent: function($popup) {
			var self = this;
			$popup.find('.uxper-image-default-popup-content > h1 > span').on('click', function() {
				$popup.hide();
			});
			$popup.find('.uxper-image-default-popup-item').on('click', function() {
				var $img = $(this).find('img'),
					src = $img.attr('src');

				$popup.data('previewField').attr('src', src);
				$popup.data('previewField').show();
				$popup.data('idField').val('0');
				$popup.data('urlField').val(src);
				$popup.hide();
				self.changeField(self.$container);
			});
		},
		changeField: function($item) {
			var $field = $item.closest('.uxper-field'),
				value = UxperFieldsConfig.fields.getValue($field);
			UxperFieldsConfig.required.checkRequired($field, value);
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_ImageObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-image-inner').each(function () {
					var field = new Uxper_ImageClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-image').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-image-inner');
				if ($items.length) {
					var field = new Uxper_ImageClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_ImageObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_ImageObject);
	});
})(jQuery);