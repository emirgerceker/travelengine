<?php
defined('ABSPATH') || exit;

if (!class_exists('Togo_Customize')) {
	class Togo_Customize
	{

		protected static $instance = null;
		private $wp_customize;

		static function instance()
		{
			if (null === self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize()
		{
			add_action('customize_preview_init', array($this, 'togo_customizer_live_preview'));
			add_action('customize_controls_enqueue_scripts', array($this, 'togo_customize_enqueue'), 10);
			add_action('customize_register', array($this, 'customize_register'));
			add_filter('kirki_fonts_standard_fonts', array($this, 'togo_add_custom_font'));
			add_action('wp_enqueue_scripts', array($this, 'togo_add_custom_font_css'));
			add_action('wp_ajax_customizer_import', array($this, 'ajax_customizer_import'));
			add_action('wp_ajax_customizer_reset', array($this, 'ajax_customizer_reset'));
			add_action('wp_ajax_togo_header_builder', array($this, 'togo_header_builder'));
			add_action('wp_ajax_togo_topbar_builder', array($this, 'togo_topbar_builder'));
			add_action('wp_ajax_togo_header_delete_builder', array($this, 'togo_header_delete_builder'));
			add_action('wp_ajax_togo_topbar_delete_builder', array($this, 'togo_topbar_delete_builder'));
		}

		function togo_customizer_live_preview()
		{
			wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/inc/admin/customizer/assets/libs/jquery-ui/jquery-ui.min.js', NULL, TOGO_THEME_VERSION, true);
			wp_enqueue_script('togo-customize-preview', get_template_directory_uri() . '/inc/admin/customizer/assets/js/preview.js', array('jquery', 'customize-preview'), '', true);
			wp_localize_script(
				'togo-customize-preview',
				'customize_preview',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'delete' => __('Do you want to delete current layout?', 'togo'),
				)
			);

			wp_enqueue_style('jquery-ui', get_template_directory_uri() . '/inc/admin/customizer/assets/libs/jquery-ui/jquery-ui.min.css', array(), '', 'all');
			wp_enqueue_style('togo-preview', get_template_directory_uri() . '/inc/admin/customizer/assets/css/preview.css', array());
		}

		function togo_customize_enqueue()
		{
			wp_enqueue_style('togo-customize', get_template_directory_uri() . '/inc/admin/customizer/assets/css/customize.css', array());
			wp_enqueue_script('togo-customize-script', get_template_directory_uri() . '/inc/admin/customizer/assets/js/customize.js', array('jquery'), false, true);
			wp_localize_script('togo-customize-script', 'customizeScript', array(
				'ajaxurl' => admin_url('admin-ajax.php', 'relative'),
				'reset'   => __('Reset', 'togo'),
				'import'  => __('Do you want to import customizer options?', 'togo'),
				'export'  => __('Do you want to export customizer options?', 'togo'),
				'confirm' => __("Attention! This will remove all customizations ever made via customizer to this theme!\n\nThis action is irreversible!", 'togo'),
				'nonce'   => array(
					'reset' => wp_create_nonce('customizer-reset'),
				)
			));
		}

		public static function header_elements()
		{
			$header_elements = array(
				'site-logo'             => __('Site Logo', 'togo'),
				'main-menu'             => __('Main Menu', 'togo'),
				'landing-menu'             => __('Landing Menu', 'togo'),
				'canvas-menu'           => __('Canvas Menu', 'togo'),
				'canvas-menu-02'        => __('Canvas Menu 02', 'togo'),
				'canvas-mb-menu'        => __('Canvas Mobile Menu', 'togo'),
				'header-device'         => __('Device', 'togo'),
				'header-lang'           => __('Languages', 'togo'),
				'header-contact'        => __('Contact', 'togo'),
				'header-search-icon'    => __('Search Icon', 'togo'),
				'header-search-input'   => __('Search Input', 'togo'),
				'header-account'      	=> __('Account', 'togo'),
				'header-cart'      		=> __('Cart', 'togo'),
				'header-button-01'      => __('Button 01', 'togo'),
				'header-custom-html-01' => __('Custom HTML 01', 'togo'),
				'header-custom-html-02' => __('Custom HTML 02', 'togo'),
			);
			// Add Hooked Header Elements
			$header_elements = apply_filters('togo_header_elements', $header_elements);

			return $header_elements;
		}

		function togo_add_custom_font($fonts)
		{
			$fonts['Cormorant Garamond'] = [
				'label'    => 'Cormorant Garamond',
				'variants' => [
					100,
					200,
					300,
					'regular',
					500,
					600,
					700,
					800,
					900,
				],
				'stack' => 'Cormorant Garamond',
			];

			return $fonts;
		}

		function togo_add_custom_font_css()
		{
			$typo_fields = \Togo\Kirki::get_typography_fields_id();

			if (!is_array($typo_fields) || empty($typo_fields)) {
				return;
			}

			$fonts = [];

			foreach ($typo_fields as $field) {
				$value = Togo\Helper::setting($field);

				if (is_array($value) && !empty($value['font-family']) && 'inherit' !== $value['font-family']) {
					$fonts[] = $value['font-family'];
				}
			}

			if (!empty($fonts)) {
				$fonts = array_unique($fonts);

				foreach ($fonts as $font) {
					if (strpos($font, 'Cormorant Garamond') !== false) {
						wp_enqueue_style('togo-font-cormorant', TOGO_THEME_URI . '/assets/fonts/CormorantGaramond/stylesheet.css', null, null);
					} else {
						do_action('togo_enqueue_custom_font', $font); // hook to custom do enqueue fonts
					}
				}
			}
		}

		/**
		 * Get list footer
		 */
		public static function togo_get_footers($default_option = true)
		{
			$footers = Togo\Theme::get_list_templates(false, 'togo_footer');
			if ($default_option === true) {
				$footers = Togo\Theme::get_list_templates(true, 'togo_footer');
			}

			return $footers;
		}

		function customize_register($wp_customize)
		{
			$this->wp_customize = $wp_customize;
			$this->togo_customizer_create_path();
		}

		function ajax_customizer_import()
		{
			$options = unserialize($this->togo_get_contents($_FILES['file']['tmp_name']));
			if (is_array($options)) {
				foreach ($options as $key => $val) {
					set_theme_mod($key, $val);
				}
			}
			echo json_encode(array('options' => $options, 'status' => 1, 'message' => __('Import is successful!', 'togo')));
			wp_die();
		}

		function ajax_customizer_reset()
		{
			if (!$this->wp_customize->is_preview()) {
				wp_send_json_error('not_preview');
			}

			if (!check_ajax_referer('customizer-reset', 'nonce', false)) {
				wp_send_json_error('invalid_nonce');
			}

			$settings = $this->wp_customize->settings();

			// remove theme_mod settings registered in customizer
			foreach ($settings as $setting) {
				if ('theme_mod' == $setting->type) {
					remove_theme_mod($setting->id);
				}
			}

			wp_send_json_success();
		}

		function togo_get_contents($path)
		{
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
			global $wp_filesystem;

			$get_content = '';
			if (function_exists('realpath')) {
				$filepath = realpath($path);
			}
			if (!$filepath || !@is_file($filepath)) {
				return '';
			}

			return $wp_filesystem->get_contents($filepath);
		}

		function togo_header_builder()
		{
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
			global $wp_filesystem;

			$css        = stripslashes($_POST['css']);
			$header     = Togo\Helper::togo_clean(wp_unslash($_POST['header']));
			$header_obj = $_POST['header_obj'];

			update_option($header, $header_obj['header']);

			$this->togo_customizer_create_file($header, 'css', $css);

			return true;

			wp_die();
		}

		function togo_header_delete_builder()
		{

			$header = Togo\Helper::togo_clean(wp_unslash($_POST['header']));

			delete_option($header);

			return true;

			wp_die();
		}

		function togo_topbar_builder()
		{
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
			global $wp_filesystem;

			$css        = stripslashes($_POST['css']);
			$topbar     = Togo\Helper::togo_clean(wp_unslash($_POST['topbar']));
			$topbar_obj = $_POST['topbar_obj'];

			update_option($topbar, $topbar_obj['topbar']);

			$this->togo_customizer_create_file($topbar, 'css', $css);

			return true;

			wp_die();
		}

		function togo_topbar_delete_builder()
		{

			$topbar = Togo\Helper::togo_clean(wp_unslash($_POST['topbar']));

			delete_option($topbar);

			return true;

			wp_die();
		}

		/**
		 * Create Path
		 */
		function togo_customizer_create_path()
		{
			$upload_dir = wp_upload_dir();
			$logger_dir = $upload_dir['basedir'] . '/togo/header';

			if (!file_exists($logger_dir)) {
				wp_mkdir_p($logger_dir);
			}
		}

		function togo_customizer_create_file($name, $path, $css)
		{
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
			global $wp_filesystem;

			$upload_dir = wp_upload_dir();
			$logger_dir = $upload_dir['basedir'] . '/togo/header';

			$name = $name . '.' . $path;
			$wp_filesystem->put_contents(trailingslashit($logger_dir) . $name, $css);
		}
	}

	Togo_Customize::instance()->initialize();
}
