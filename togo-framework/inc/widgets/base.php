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
class Widgets
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

	public function __construct()
	{
		add_action('widgets_init', array($this, 'register_widget'), 1);
		$this->includes();
		spl_autoload_register(array($this, 'autoload'));
	}

	public function autoload($class_name)
	{
		$class = preg_replace('/^Togo_Widget_/', '', $class_name);
		if ($class != $class_name) {
			$class = str_replace('_', '-', $class);
			$class = strtolower($class);
			include_once(TOGO_FRAMEWORK_PATH . '/inc/widgets/includes/' . $class . '.php');
		}
	}

	private function includes()
	{
		include_once(TOGO_FRAMEWORK_PATH . '/inc/widgets/widget-config.php');
	}

	public function register_widget()
	{
		register_widget('Togo_Widget_Popular_Posts');
		if (class_exists('WooCommerce')) {
			register_widget('Togo_Widget_Products_Filter');
		}
	}
}
