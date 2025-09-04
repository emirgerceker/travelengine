/**
 * your_field field script
 *
 */

var Uxper_YourFieldClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_YourFieldClass.prototype = {
		init: function() {
			this.$container.find('.uxper-textarea').on('change', function() {
				var $field = $(this).closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_YourFieldObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-your_field-inner').each(function () {
					var field = new Uxper_YourFieldClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-your_field').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-your_field-inner');
				if ($items.length) {
					var field = new Uxper_YourFieldClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_YourFieldObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_YourFieldObject);
	});
})(jQuery);