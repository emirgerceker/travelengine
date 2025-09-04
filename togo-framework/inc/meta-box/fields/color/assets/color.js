/**
 * color field script
 *
 */

var Uxper_ColorClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_ColorClass.prototype = {
		init: function() {
			var data = $.extend(
				{
					change: function () {
						var $this = $(this),
							$field = $this.closest('.uxper-field');
						if (!$this.hasClass('uxper-color-init-done')) {
							$this.addClass('uxper-color-init-done');
						}
						else {
							setTimeout(function() {
								var value = UxperFieldsConfig.fields.getValue($field);
								UxperFieldsConfig.required.checkRequired($field, value);
							}, 50);
						}
					},
					clear: function () {
						var $field = $(this).closest('.uxper-field');

						setTimeout(function() {
							var value = UxperFieldsConfig.fields.getValue($field);
							UxperFieldsConfig.required.checkRequired($field, '');
						}, 50);
					}
				}
			);
			this.$container.find('.uxper-color').wpColorPicker(data);
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_ColorObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-color-inner').each(function () {
					var field = new Uxper_ColorClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-color').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-color-inner');
				if ($items.length) {
					var field = new Uxper_ColorClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_ColorObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_ColorObject);
	});
})(jQuery);