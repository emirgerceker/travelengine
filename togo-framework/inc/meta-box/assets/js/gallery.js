/**
 * Define class field
 */

var Uxper_GalleryClass = function($container) {
	this.$container = $container;
};

(function ($) {
	"use strict";
	/**
	 * Define class field prototype
	 */
	Uxper_GalleryClass.prototype = {
		init: function() {
			this.select();
			this.remove();
			this.sortable();
		},
		select: function () {
			var _media = new UxperMedia(),
				$addButton = this.$container.find('.uxper-gallery-add');
			_media.selectGallery($addButton, {filter: 'image'}, function(attachments) {
				if (attachments.length) {
					var $this = $(_media.clickedButton);
					var $parent = $this.parent();
					var $input = $parent.find('input[type="hidden"]');
					var valInput = $input.val();
					var arrInput = valInput.split('|');
					var imgHtml = '';
					var url_image='';
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
						if( attachment.sizes){
							if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url;
							else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
							else url_image=attachment.sizes.full.url;
							imgHtml += '<div class="uxper-image-preview" data-id="' + attachment.id + '">';
							imgHtml +='<div class="centered">';
							imgHtml += '<img src="' + url_image + '"/>';
							imgHtml += '</div>';
							imgHtml += '<span class="uxper-gallery-remove dashicons dashicons dashicons-no-alt"></span>';
							imgHtml += '</div>';
						}
					});
					$input.val(valInput);
					$this.before(imgHtml);
					$this.trigger('uxper-gallery-selected');
				}
			});
		},
		remove: function() {
			this.$container.on('click', '.uxper-gallery-remove', function() {
				var $this = $(this).parent();
				var $parent = $this.parent();
				var $input = $parent.find('input[type="hidden"]');
				$this.remove();
				var valInput = '';
				$('.uxper-image-preview', $parent).each(function() {
					if (valInput != '') {
						valInput += '|' + $(this).data('id');
					}
					else {
						valInput = '' + $(this).data('id');
					}
				});
				$input.val(valInput);
				$parent.trigger('uxper-gallery-removed');
			});
		},
		sortable: function () {
			this.$container.sortable({
				placeholder: "uxper-gallery-sortable-placeholder",
				items: '.uxper-image-preview',
				update: function( event, ui ) {
					var $wrapper = $(event.target);
					var valInput = '';
					$('.uxper-image-preview', $wrapper).each(function() {
						if (valInput != '') {
							valInput += '|' + $(this).data('id');
						}
						else {
							valInput = '' + $(this).data('id');
						}
					});
					var $input = $wrapper.find('input[type="hidden"]');
					$input.val(valInput);
					$wrapper.trigger('uxper-gallery-sortable-updated');
				}
			});
		}
	};
})(jQuery);
