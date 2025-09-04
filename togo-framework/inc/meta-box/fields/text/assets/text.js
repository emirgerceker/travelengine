/**
 * text field script
 *
 */

var Uxper_TextClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	Uxper_TextClass.prototype = {
		init: function() {
			this.slider();
			this.onChange();
			this.unique_id();
		},
		slider: function() {
			this.$container.find('.uxper-text[type="range"]').each(function() {
				var $this = $(this),
					$parent = $this.closest('.uxper-field-text-inner');
				$parent.append('<span class="uxper-text-range-info">' + $this.val() + '</span>');

				/**
				 * Slide drag
				 */
				this.oninput = function() {
					$(this).next().text($(this).val());
				}
			});
		},

		onChange: function() {
			this.$container.find('.uxper-text[data-field-control]').on('change', function() {
				var $this = $(this),
					type = $this.attr('type');
				var $field = $this.closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});
		},
		unique_id : function() {
			this.$container.find('.uxper-text[data-unique_id="true"]').each(function(){
				var $this = $(this),
					prefix = $this.data('unique_id-prefix'),
					$field = $this.closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				if (value === '') {
					var random =  Math.floor(Math.random() * (999999 - 100000)) + 100000;
					$this.val(prefix + random);
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_TextObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-text-inner').each(function () {
					var field = new Uxper_TextClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-text').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-text-inner');
				if ($items.length) {
					var field = new Uxper_TextClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_TextObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_TextObject);
	});
})(jQuery);