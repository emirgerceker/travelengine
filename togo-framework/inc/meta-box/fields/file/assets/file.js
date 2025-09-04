/**
 * file field script
 *
 */

var Uxper_FileClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	Uxper_FileClass.prototype = {
		init: function() {
			this.select();
			this.remove();
			this.sortable();
		},
		select: function() {
			var self = this,
				$button = self.$container.find('.uxper-file-add > button'),
				library_filter = self.$container.data('lib-filter'),
				options = {
					title: sfFileFieldMeta.title,
					button: sfFileFieldMeta.button
				},
				_media = new UxperMedia();
			if ((typeof (library_filter) != "undefined") && (library_filter != null) && (library_filter != '')) {
				options.filter = library_filter;
			}

			_media.selectGallery($button, options, function(attachments) {
				if (attachments.length) {
					var $this = $(_media.clickedButton),
						$input = self.$container.find('input[type="hidden"]'),
						valInput = $input.val(),
						arrInput = valInput.split('|'),
						imgHtml = '',
						removeText = self.$container.data('remove-text');
					attachments.each(function(attachment) {
						attachment = attachment.toJSON();

						if (arrInput.indexOf('' + attachment.id) != -1) {
							return;
						}
						if (valInput != '') {
							valInput += '|' + attachment.id;
						}
						else {
							valInput = '' + attachment.id;
						}
						arrInput.push('' + attachment.id);
						imgHtml += '<div class="uxper-file-item" data-file-id="' + attachment.id + '">';
						imgHtml += '<span class="dashicons dashicons-media-document"></span>';
						imgHtml +='<div class="uxper-file-info">';
						imgHtml += '<a class="uxper-file-title" href="' + attachment.editLink + '" target="_blank">' + attachment.title + '</a>';
						imgHtml += '<div class="uxper-file-name">' + attachment.filename + '</div>';
						imgHtml += '<div class="uxper-file-action">';
						imgHtml += '<span class="uxper-file-remove"><span class="dashicons dashicons-no-alt"></span> ' + removeText + '</span>';
						imgHtml += '</div>';
						imgHtml += '</div>';
						imgHtml += '</div>';
					});
					$input.val(valInput);

					var $element = $(imgHtml);

					$this.parent().before($element);

					self.remove($element);

					var $field = $this.closest('.uxper-field'),
						value = UxperFieldsConfig.fields.getValue($field);
					UxperFieldsConfig.required.checkRequired($field, value);
				}
			});
		},
		remove: function($item) {
			if (typeof ($item) === "undefined") {
				$item = this.$container;
			}
			$item.find('.uxper-file-remove').on('click', function() {
				var $this = $(this).closest('.uxper-file-item');
				var $parent = $this.parent();
				var $input = $parent.find('input[type="hidden"]');
				$this.remove();
				var valInput = '';
				$('.uxper-file-item', $parent).each(function() {
					if (valInput != '') {
						valInput += '|' + $(this).data('file-id');
					}
					else {
						valInput = '' + $(this).data('file-id');
					}
				});
				$input.val(valInput);

				var $field = $parent.closest('.uxper-field'),
					value = UxperFieldsConfig.fields.getValue($field);
				UxperFieldsConfig.required.checkRequired($field, value);
			});
		},
		sortable: function () {
			this.$container.sortable({
				placeholder: "uxper-file-sortable-placeholder",
				items: '.uxper-file-item',
				handle: '.dashicons-media-document',
				update: function( event, ui ) {
					var $wrapper = $(event.target);
					var valInput = '';
					$('.uxper-file-item', $wrapper).each(function() {
						if (valInput != '') {
							valInput += '|' + $(this).data('file-id');
						}
						else {
							valInput = '' + $(this).data('file-id');
						}
					});
					var $input = $wrapper.find('input[type="hidden"]');
					$input.val(valInput);

					var $field = $wrapper.closest('.uxper-field'),
						value = UxperFieldsConfig.fields.getValue($field);
					UxperFieldsConfig.required.checkRequired($field, value);
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var Uxper_FileObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.uxper-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('uxper_make_template_done', function() {
				$('.uxper-field-file-inner').each(function () {
					var field = new Uxper_FileClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.uxper-field.uxper-field-file').on('uxper_add_clone_field', function(event){
				var $items = $(event.target).find('.uxper-field-file-inner');
				if ($items.length) {
					var field = new Uxper_FileClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		Uxper_FileObject.init();
		UxperFieldsConfig.fieldInstance.push(Uxper_FileObject);
	});
})(jQuery);