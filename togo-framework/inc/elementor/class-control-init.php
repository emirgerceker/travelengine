<?php

namespace Togo_Framework\Elementor;

use Elementor\Element_Base;

defined('ABSPATH') || exit;

class Control_Init
{

	private static $_instance = null;

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct()
	{

		/**
		 * Register Controls.
		 */
		add_action('elementor/controls/controls_registered', array($this, 'init_controls'));

		/**
		 * Edit Controls.
		 */
		// Add custom Motion Effect - Entrance Animation.
		add_filter('elementor/controls/animations/additional_animations', [
			$this,
			'add_custom_entrance_animations',
		]);
	}

	public function add_custom_entrance_animations($animations)
	{
		$animations['By Togo'] = [
			'togo-fade-in-down'   => 'Togo - Fade In Down',
			'togo-fade-in-left'   => 'Togo - Fade In Left',
			'togo-fade-in-right'  => 'Togo - Fade In Right',
			'togo-fade-in-up'     => 'Togo - Fade In Up',
			'togo-jump'    	   => 'Togo - Jump',
			'togo-spin'    	   => 'Togo - Spin',
		];

		return $animations;
	}

	/**
	 * @param \Elementor\Controls_Manager $controls_manager
	 *
	 * Include controls files and register them
	 */
	public function init_controls($controls_manager)
	{
		// Include controls files.
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/controls/group-control-text-gradient.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/controls/group-control-text-stroke.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/controls/group-control-advanced-border.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/controls/group-control-button.php';
		require_once TOGO_FRAMEWORK_PATH . '/inc/elementor/controls/group-control-tooltip.php';

		// Group Control.
		$controls_manager->add_group_control(\Togo_Framework\Elementor\Controls\Group_Control_Text_Gradient::get_type(), new \Togo_Framework\Elementor\Controls\Group_Control_Text_Gradient());
		$controls_manager->add_group_control(\Togo_Framework\Elementor\Controls\Group_Control_Text_Stroke::get_type(), new \Togo_Framework\Elementor\Controls\Group_Control_Text_Stroke());
		$controls_manager->add_group_control(\Togo_Framework\Elementor\Controls\Group_Control_Advanced_Border::get_type(), new \Togo_Framework\Elementor\Controls\Group_Control_Advanced_Border());
		$controls_manager->add_group_control(\Togo_Framework\Elementor\Controls\Group_Control_Button::get_type(), new \Togo_Framework\Elementor\Controls\Group_Control_Button());
		$controls_manager->add_group_control(\Togo_Framework\Elementor\Controls\Group_Control_Tooltip::get_type(), new \Togo_Framework\Elementor\Controls\Group_Control_Tooltip());
	}
}
