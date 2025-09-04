(function($) {
	"use strict";

	//clear form after submit
	$( document ).ajaxComplete(function( event, xhr, settings ) {
		try{
			var $respo = $.parseXML(xhr.responseText);
			//exit on error
			if ($($respo).find('wp_error').length) return;
			if ($($respo).find('.uxper-term-meta-item-wrapper').length) {
				return;
			}

			var $taxWrappe = $('.uxper-term-meta-wrapper'),
				taxonomy = $taxWrappe.data('taxonomy');
			$.ajax({
				type: "GET",
				url: UxperMetaData.ajax_url,
				data: {
					action: 'uxper_tax_meta_form',
					taxonomy: taxonomy
				},
				success : function(res) {
					$taxWrappe.html(res);
					for (var i = 0; i < UxperFieldsConfig.fieldInstance.length; i++) {
						UxperFieldsConfig.fieldInstance[i].init();
					}
					UxperFieldsConfig.onReady.init();
				}
			});

		}catch(err) {}
	});
})(jQuery);