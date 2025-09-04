/**
 * panel field script
 *
 */

var Uxper_PanelClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_PanelClass.prototype = {
		init: function() {
			var self = this;
			self.toggleElement();
			self.panelTitleElement();
		},

		panelTitleElement: function() {
			var $panelTitle = this.$container.find('[data-panel-title="true"]:first');
			$panelTitle.on('change', function() {
				var $this = $(this),
					value = $this.val(),
					$title = $this.closest('.uxper-clone-field-panel').find('.uxper-panel-title'),
					label = $title.data('label');
				if (value == '') {
					$title.text(label);
				}
				else {
					$title.text(label + ': ' + value);
				}
			});
			$panelTitle.trigger('change');
		},

		toggleElement: function($element) {
			var $toggle = this.$container.find('> h4'),
				$inner = this.$container.find('>.uxper-clone-field-panel-inner');
			$toggle.on('click', function(event) {
				if ($(event.target).closest('.uxper-clone-button-remove').length == 0) {
					$toggle.find('.uxper-panel-toggle').toggleClass('dashicons-arrow-up').toggleClass('dashicons-arrow-down');
					$inner.slideToggle();
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_PanelObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-clone-field-panel').each(function () {
					var field = new Uxper_PanelClass($(this));
					field.init();
				});
				Uxper_PanelObject.sortableFieldPanel();
				Uxper_PanelObject.addCloneButton();
			});
		},
		addCloneButton: function() {
			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-panel').on('uxper_add_clone_field', function(event){
				var $items = $(event.target);
				if ($items.length) {
					var field = new Uxper_PanelClass($items);
					field.init();

					UxperFieldsConfig.cloneField.makeCloneTemplateElement($items);
					$items.find('.uxper-field').each(function() {
						var $field = $(this),
							fieldType = $field.data('field-type');
						if (typeof (fieldType) != 'undefined') {
							var $container = $field.find('.uxper-field-' + fieldType + '-inner');
							try {
								var field = eval("new " + Uxper_PanelObject.getFieldClass(fieldType) + "($container)");
								field.init();
							}
							catch (ex) {}
						}
					});
					$items.find('.uxper-field').each(function() {
						var $field = $(this);
						$field.on('uxper_check_required', UxperFieldsConfig.required.onChangeEvent);
						$field.trigger('uxper_check_required');
						$field.trigger('uxper_check_preset');
					});
				}
			});
		},
		getFieldClass: function(fieldType) {
			var arr = fieldType.split('_');
			for (var i = 0; i < arr.length; i++) {
				arr[i] = this.ucwords(arr[i]);
			}
			return 'Uxper_' + arr.join('') + 'Class';
		},
		ucwords: function(str) {
			return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
				return $1.toUpperCase();
			})
		},
		sortableFieldPanel: function() {
			var self = this;
			$('.uxper-field-panel-sortable').sortable({
				placeholder: "uxper-field-panel-sortable-placeholder",
				handle: '.uxper-field-panel-title',
				items: '.uxper-clone-field-panel',
				update: function(event) {
					var $wrapper = $(event.target),
						$field = $wrapper.closest('.uxper-field');
					UxperFieldsConfig.cloneField.reIndexFieldName($wrapper.parent(), false);
					$field.trigger('Uxper_Field_change');
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_PanelObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_PanelObject);
	});
})(jQuery);