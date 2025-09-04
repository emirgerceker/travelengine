/**
 * checkbox_list field script
 */

/**
 * Define class field
 */
var Uxper_CheckboxListClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_CheckboxListClass.prototype = {
		init: function() {
			this.$container.find('input.uxper-checkbox_list').on('change', function() {
				var $field = $(this).closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_CheckboxListObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-checkbox_list-inner').each(function () {
					var field = new Uxper_CheckboxListClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-checkbox_list').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-checkbox_list-inner');
				if ($items.length) {
					var field = new Uxper_CheckboxListClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_CheckboxListObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_CheckboxListObject);
	});
})(jQuery);