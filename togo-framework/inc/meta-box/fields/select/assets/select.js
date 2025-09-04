/**
 * select field script
 *
 */

var Uxper_SelectClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_SelectClass.prototype = {
		init: function() {
			this.$container.find('.uxper-select').on('change', function() {
				var $field = $(this).closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_SelectObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-select-inner').each(function () {
					var field = new Uxper_SelectClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-select').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-select-inner');
				if ($items.length) {
					var field = new Uxper_SelectClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_SelectObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_SelectObject);
	});
})(jQuery);