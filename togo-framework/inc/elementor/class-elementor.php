<?php

namespace Togo_Framework\Elementor;

defined('ABSPATH') || exit;

/**
 * Main Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class Setup
{

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	private static $_instance = null;

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * The real constructor to initialize
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function __construct()
	{
		// Check if Elementor installed and activated.
		if (!defined('ELEMENTOR_VERSION')) {
			add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));

			return;
		}

		// Check for required Elementor version.
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', array($this, 'admin_notice_minimum_elementor_version'));

			return;
		}

		// Check for required PHP version.
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));

			return;
		}

		add_action('elementor/theme/register_locations', [$this, 'register_theme_locations']);

		add_action('after_switch_theme', [$this, 'add_cpt_support']);

		add_action('elementor/editor/after_enqueue_styles', array($this, 'elementor_styles'));

		// Add custom icon library
		add_filter('elementor/icons_manager/additional_tabs', array($this, 'add_svg_library'));

		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/class-widget-init.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/class-control-init.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/class-elementor-ajax.php';

		\Togo_Framework\Elementor\Widget_Init::instance();
		\Togo_Framework\Elementor\Control_Init::instance();

		add_action('elementor/element/container/section_shape_divider/after_section_end', array($this, 'add_custom_shape_divider_controls'), 10, 2);
		add_action('elementor/frontend/container/before_render', array($this, 'add_custom_shape_divider_before_render'), 5, 1);
		add_action('elementor/frontend/container/after_render', array($this, 'add_custom_shape_divider_after_render'), 15, 1);
		\Togo_Framework\Elementor\Widget_Ajax::instance();
	}

	public static function add_svg_library($icon_sets)
	{
		$icon_sets['svg-icons'] = [
			'name' => 'svg-icons',
			'label' => __('Togo Premium Icons', 'togo-framework'),
			'url' => '',
			'prefix' => '',
			'displayPrefix' => 'togo-svg',
			'labelIcon' => 'eicon-filter',
			'ver' => '1.0',
			'fetchJson' => TOGO_FRAMEWORK_DIR . '/inc/elementor/assets/libs/svg-icons/icons.json',
		];

		return $icon_sets;
	}

	function elementor_styles()
	{
		wp_enqueue_style('togo-elementor-editor', TOGO_FRAMEWORK_DIR . '/inc/elementor/assets/css/editor.css');
	}

	/**
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager
	 *
	 * Register theme locations
	 */
	public function register_theme_locations($elementor_theme_manager)
	{
		$elementor_theme_manager->register_location('header');
		$elementor_theme_manager->register_location('footer');
		$elementor_theme_manager->register_location('single');
		$elementor_theme_manager->register_location('archive');
	}

	/**
	 * Enable default Elementor Editor for custom post type.
	 */
	public function add_cpt_support()
	{
		//if exists, assign to $cpt_support var.
		$cpt_support = get_option('elementor_cpt_support');

		//check if option DOESN'T exist in db.
		if (!$cpt_support) {
			// Create array of our default supported post types.
			$cpt_support = [
				'page',
				'post',
				'togo_mega_menu',
				'togo_footer',
			];
			update_option('elementor_cpt_support', $cpt_support);
		} else {
			if (!in_array('togo_mega_menu', $cpt_support)) {
				$cpt_support[] = 'togo_mega_menu';
			}

			update_option('elementor_cpt_support', $cpt_support);
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'togo'),
			'<strong>' . esc_html__('Togo', 'togo') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'togo') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'togo'),
			'<strong>' . esc_html__('Togo', 'togo') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'togo') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version()
	{

		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'togo'),
			'<strong>' . esc_html__('Togo', 'togo') . '</strong>',
			'<strong>' . esc_html__('PHP', 'togo') . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	public static function display_terms_hierarchy($parent_id, $terms_hierarchy)
	{
		if (isset($terms_hierarchy[$parent_id])) {
			echo '<ul>';
			foreach ($terms_hierarchy[$parent_id] as $term) {
				echo '<li><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
				self::display_terms_hierarchy($term->term_id, $terms_hierarchy); // Recursive call for children
				echo '</li>';
			}
			echo '</ul>';
		}
	}

	public static function add_custom_shape_divider_controls($element, $args) {

		$element->start_controls_section(
			'custom_shape_divider_lr',
			[
				'label' => __('Shape Divider Curve Left/Right', 'your-plugin'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_responsive_control(
			'shape_left_enable',
			[
				'label' => __('Enable Left Curve', 'your-plugin'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$element->add_responsive_control(
			'shape_right_enable',
			[
				'label' => __('Enable Right Curve', 'your-plugin'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$element->add_control(
			'transparent_enable',
			[
				'label' => __('Enable transparent color', 'your-plugin'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$element->add_control(
			'weight',
			[
				'label' => __('Weight', 'togo-framework'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['unit'],
				'range' => [
					'unit' => [
						'min' => 1,
						'max' => 9,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'unit',
					'size' => 1,
				],
			]
		);

		$element->end_controls_section();

	}

	public static function add_custom_shape_divider_before_render($element) {
		$settings = $element->get_settings_for_display();
		
		$has_left_shape = !empty($settings['shape_left_enable']) && $settings['shape_left_enable'] === 'yes';
		$has_right_shape = !empty($settings['shape_right_enable']) && $settings['shape_right_enable'] === 'yes';
		
		if ($has_left_shape || $has_right_shape) {
			ob_start();
		}
	}

	public static function add_custom_shape_divider_after_render($element) {
		$settings = $element->get_settings_for_display();
		
		$has_left_shape = !empty($settings['shape_left_enable']) && $settings['shape_left_enable'] === 'yes';
		$has_right_shape = !empty($settings['shape_right_enable']) && $settings['shape_right_enable'] === 'yes';
		$transparent_enable = !empty($settings['transparent_enable']) && $settings['transparent_enable'] === 'yes';

		$has_left_shape_tablet = !empty($settings['shape_left_enable_tablet']) && $settings['shape_left_enable_tablet'] === 'yes';
		$has_left_shape_mobile = !empty($settings['shape_left_enable_mobile']) && $settings['shape_left_enable_mobile'] === 'yes';

		$has_right_shape_tablet = !empty($settings['shape_right_enable_tablet']) && $settings['shape_right_enable_tablet'] === 'yes';
		$has_right_shape_mobile = !empty($settings['shape_right_enable_mobile']) && $settings['shape_right_enable_mobile'] === 'yes';


		
		if ($has_left_shape || $has_right_shape) {
			$container_content = ob_get_clean();
			
			$shapes_html = '';
			$weight_size = isset($settings['weight']['size']) ? (int) $settings['weight']['size'] : 1;
			$right_weight = 9;
			$left_weight = 1;
			if ($has_left_shape) {
				$left_weight = $weight_size;
			} else {
				$right_weight = ($weight_size >= 1 && $weight_size <= 9) ? (10 - $weight_size) : 9;
			}
			
			if ($has_left_shape) {
				if ($transparent_enable) {
					$shapes_html .= '<div class="elementor-shape elementor-shape-left" style="height: 0;">';
					$shapes_html .= '<svg width="0" height="0"><defs><clipPath id="'.$element->get_id().'-clipInward" clipPathUnits="objectBoundingBox"><path d="M0,0 H1 V1 H0 Q0.'.$left_weight.',0.5 0,0 Z"></path></clipPath></defs></svg>';
					$shapes_html .= '</div>';
					$clip_path = $element->get_id() ? $element->get_id().'-clipInward' : '';
				} else {
					$shapes_html .= '<div class="elementor-shape elementor-shape-left" style="position: absolute; left: 0; top: 0; height: 100%; width: 100px;">';
					$shapes_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 1000" preserveAspectRatio="none" style="height: 100%; width: 100%;">';
					$shapes_html .= '<path class="elementor-shape-fill" fill="#ffffff" d="M0,0 C40,250 40,750 0,1000 L0,0 Z" />';
					$shapes_html .= '</svg>';
					$shapes_html .= '</div>';
					$clip_path = '';
				}
			}
			
			if ($has_right_shape) {
				if ($transparent_enable) {
					$shapes_html .= '<div class="elementor-shape elementor-shape-right" style="height: 0;">';
					$shapes_html .= '<svg width="0" height="0"><defs><clipPath id="'.$element->get_id().'-clipCurve" clipPathUnits="objectBoundingBox"><path d="M0,0 H1 Q0.'.$right_weight.',0.5 1,1 H0 Z" /></clipPath></defs></svg>';
					$shapes_html .= '</div>';
					$clip_path = $element->get_id() ? $element->get_id().'-clipCurve' : '';
				} else {
					$shapes_html .= '<div class="elementor-shape elementor-shape-right" style="position: absolute; right: 0; left: auto; top: 0; height: 100%; width: 100px;">';
					$shapes_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 1000" preserveAspectRatio="none" style="height: 100%; width: 100%;">';
					$shapes_html .= '<path class="elementor-shape-fill" fill="#ffffff" d="M100,0 C60,250 60,750 100,1000 L100,0 Z" />';
					$shapes_html .= '</svg>';
					$shapes_html .= '</div>';
					$clip_path = '';
				}
			}
			
			$pattern = '/(<\/div>\s*$)/';
			$replacement = $shapes_html . '$1';
			$container_content = preg_replace($pattern, $replacement, $container_content, 1);
			
			echo $container_content;

			$shape_css = '';
			$element_id = $element->get_id();

			$responsive_left = [
				'desktop' => [
					'enabled' => $has_left_shape,
					'media' => '(min-width: 1025px)',
				],
				'tablet' => [
					'enabled' => $has_left_shape_tablet,
					'media' => '(min-width: 768px) and (max-width: 1024px)',
				],
				'mobile' => [
					'enabled' => $has_left_shape_mobile,
					'media' => '(max-width: 767px)',
				],
			];

			$responsive_right = [
				'desktop' => [
					'enabled' => $has_right_shape,
					'media' => '(min-width: 1025px)',
				],
				'tablet' => [
					'enabled' => $has_right_shape_tablet,
					'media' => '(min-width: 768px) and (max-width: 1024px)',
				],
				'mobile' => [
					'enabled' => $has_right_shape_mobile,
					'media' => '(max-width: 767px)',
				],
			];

			if ($transparent_enable) {
				foreach ($responsive_left as $ldevice => $lconfig) {
					$display = $lconfig['enabled'] ? $clip_path : 'none';
					$shape_css .= "@media {$lconfig['media']} {
						.elementor-element-" . $element->get_id() . "{
							clip-path: url(#" . $display . ");
						}
					}";
				}
			} else {
				foreach ($responsive_left as $ldevice => $lconfig) {
					$display = $lconfig['enabled'] ? 'block' : 'none';
					$shape_css .= "@media {$lconfig['media']} {
						.elementor-element-{$element_id} .elementor-shape-left {
							display: {$display};
						}
					}";
				}
				foreach ($responsive_right as $device => $config) {
					$display = $config['enabled'] ? 'block' : 'none';
					$shape_css .= "@media {$config['media']} {
						.elementor-element-{$element_id} .elementor-shape-right {
							display: {$display};
						}
					}";
				}
			}
			echo '<style>' . $shape_css . '</style>';
		}
	}

}
