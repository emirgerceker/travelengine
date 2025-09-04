<?php

namespace Togo_Framework\Elementor\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Elementor tooltip control.
 *
 * A base control for creating tooltip control.
 *
 * @since 1.0.0
 */
class Group_Control_Tooltip extends Group_Control_Base
{

	protected static $fields;

	public static function get_type()
	{
		return 'tooltip';
	}

	protected function init_fields()
	{
		$fields = [];

		$fields['skin'] = [
			'label'   => esc_html__('Tooltip Skin', 'togo'),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''        => esc_html__('Black', 'togo'),
				'white'   => esc_html__('White', 'togo'),
				'primary' => esc_html__('Primary', 'togo'),
			],
			'default' => '',
		];

		$fields['position'] = [
			'label'   => esc_html__('Tooltip Position', 'togo'),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'top'          => esc_html__('Top', 'togo'),
				'right'        => esc_html__('Right', 'togo'),
				'bottom'       => esc_html__('Bottom', 'togo'),
				'left'         => esc_html__('Left', 'togo'),
				'top-left'     => esc_html__('Top Left', 'togo'),
				'top-right'    => esc_html__('Top Right', 'togo'),
				'bottom-left'  => esc_html__('Bottom Left', 'togo'),
				'bottom-right' => esc_html__('Bottom Right', 'togo'),
			],
			'default' => 'top',
		];

		return $fields;
	}

	protected function get_default_options()
	{
		return [
			'popover' => [
				'starter_title' => _x('Tooltip', 'Tooltip Control', 'togo'),
				'starter_name'  => 'enable',
				'starter_value' => 'yes',
				'settings'      => [
					'render_type' => 'template',
				],
			],
		];
	}
}
