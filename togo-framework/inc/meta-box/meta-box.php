<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Razzi Addons init
 *
 * @since 1.0.0
 */
class Metabox
{
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance()
	{
		if (! isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Constructor SP_Loader
	 * *******************************************************
	 */
	public function __construct()
	{
		$this->create_roles();
		$this->define_constants();
		$this->includes();

		/*
			 * Register auto loader for fields type
			 */
		spl_autoload_register(array($this, 'fields_autoload'));
		add_action('init', array($this, 'theme_init'));
	}

	public function theme_init()
	{
		if (!defined('UXPER_OPTIONS_FONT_USING')) {
			define('UXPER_OPTIONS_FONT_USING', 'uxper_font_using');
		}
	}

	/**
	 * Define constant using in BASE
	 * *******************************************************
	 */
	private function define_constants()
	{
		if (!defined('TOGO_METABOX_PREFIX')) {
			define('TOGO_METABOX_PREFIX', 'togo_metabox_');
		}
	}

	/**
	 * Includes library for plugin
	 * *******************************************************
	 */
	private function includes()
	{
		/*
			 * Function
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/inc/functions.php';

		/*
			 * Define post type
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/controls/post-type.php';

		/*
			 * Define taxonomy for post type
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/controls/taxonomy.php';

		/*
			 * Meta box for post type Attribute
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/controls/meta-box.php';

		/*
			 * Define term meta custom config
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/controls/term-meta.php';

		/*
			 * Define theme options
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/controls/theme-options.php';

		/*
			 * Required Field abstract class
			 */
		require_once TOGO_FRAMEWORK_PATH . 'inc/meta-box/fields/field.php';
	}

	public function create_roles()
	{
		global $wp_roles;

		if (! class_exists('WP_Roles')) {
			return;
		}

		if (! isset($wp_roles)) {
			$wp_roles = new \WP_Roles();
		}

		$capabilities = $this->get_capabilities();

		foreach ($capabilities as $cap_group) {
			foreach ($cap_group as $cap) {
				$wp_roles->add_cap('administrator', $cap);
			}
		}
	}

	public function get_capabilities()
	{
		$capabilities = array();

		$capability_types = array('room', 'booking');

		foreach ($capability_types as $capability_type) {

			$capabilities[$capability_type] = array(
				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms"
			);
		}

		return $capabilities;
	}

	/**
	 * Auto load fields
	 * *******************************************************
	 */
	public function fields_autoload($class_name)
	{
		$class = preg_replace('/^Uxper_Field_/', '', $class_name);
		if ($class != $class_name) {
			$class = strtolower($class);
			include_once(TOGO_FRAMEWORK_PATH . "inc/meta-box/fields/{$class}/{$class}.class.php");
		}
	}
}
