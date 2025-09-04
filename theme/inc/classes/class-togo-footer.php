<?php
defined('ABSPATH') || exit;

if (!class_exists('Togo_Footer')) {

	class Togo_Footer
	{

		protected static $instance  = null;

		public static function instance()
		{
			if (null === self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize()
		{
			add_action('togo_render_footer', [$this, 'footer_html']);
		}

		public function footer_html()
		{
			$footer_enable_footer = Togo\Helper::setting('footer_enable_footer');
			$footer_enable = Togo\Helper::get_post_meta('footer_enable') ? Togo\Helper::get_post_meta('footer_enable') : $footer_enable_footer;
			$footer_type = Togo\Theme::get_footer_type();

			if ($footer_enable == 'yes') :
				echo '<footer id="footer" class="site-footer">';
				if (!function_exists('elementor_location_exits') || !elementor_location_exits('footer', true)) {
					if ($footer_type !== '0' && !empty($footer_type)) {
						if (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->db->is_built_with_elementor($footer_type)) {
							echo \Elementor\Plugin::$instance->frontend->get_builder_content($footer_type);
						} else {
							$footer = get_post($footer_type);
							if ($footer) {
								$footer_content = $footer->post_content;
								echo wp_kses_post($footer_content);
							}
						}
					}

					$footer_copyright_enable = Togo\Helper::setting('footer_copyright_enable');
					if ($footer_copyright_enable == '1') {
						get_template_part('templates/footer/copyright');
					}
				} else {
					elementor_theme_do_location('footer');
				}
				echo '</footer>';
			endif;
		}
	}

	Togo_Footer::instance()->initialize();
}
