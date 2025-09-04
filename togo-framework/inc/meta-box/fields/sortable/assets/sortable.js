/**
 * Define class field
 */
var Uxper_SortableClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_SortableClass.prototype = {
		init: function() {
			this.$container.sortable({
				placeholder: 'uxper-sortable-sortable-placeholder',
				items: '.uxper-field-sortable-item',
				handle: '.dashicons-menu',
				update: function (event, ui) {
					var $wrapper = $(event.target);

					var sortValue = '';
					$wrapper.find('input[type="checkbox"]').each(function() {
						var $this = $(this);
						if (sortValue === '') {
							sortValue += $this.val();
						}
						else {
							sortValue += '|' + $this.val();
						}
					});

					$wrapper.find('.uxper-field-sortable-sort').val(sortValue);


					var $field = $wrapper.closest('.uxper-field'),
						value = UxperFieldsConfig.fields.getValue($field);
					UxperFieldsConfig.required.checkRequired($field, value);
				}
			});

			$('.uxper-field-sortable-inner .uxper-field-sortable-checkbox').change(function() {
				var $field = $(this).closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_SortableObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-sortable-inner').each(function () {
					var field = new Uxper_SortableClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-sortable').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-sortable-inner');
				if ($items.length) {
					var field = new Uxper_SortableClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_SortableObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_SortableObject);
	});
})(jQuery);