
var Uxper_ButtonSetClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_ButtonSetClass.prototype = {
		init: function() {
			var self = this;

			self.allowClearChecked = false;

			self.$container.find('[data-field-control]').on('change', function() {
				var $field = $(this).closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});

			self.$container.find('.uxper-allow-clear').on('click mousedown', function(event) {
				var $input = $(this).closest('label').find('input[type="radio"]');

				if ($input.length > 0) {
					if (event.type == 'click') {
						setTimeout(function() {
							if (self.allowClearChecked) {
								$input[0].checked = false;
							}
						}, 10);
					}
					else {
						self.allowClearChecked = $input[0].checked;
					}
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_ButtonSetObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-button_set-inner').each(function () {
					var field = new Uxper_ButtonSetClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-button_set').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-button_set-inner');
				if ($items.length) {
					var field = new Uxper_ButtonSetClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_ButtonSetObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_ButtonSetObject);
	});
})(jQuery);