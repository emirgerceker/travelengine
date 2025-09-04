/**
 * Ace-editor field script
 */

/**
 * Define class field
 */
var Uxper_AceEditorClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_AceEditorClass.prototype = {
		init: function() {
			this.$fieldText = this.$container.find('textarea');
			this.$editorField = this.$container.find('.uxper-ace-editor');
			var params = this.$fieldText.data('options'),
				mode = this.$fieldText.data('mode'),
				theme = this.$fieldText.data('theme');
			this.editor = ace.edit(this.$editorField.attr('id'));
			this.$editorField.attr('id', '');
			if (mode != '') {
				this.editor.session.setMode('ace/mode/' + mode);
			}
			if (theme != '') {
				this.editor.setTheme('ace/theme/' + theme);
			}

			this.editor.setAutoScrollEditorIntoView(true);
			this.editor.setOptions(params);
			var self = this;
			this.editor.on('change', function (event) {
				self.$fieldText.val(self.editor.getSession().getValue());

				var $field = self.$container.closest('.uxper-field');
				$field.trigger('Uxper_Field_change');
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_AceEditorObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-ace-editor-inner').each(function () {
					var field = new Uxper_AceEditorClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-ace_editor').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-ace-editor-inner');
				if ($items.length) {
					var field = new Uxper_AceEditorClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_AceEditorObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_AceEditorObject);
	});
})(jQuery);