<?php

/**
 * Theme Options
 *
 */
if (!class_exists('Uxper_Theme_Options')) {

	class Uxper_Theme_Options
	{

		public static $instance;

		/**
		 * Init Uxper_Theme_Options
		 *
		 * @since 1.0
		 */
		public static function init()
		{
			if (self::$instance == NULL) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor Uxper_Theme_Options
		 *
		 * @since 1.0
		 */
		public function __construct()
		{
			add_action('admin_menu', array($this, 'theme_options_menu'));
			add_filter('uxper_theme_options_get_value', array($this, 'option_get_value'), 10, 2);
			add_filter('uxper_theme_options_get_clone_count', array($this, 'option_get_clone_count'), 10, 2);
			add_filter('uxper_theme_options_get_panel_count', array($this, 'option_get_panel_count'), 10, 2);
		}

		public function admin_enqueue_styles()
		{
			if (is_rtl()) {
				wp_enqueue_style(TOGO_METABOX_PREFIX . 'field_css', TOGO_FRAMEWORK_DIR . 'inc/meta-box/assets/css/field-css-rtl.css', array(), null);
			} else {
				wp_enqueue_style(TOGO_METABOX_PREFIX . 'field_css', TOGO_FRAMEWORK_DIR . 'inc/meta-box/assets/css/field-css.css', array(), null);
			}
		}

		/**
		 * Enqueue script for options page
		 *
		 * @since 1.0
		 */
		public function admin_enqueue_scripts()
		{
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-autocomplete');

			wp_enqueue_script(TOGO_METABOX_PREFIX . 'media', TOGO_FRAMEWORK_DIR . 'inc/meta-box/assets/js/media.js', array(), null, true);
			wp_enqueue_script(TOGO_METABOX_PREFIX . 'field_config', TOGO_FRAMEWORK_DIR . 'inc/meta-box/assets/js/field-config.js', array(), null, true);
		}

		/**
		 * Add menu Theme Options
		 *
		 * @since 1.0
		 */
		public function theme_options_menu()
		{
			/**
			 * Check enable theme options
			 *
			 * Default: true
			 */

			$configs = &uxper_get_options_config();

			foreach ($configs as $page => $config) {
				if (isset($config['parent_slug']) && !empty($config['parent_slug'])) {
					$parent_slug = $config['parent_slug'];
				} else {
					$parent_slug = 'themes.php';
				}
				add_submenu_page(
					$parent_slug,
					$config['page_title'],
					$config['menu_title'],
					$config['permission'],
					$page,
					array($this, 'binder_page')
				);
			}
		}

		/**
		 * Binder theme options page
		 *
		 * @since 1.0
		 */
		public function binder_page()
		{
			/**
			 * Set Field Type is theme_options
			 */
			uxper_set_config_type('theme_options');

			/**
			 * Save Theme Options
			 */
			$this->save_theme_options();

			settings_errors('gf-options');

			$page = uxper_clean(wp_unslash($_GET['page']));
			$config = &uxper_get_options_config($page);
			$option_name = $config['option_name'];

			uxper_set_config_layout(isset($config['layout']) ? $config['layout'] : '');

			wp_enqueue_media();
			add_action('admin_footer', array($this, 'admin_enqueue_styles'));
			// Enqueue common styles and scripts
			add_action('admin_footer', array($this, 'admin_enqueue_scripts'), 15);

			/**
			 * Get Options Value
			 */

			$GLOBALS['uxper_options'] = get_option($option_name);

			/**
			 * Action: uxper/build_page/after_get_option
			 */
			do_action('uxper/build_page/after_get_option');

			if (!is_array($GLOBALS['uxper_options'])) {
				$GLOBALS['uxper_options'] = array();
			}

			$list_section = array();
			if (isset($config['section'])) {
				foreach ($config['section'] as $tab) {
					$list_section[] = array(
						'id'    => $tab['id'],
						'title' => $tab['title'],
						'icon'  => isset($tab['icon']) ? $tab['icon'] : 'dashicons-admin-generic',
					);
				}
			}

			uxper_get_template('templates/theme-option-start', array(
				'list_section' => $list_section,
				'option_name'  => $config['option_name'],
				'page'         => $page,
				'page_title'   => $config['page_title']
			));

			if (!empty($config)) {
				if (isset($config['section'])) {
					$tab_index = 0;
					foreach ($config['section'] as $tabs) {
						echo sprintf('<div id="section_%s" class="uxper-section-container">', $tabs['id']);
						if (isset($tabs['fields'])) {
							$this->theme_options_display_fields($tabs['fields']);
						}
						echo '</div>';
						$tab_index++;
					}
				} else {
					$this->theme_options_display_fields($config['fields']);
				}
			}
			uxper_get_template('templates/theme-option-end', array('option_name' => $config['option_name']));
		}

		/**
		 * Display Listing Fields
		 *
		 * @since 1.0
		 */
		public function theme_options_display_fields(&$fields, $parent_type = '')
		{
			foreach ($fields as $field) {
				$this->theme_options_display_field($field, $parent_type);
			}
		}

		/**
		 * Display Field
		 *
		 * @since 1.0
		 */
		public function theme_options_display_field(&$field, $parent_type = '')
		{
			if (!isset($field['type'])) {
				return;
			}

			$class_field = uxper_get_field_class_name($field['type']);
			$meta = new $class_field($field, $parent_type, 12);
			$meta->render();
		}

		/**
		 * Save theme options
		 *
		 * @since 1.0
		 */
		public function save_theme_options()
		{
			if (empty($_POST)) {
				return;
			}
			if (!isset($_POST['uxper_save_option'])) {
				return;
			}

			try {
				$page = $_POST['_current_page'];
				$configs = &uxper_get_options_config($page);
				$options_name = $configs['option_name'];

				if (!wp_verify_nonce($_POST['_wpnonce'], $options_name)) {
					return;
				}

				$config_keys = uxper_get_option_config_keys($configs);
				$config_options = array();

				$font_using = array();
				foreach ($config_keys as $meta_id => $field_value) {
					$is_clone = $field_value['clone'];

					$meta_value = isset($_POST[$meta_id]) ? $_POST[$meta_id] : ($is_clone ? array() : '');
					if ($is_clone && is_array($meta_value)) {
						$max = false;
						foreach ($meta_value as $index_key => &$value) {
							if (!is_int($index_key)) {
								$max = false;
								break;
							}
							$max = $index_key;
						}

						if (($max !== false) && (count($meta_value) - 1 < $max) && ($max < 200)) {
							$newKeys = array_fill_keys(range(0, $max), array());
							$meta_value += $newKeys;
							ksort($meta_value);
						}
					}

					$config_options[$meta_id] = wp_unslash($meta_value);

					if ($field_value['type'] === 'font') {
						$font_using[$meta_id] = wp_unslash($meta_value);
					}
				}

				$fonts_option = get_option(UXPER_OPTIONS_FONT_USING);
				$fonts_option[$options_name] = $font_using;
				update_option(UXPER_OPTIONS_FONT_USING, $fonts_option);

				/**
				 * Call action before save options
				 */
				do_action('uxper_before_save_options', $config_options);

				/**
				 * Update options
				 */
				update_option($options_name, $config_options);

				/**
				 * Call action after save options
				 */
				do_action('uxper_after_save_options', $config_options);

				/**
				 * Set options Config
				 */
				$GLOBALS['uxper_option_config'] = apply_filters('uxper_option_config', array());

				$message = esc_html__('Settings saved', 'uxper-booking');
				add_settings_error('gf-options', 'update-options', $message, 'updated');
			} catch (Exception $e) {
				$message = esc_html__('There has been an error when update options', 'uxper-booking');
				add_settings_error('gf-options', 'update-options', $message);
			}
		}

		/**
		 * Get Options Field Value
		 *
		 * @param $value
		 * @param $field
		 * @return array|string
		 */
		public function option_get_value($value, $field)
		{
			$is_single = !($field->is_clone() || ($field->parent_type === 'repeater'));
			if (!isset($field->params['id'])) {
				return $is_single ? '' : array();
			}

			$default_value = isset($field->params['default']) ? $field->params['default'] : '';

			/**
			 * If field in panel
			 */
			if (!empty($field->panel_id)) {
				$meta_key = $field->panel_id;
				$current_key = $field->params['id'];

				if ($field->panel_default !== array(array())) {

					if ($field->is_clone() || ($field->parent_type === 'repeater')) {
						$default_value =
							isset($field->panel_default[$field->panel_index])
							&& isset($field->panel_default[$field->panel_index][$current_key])
							&& isset($field->panel_default[$field->panel_index][$current_key][$field->index])
							? $field->panel_default[$field->panel_index][$current_key][$field->index]
							: $default_value;
					} else {
						$default_value =
							isset($field->panel_default[$field->panel_index])
							&& isset($field->panel_default[$field->panel_index][$current_key])
							? $field->panel_default[$field->panel_index][$current_key]
							: $default_value;
					}
				}
				if (isset($GLOBALS['uxper_options'][$meta_key])) {
					$value = $GLOBALS['uxper_options'][$meta_key];
					if ($field->is_clone() || ($field->parent_type === 'repeater')) {
						$value = isset($value[$field->panel_index]) && isset($value[$field->panel_index][$current_key]) && isset($value[$field->panel_index][$current_key][$field->index])
							? $value[$field->panel_index][$current_key][$field->index]
							: $default_value;
					} else {
						$value = isset($value[$field->panel_index]) && isset($value[$field->panel_index][$current_key])
							? $value[$field->panel_index][$current_key]
							: $default_value;
					}
				} else {
					$value = $default_value;
				}

				return $value;
			}

			/**
			 * If field not in panel
			 */
			$meta_key = $field->params['id'];

			if (isset($GLOBALS['uxper_options'][$meta_key])) {
				$value = $GLOBALS['uxper_options'][$meta_key];
				if ($field->is_clone() || ($field->parent_type === 'repeater')) {
					$value = isset($value[$field->index]) ? $value[$field->index] : '';
				}
			} else {
				$value = $default_value;
			}

			return $value;
		}

		/**
		 * Get Clone Field Count
		 *
		 * @param $value
		 * @param $field
		 * @return int
		 */
		public function option_get_clone_count($value, $field)
		{
			$current_key = $field->params['id'];
			if (!empty($field->panel_id)) {
				$meta_key = $field->panel_id;
				$value = isset($GLOBALS['uxper_options'][$meta_key]) ? $GLOBALS['uxper_options'][$meta_key] : array();
				$value = isset($value[$field->panel_index]) ? $value[$field->panel_index] : array();
				$value = isset($value[$current_key]) ? $value[$current_key] : array();
			} else {
				$value = isset($GLOBALS['uxper_options'][$current_key]) ? $GLOBALS['uxper_options'][$current_key] : array();
			}
			return count($value);
		}

		/**
		 * Get Panel Count
		 *
		 * @param $value
		 * @param $field
		 * @return int
		 */
		public function option_get_panel_count($value, $field)
		{
			$id = $field->params['id'];
			if (isset($GLOBALS['uxper_options'][$id])) {
				$value = isset($GLOBALS['uxper_options'][$id]) ? $GLOBALS['uxper_options'][$id] : array();
			} else {
				$value = isset($field->params['default']) ? $field->params['default'] : array();
			}

			return is_array($value) ? count($value) : 0;
		}
	}

	/**
	 * Instantiate the Meta boxes
	 */
	Uxper_Theme_Options::init();
}
