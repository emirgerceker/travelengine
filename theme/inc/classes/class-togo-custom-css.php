<?php
defined('ABSPATH') || exit;

/**
 * Enqueue custom styles.
 */
if (!class_exists('Togo_Custom_Css')) {
	class Togo_Custom_Css
	{

		protected static $instance = null;

		public static function instance()
		{
			if (null === self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize()
		{
			add_action('wp_enqueue_scripts', array($this, 'extra_css'), 9999);
		}

		/**
		 * Responsive styles.
		 *
		 * @access public
		 */
		public function extra_css()
		{
			$custom_css = self::get_customizer_css();
			wp_add_inline_style('togo-style', ':root{' . $custom_css . '}');
		}

		public function get_customizer_css()
		{
			$primary_color = Togo\Helper::setting("primary_color");
			$secondary_color = Togo\Helper::setting("secondary_color");
			$accent_color = Togo\Helper::setting("accent_color");
			$text_color_01 = Togo\Helper::setting("text_color_01");
			$text_color_02 = Togo\Helper::setting("text_color_02");
			$text_color_03 = Togo\Helper::setting("text_color_03");
			$text_color_04 = Togo\Helper::setting("text_color_04");
			$text_color_05 = Togo\Helper::setting("text_color_05");
			$text_color_06 = Togo\Helper::setting("text_color_06");
			$border_color_01 = Togo\Helper::setting("border_color_01");
			$border_color_02 = Togo\Helper::setting("border_color_02");
			$border_color_03 = Togo\Helper::setting("border_color_03");
			$border_color_04 = Togo\Helper::setting("border_color_04");
			$border_color_05 = Togo\Helper::setting("border_color_05");
			$icon_color_01 = Togo\Helper::setting("icon_color_01");
			$icon_color_02 = Togo\Helper::setting("icon_color_02");
			$icon_color_03 = Togo\Helper::setting("icon_color_03");
			$icon_color_04 = Togo\Helper::setting("icon_color_04");
			$icon_color_05 = Togo\Helper::setting("icon_color_05");
			$tone_color_01 = Togo\Helper::setting("tone_color_01");
			$tone_color_02 = Togo\Helper::setting("tone_color_02");
			$tone_color_03 = Togo\Helper::setting("tone_color_03");
			$link_color = Togo\Helper::setting("link_color");
			$typography_body = Togo\Helper::setting("typography_body");
			$typography_heading = Togo\Helper::setting("typography_heading");
			$h1_font_size = Togo\Helper::setting("h1_font_size");
			$h2_font_size = Togo\Helper::setting("h2_font_size");
			$h3_font_size = Togo\Helper::setting("h3_font_size");
			$h4_font_size = Togo\Helper::setting("h4_font_size");
			$h5_font_size = Togo\Helper::setting("h5_font_size");
			$h6_font_size = Togo\Helper::setting("h6_font_size");
			$logo_width = Togo\Helper::setting("logo_width");
			$content_top_spacing = Togo\Helper::get_post_meta('content_top_spacing', '');
			$content_top_spacing_tablet = Togo\Helper::get_post_meta('content_top_spacing_tablet', '');
			$content_top_spacing_mobile = Togo\Helper::get_post_meta('content_top_spacing_mobile', '');
			$content_bottom_spacing = Togo\Helper::get_post_meta('content_bottom_spacing', '');
			$content_bottom_spacing_tablet = Togo\Helper::get_post_meta('content_bottom_spacing_tablet', '');
			$content_bottom_spacing_mobile = Togo\Helper::get_post_meta('content_bottom_spacing_mobile', '');
			$content_overflow_hidden = Togo\Helper::get_post_meta('content_overflow_hidden', '');
			$button_typography = Togo\Helper::setting("button_typography");
			$button_underline_color = Togo\Helper::setting("button_underline_color");
			$button_underline_border_color = Togo\Helper::setting("button_underline_border_color");
			$button_underline_padding = Togo\Helper::setting("button_underline_padding");
			$button_underline_radius = Togo\Helper::setting("button_underline_radius");
			$button_underline_border = Togo\Helper::setting("button_underline_border");
			$button_underline_border_top = Togo\Helper::setting("button_underline_border_top");
			$button_underline_border_right = Togo\Helper::setting("button_underline_border_right");
			$button_underline_border_bottom = Togo\Helper::setting("button_underline_border_bottom");
			$button_underline_border_left = Togo\Helper::setting("button_underline_border_left");
			$button_full_filled_color = Togo\Helper::setting("button_full_filled_color");
			$button_full_filled_background_color = Togo\Helper::setting("button_full_filled_background_color");
			$button_full_filled_padding = Togo\Helper::setting("button_full_filled_padding");
			$button_full_filled_radius = Togo\Helper::setting("button_full_filled_radius");
			$button_full_filled_border = Togo\Helper::setting("button_full_filled_border");
			$button_full_filled_border_color = Togo\Helper::setting("button_full_filled_border_color");
			$button_full_filled_border_top = Togo\Helper::setting("button_full_filled_border_top");
			$button_full_filled_border_right = Togo\Helper::setting("button_full_filled_border_right");
			$button_full_filled_border_bottom = Togo\Helper::setting("button_full_filled_border_bottom");
			$button_full_filled_border_left = Togo\Helper::setting("button_full_filled_border_left");
			$button_border_line_color = Togo\Helper::setting("button_border_line_color");
			$button_border_line_background_color = Togo\Helper::setting("button_border_line_background_color");
			$button_border_line_padding = Togo\Helper::setting("button_border_line_padding");
			$button_border_line_radius = Togo\Helper::setting("button_border_line_radius");
			$button_border_line_border = Togo\Helper::setting("button_border_line_border");
			$button_border_line_border_color = Togo\Helper::setting("button_border_line_border_color");
			$button_border_line_border_top = Togo\Helper::setting("button_border_line_border_top");
			$button_border_line_border_right = Togo\Helper::setting("button_border_line_border_right");
			$button_border_line_border_bottom = Togo\Helper::setting("button_border_line_border_bottom");
			$button_border_line_border_left = Togo\Helper::setting("button_border_line_border_left");
			$page_sidebar_width = Togo\Helper::setting("page_sidebar_width");
			$css = '';

			if ($primary_color) {
				$css .= ' --togo-primary-color: ' . $primary_color . ';';
			}

			if ($secondary_color) {
				$css .= ' --togo-secondary-color: ' . $secondary_color . ';';
			}

			if ($accent_color) {
				$css .= ' --togo-accent-color: ' . $accent_color . ';';
				$css .= ' --togo-accent-color-unrgb: ' . str_replace(['rgb(', ')'], '', $accent_color) . ';';
			}

			if ($text_color_01) {
				$css .= ' --togo-text-color-01: ' . $text_color_01 . ';';
			}

			if ($text_color_02) {
				$css .= ' --togo-text-color-02: ' . $text_color_02 . ';';
			}

			if ($text_color_03) {
				$css .= ' --togo-text-color-03: ' . $text_color_03 . ';';
			}

			if ($text_color_04) {
				$css .= ' --togo-text-color-04: ' . $text_color_04 . ';';
			}

			if ($text_color_05) {
				$css .= ' --togo-text-color-05: ' . $text_color_05 . ';';
			}

			if ($text_color_06) {
				$css .= ' --togo-text-color-06: ' . $text_color_06 . ';';
			}

			if ($border_color_01) {
				$css .= ' --togo-border-color-01: ' . $border_color_01 . ';';
			}

			if ($border_color_02) {
				$css .= ' --togo-border-color-02: ' . $border_color_02 . ';';
			}

			if ($border_color_03) {
				$css .= ' --togo-border-color-03: ' . $border_color_03 . ';';
			}

			if ($border_color_04) {
				$css .= ' --togo-border-color-04: ' . $border_color_04 . ';';
			}

			if ($border_color_05) {
				$css .= ' --togo-border-color-05: ' . $border_color_05 . ';';
			}

			if ($icon_color_01) {
				$css .= ' --togo-icon-color-01: ' . $icon_color_01 . ';';
			}

			if ($icon_color_02) {
				$css .= ' --togo-icon-color-02: ' . $icon_color_02 . ';';
			}

			if ($icon_color_03) {
				$css .= ' --togo-icon-color-03: ' . $icon_color_03 . ';';
			}

			if ($icon_color_04) {
				$css .= ' --togo-icon-color-04: ' . $icon_color_04 . ';';
			}

			if ($icon_color_05) {
				$css .= ' --togo-icon-color-05: ' . $icon_color_05 . ';';
			}

			if ($tone_color_01) {
				$css .= ' --togo-tone-color-01: ' . $tone_color_01 . ';';
			}

			if ($tone_color_02) {
				$css .= ' --togo-tone-color-02: ' . $tone_color_02 . ';';
			}

			if ($tone_color_03) {
				$css .= ' --togo-tone-color-03: ' . $tone_color_03 . ';';
			}

			if ($link_color) {
				$css .= ' --togo-link-color-normal: ' . $link_color['normal'] . ';';
				$css .= ' --togo-link-color-hover: ' . $link_color['hover'] . ';';
			}

			if ($typography_body) {
				$css .= ' --togo-body-font-family: ' . $typography_body['font-family'] . ';';
				$css .= ' --togo-body-font-family-2: "Marcellus", serif;';
				$css .= ' --togo-body-font-weight: ' . $typography_body['font-weight'] . ';';
				$css .= ' --togo-body-font-size: ' . $typography_body['font-size'] . ';';
				$css .= ' --togo-body-line-height: ' . $typography_body['line-height'] . ';';
				$css .= ' --togo-body-letter-spacing: ' . $typography_body['letter-spacing'] . ';';
				$css .= ' --togo-body-font-style: ' . $typography_body['font-style'] . ';';
			}

			if ($typography_heading) {
				$css .= ' --togo-heading-font-family: ' . $typography_heading['font-family'] . ';';
				$css .= ' --togo-heading-font-weight: ' . $typography_heading['font-weight'] . ';';
				$css .= ' --togo-heading-line-height: ' . $typography_heading['line-height'] . ';';
				$css .= ' --togo-heading-letter-spacing: ' . $typography_heading['letter-spacing'] . ';';
				$css .= ' --togo-heading-font-style: ' . $typography_heading['font-style'] . ';';
			}

			if ($h1_font_size) {
				$css .= ' --togo-h1-font-size: ' . $h1_font_size . 'px;';
			}

			if ($h2_font_size) {
				$css .= ' --togo-h2-font-size: ' . $h2_font_size . 'px;';
			}

			if ($h3_font_size) {
				$css .= ' --togo-h3-font-size: ' . $h3_font_size . 'px;';
			}

			if ($h4_font_size) {
				$css .= ' --togo-h4-font-size: ' . $h4_font_size . 'px;';
			}

			if ($h5_font_size) {
				$css .= ' --togo-h5-font-size: ' . $h5_font_size . 'px;';
			}

			if ($h6_font_size) {
				$css .= ' --togo-h6-font-size: ' . $h6_font_size . 'px;';
			}

			if ($logo_width) {
				$css .= ' --togo-logo-width: ' . $logo_width . 'px;';
			}

			if ($content_top_spacing) {
				$css .= ' --togo-content-top-spacing: ' . $content_top_spacing . ';';
			}

			if ($content_top_spacing_tablet) {
				$css .= ' --togo-content-top-spacing-tablet: ' . $content_top_spacing_tablet . ';';
			}

			if ($content_top_spacing_mobile) {
				$css .= ' --togo-content-top-spacing-mobile: ' . $content_top_spacing_mobile . ';';
			}

			if ($content_bottom_spacing) {
				$css .= ' --togo-content-bottom-spacing: ' . $content_bottom_spacing . ';';
			}

			if ($content_bottom_spacing_tablet) {
				$css .= ' --togo-content-bottom-spacing-tablet: ' . $content_bottom_spacing_tablet . ';';
			}

			if ($content_bottom_spacing_mobile) {
				$css .= ' --togo-content-bottom-spacing-mobile: ' . $content_bottom_spacing_mobile . ';';
			}

			if ($content_overflow_hidden) {
				$css .= ' --togo-content-overflow-hidden: ' . $content_overflow_hidden . ';';
			}

			if ($button_typography) {
				$css .= ' --togo-button-font-family: ' . $button_typography['font-family'] . ';';
				$css .= ' --togo-button-font-weight: ' . $button_typography['font-weight'] . ';';
				$css .= ' --togo-button-font-size: ' . $button_typography['font-size'] . ';';
				$css .= ' --togo-button-line-height: ' . $button_typography['line-height'] . ';';
				$css .= ' --togo-button-letter-spacing: ' . $button_typography['letter-spacing'] . ';';
				$css .= ' --togo-button-font-style: ' . $button_typography['font-style'] . ';';
			}

			if ($button_underline_color) {
				$css .= ' --togo-button-underline-color: ' . $button_underline_color['normal'] . ';';
				$css .= ' --togo-button-underline-color-hover: ' . $button_underline_color['hover'] . ';';
			}

			if ($button_underline_border_color) {
				$css .= ' --togo-button-underline-border-color: ' . $button_underline_border_color['normal'] . ';';
				$css .= ' --togo-button-underline-border-color-hover: ' . $button_underline_border_color['hover'] . ';';
			}

			if ($button_underline_padding) {
				$css .= ' --togo-button-underline-padding-top: ' . $button_underline_padding['top'] . ';';
				$css .= ' --togo-button-underline-padding-right: ' . $button_underline_padding['right'] . ';';
				$css .= ' --togo-button-underline-padding-bottom: ' . $button_underline_padding['bottom'] . ';';
				$css .= ' --togo-button-underline-padding-left: ' . $button_underline_padding['left'] . ';';
			}

			if ($button_underline_radius) {
				$css .= ' --togo-button-underline-radius: ' . $button_underline_radius . 'px;';
			}

			if ($button_underline_border) {
				$css .= ' --togo-button-underline-border: ' . $button_underline_border . ';';
			}

			if ($button_underline_border_top) {
				$css .= ' --togo-button-underline-border-top: ' . $button_underline_border_top . 'px;';
			}

			if ($button_underline_border_right) {
				$css .= ' --togo-button-underline-border-right: ' . $button_underline_border_right . 'px;';
			}

			if ($button_underline_border_bottom) {
				$css .= ' --togo-button-underline-border-bottom: ' . $button_underline_border_bottom . 'px;';
			}

			if ($button_underline_border_left) {
				$css .= ' --togo-button-underline-border-left: ' . $button_underline_border_left . 'px;';
			}

			if ($button_full_filled_color) {
				$css .= ' --togo-button-full-filled-color: ' . $button_full_filled_color['normal'] . ';';
				$css .= ' --togo-button-full-filled-color-hover: ' . $button_full_filled_color['hover'] . ';';
			}

			if ($button_full_filled_background_color) {
				$css .= ' --togo-button-full-filled-background-color: ' . $button_full_filled_background_color['normal'] . ';';
				$css .= ' --togo-button-full-filled-background-color-hover: ' . $button_full_filled_background_color['hover'] . ';';
			}

			if ($button_full_filled_border_color) {
				$css .= ' --togo-button-full-filled-border-color: ' . $button_full_filled_border_color['normal'] . ';';
				$css .= ' --togo-button-full-filled-border-color-hover: ' . $button_full_filled_border_color['hover'] . ';';
			}

			if ($button_full_filled_padding) {
				$css .= ' --togo-button-full-filled-padding-top: ' . $button_full_filled_padding['top'] . ';';
				$css .= ' --togo-button-full-filled-padding-right: ' . $button_full_filled_padding['right'] . ';';
				$css .= ' --togo-button-full-filled-padding-bottom: ' . $button_full_filled_padding['bottom'] . ';';
				$css .= ' --togo-button-full-filled-padding-left: ' . $button_full_filled_padding['left'] . ';';
			}

			if ($button_full_filled_radius) {
				$css .= ' --togo-button-full-filled-radius: ' . $button_full_filled_radius . 'px;';
			}

			if ($button_full_filled_border) {
				$css .= ' --togo-button-full-filled-border: ' . $button_full_filled_border . ';';
			}

			if ($button_full_filled_border_top) {
				$css .= ' --togo-button-full-filled-border-top: ' . $button_full_filled_border_top . 'px;';
			}

			if ($button_full_filled_border_right) {
				$css .= ' --togo-button-full-filled-border-right: ' . $button_full_filled_border_right . 'px;';
			}

			if ($button_full_filled_border_bottom) {
				$css .= ' --togo-button-full-filled-border-bottom: ' . $button_full_filled_border_bottom . 'px;';
			}

			if ($button_full_filled_border_left) {
				$css .= ' --togo-button-full-filled-border-left: ' . $button_full_filled_border_left . 'px;';
			}

			if ($button_border_line_color) {
				$css .= ' --togo-button-border-line-color: ' . $button_border_line_color['normal'] . ';';
				$css .= ' --togo-button-border-line-color-hover: ' . $button_border_line_color['hover'] . ';';
			}

			if ($button_border_line_background_color) {
				$css .= ' --togo-button-border-line-background-color: ' . $button_border_line_background_color['normal'] . ';';
				$css .= ' --togo-button-border-line-background-color-hover: ' . $button_border_line_background_color['hover'] . ';';
			}

			if ($button_border_line_border_color) {
				$css .= ' --togo-button-border-line-border-color: ' . $button_border_line_border_color['normal'] . ';';
				$css .= ' --togo-button-border-line-border-color-hover: ' . $button_border_line_border_color['hover'] . ';';
			}

			if ($button_border_line_border) {
				$css .= ' --togo-button-border-line-border: ' . $button_border_line_border . ';';
			}

			if ($button_border_line_border_top) {
				$css .= ' --togo-button-border-line-border-top: ' . $button_border_line_border_top . 'px;';
			}

			if ($button_border_line_border_right) {
				$css .= ' --togo-button-border-line-border-right: ' . $button_border_line_border_right . 'px;';
			}

			if ($button_border_line_border_bottom) {
				$css .= ' --togo-button-border-line-border-bottom: ' . $button_border_line_border_bottom . 'px;';
			}

			if ($button_border_line_border_left) {
				$css .= ' --togo-button-border-line-border-left: ' . $button_border_line_border_left . 'px;';
			}

			if ($button_border_line_radius) {
				$css .= ' --togo-button-border-line-radius: ' . $button_border_line_radius . 'px;';
			}

			if ($button_border_line_padding) {
				$css .= ' --togo-button-border-line-padding-top: ' . $button_border_line_padding['top'] . ';';
				$css .= ' --togo-button-border-line-padding-right: ' . $button_border_line_padding['right'] . ';';
				$css .= ' --togo-button-border-line-padding-bottom: ' . $button_border_line_padding['bottom'] . ';';
				$css .= ' --togo-button-border-line-padding-left: ' . $button_border_line_padding['left'] . ';';
			}

			if ($page_sidebar_width) {
				$css .= ' --togo-page-sidebar-width: ' . $page_sidebar_width . 'px;';
			}

			return $css;
		}
	}

	Togo_Custom_Css::instance()->initialize();
}
