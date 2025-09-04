/**
 * gallery field script
 *
 */

(function($) {
	"use strict";

	/**
	 * Define object field
	 */
	var Uxper_GalleryObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-gallery-inner','.uxper-field.uxper-field-gallery').each(function () {
					var field = new Uxper_GalleryClass($(this));
					field.init();
				});
			});

			$('.uxper-field.uxper-field-gallery').on('uxper-gallery-selected uxper-gallery-removed uxper-gallery-sortable-updated ',function(event){
				var $field = $(event.target).closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-gallery').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-gallery-inner');
				if ($items.length) {
					var field = new Uxper_GalleryClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_GalleryObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_GalleryObject);
	});
})(jQuery);