<?php

/**
 * Default Customizer Options
 *
 * @package togo
 */

/**
 *  Get default options
 */
if (!function_exists('togo_get_default_theme_options')) {
	function togo_get_default_theme_options()
	{
		$defaults = array();

		/**
		 *  General
		 */
		$defaults['logo_width']        = 110;
		$defaults['logo_dark']         = TOGO_IMAGES . 'logo-dark.png';
		$defaults['logo_dark_retina']  = TOGO_IMAGES . 'logo-dark-retina.png';
		$defaults['logo_light']        = TOGO_IMAGES . 'logo-light.png';
		$defaults['logo_light_retina'] = TOGO_IMAGES . 'logo-light-retina.png';

		$defaults['type_loading_effect']      = 'none';
		$defaults['animation_loading_effect'] = 'css-1';
		$defaults['image_loading_effect']     = '';

		$defaults['url_facebook']    = '';
		$defaults['url_twitter']     = '';
		$defaults['url_instagram']   = '';
		$defaults['url_youtube']     = '';
		$defaults['url_google_plus'] = '';
		$defaults['url_skype']       = '';
		$defaults['url_linkedin']    = '';
		$defaults['url_pinterest']   = '';
		$defaults['url_slack']       = '';
		$defaults['url_rss']         = '';

		/**
		 *  Color
		 */
		$defaults['primary_color'] = '#111111';
		$defaults['secondary_color'] = 'rgba(235,96,26,0.2)';
		$defaults['accent_color'] = 'rgb(253,70,33)';
		$defaults['text_color_01'] = '#111111';
		$defaults['text_color_02'] = '#555555';
		$defaults['text_color_03'] = '#333333';
		$defaults['text_color_04'] = '#ffffff';
		$defaults['text_color_05'] = '#999999';
		$defaults['text_color_06'] = '#dddddd';
		$defaults['border_color_01'] = '#111111';
		$defaults['border_color_02'] = '#dddddd';
		$defaults['border_color_03'] = '#f9f9f9';
		$defaults['border_color_04'] = '#eeeeee';
		$defaults['border_color_05'] = '#cccccc';
		$defaults['icon_color_01'] = '#111111';
		$defaults['icon_color_02'] = '#555555';
		$defaults['icon_color_03'] = '#999999';
		$defaults['icon_color_04'] = '#dddddd';
		$defaults['icon_color_05'] = '#ffffff';
		$defaults['tone_color_01'] = '#ED0006';
		$defaults['tone_color_02'] = '#FFD75E';
		$defaults['tone_color_03'] = '#3AB446';

		/**
		 *  Typography
		 */
		$defaults['font-family']    = '"DM Sans", system-ui';
		$defaults['font-size']      = '16px';
		$defaults['variant']        = 400;
		$defaults['line-height']    = 1.25;
		$defaults['letter-spacing'] = 'inherit';

		$defaults['heading-font-family']    = '"Outfit", sans-serif';
		$defaults['heading-line-height']    = 'inherit';
		$defaults['heading-variant']        = 600;
		$defaults['heading-letter-spacing'] = 'inherit';

		/**
		 *  Button
		 */
		// General
		$defaults['button_font_family']            = '"DM Sans", system-ui';
		$defaults['button_font_size']              = '16px';
		$defaults['button_line_height']            = '1.4';
		$defaults['button_variant']                = 500;
		$defaults['button_letter_spacing']         = '0';
		$defaults['button_text_transform']         = 'uppercase';

		// Full Filled
		$defaults['button_full_filled_color']                  = '#ffffff';
		$defaults['button_full_filled_hover_color']            = '#ffffff';
		$defaults['button_full_filled_background_color']       = '#FD4621';
		$defaults['button_full_filled_hover_background_color'] = '#FD4621';
		$defaults['button_full_filled_radius']                 = 24;
		$defaults['button_full_filled_border']                 = 'none';
		$defaults['button_full_filled_border_color']           = '#FD4621';
		$defaults['button_full_filled_hover_border_color']           = '#FD4621';

		// Underline
		$defaults['button_underline_color']                  = '#111111';
		$defaults['button_underline_hover_color']            = '#FD4621';
		$defaults['button_underline_border_color']       = '#111111';
		$defaults['button_underline_hover_border_color'] = '#FD4621';
		$defaults['button_underline_radius']                 = 0;
		$defaults['button_underline_border']                 = 'solid';

		// Border Line
		$defaults['button_border_line_color']                  = '#111111';
		$defaults['button_border_line_hover_color']            = '#ffffff';
		$defaults['button_border_line_background_color']       = '#ffffff';
		$defaults['button_border_line_hover_background_color'] = '#FD4621';
		$defaults['button_border_line_radius']                 = 24;
		$defaults['button_border_line_border']                 = 'solid';
		$defaults['button_border_line_border_color']           = '#111111';
		$defaults['button_border_line_hover_border_color']           = '#FD4621';

		/**
		 *  Layout
		 */
		$defaults['layout_content']           = 'fullwidth';
		$defaults['boxed_width']              = 1170;
		$defaults['body_background_color']    = '#ffffff';
		$defaults['content_background_color'] = '#ffffff';
		$defaults['bg_body_image']            = '';
		$defaults['bg_body_size']             = 'auto';
		$defaults['bg_body_repeat']           = 'no-repeat';
		$defaults['bg_body_position']         = 'left top';
		$defaults['bg_body_attachment']       = 'scroll';

		/**
		 *  Header
		 */
		$defaults['header_type']           = '';
		$defaults['top_bar_type']          = '';
		$defaults['header_overlay']        = '0';
		$defaults['header_float']        = '0';
		$defaults['header_padding_top']    = '20';
		$defaults['header_padding_bottom'] = '20';

		/**
		 *  Footer
		 */
		$defaults['footer_type']           = '';
		$defaults['footer_copyright_text'] = esc_attr__('© 2025 Uxper. All rights reserved', 'togo');

		/**
		 *  Page Title
		 */
		$defaults['page_top_bar_type']           = '';
		$defaults['page_header_type']           = '';
		$defaults['page_title_type']           = '0';

		/**
		 *  Blog
		 */
		$defaults['blog_archive_active_sidebar']           = 'blog_sidebar';
		$defaults['blog_archive_sidebar_position']         = 'right';
		$defaults['blog_archive_sidebar_width']            = 370;
		$defaults['blog_archive_page_title_layout']        = '';
		$defaults['blog_archive_pagination_position']      = 'center';
		$defaults['blog_archive_top_bar_type']              = '';
		$defaults['blog_archive_header_type']              = '';
		$defaults['blog_card_layout']              = 'default';

		$defaults['single_post_active_sidebar']         = 'blog_sidebar';
		$defaults['single_post_style']       = '01';
		$defaults['single_post_sidebar_position']       = 'right';
		$defaults['single_post_sidebar_width']          = 370;
		$defaults['single_post_page_title_layout']      = 'none';
		$defaults['single_post_share']      = 'hide';
		$defaults['single_post_top_bar_type']            = '';
		$defaults['single_post_header_type']            = '';

		/**
		 *  Page
		 */
		$defaults['page_active_sidebar']    = 'page_sidebar';
		$defaults['page_sidebar_position']  = 'none';
		$defaults['page_sidebar_width']     = 370;
		$defaults['page_page_title_layout'] = '01';

		if (class_exists('WooCommerce')) {
			/**
			 *  Shop
			 */
			$defaults['product_archive_page_title_layout']   = '01';
			$defaults['product_archive_sidebar_position']   = 'left';
			$defaults['product_archive_active_sidebar']   = 'woocommerce_sidebar';
		}

		return $defaults;
	}
}

/**
 * Get Setting
 */
if (!function_exists('togo_get_setting')) {
	function togo_get_setting($key, $default = '')
	{
		$option = '';
		$option = \Togo\Kirki::get_option('theme', $key);

		return (!empty($option)) ? $option : $default;
	}
}
