<?php

/**
 * Elementor widget for displaying the site logo.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Logo_Widget
 *
 * Elementor widget for displaying the site logo.
 *
 * @since 1.0.0
 */
class Togo_Logo_Widget extends Base
{

    /**
     * Get the widget name.
     *
     * @since 1.0.0
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-logo';
    }

    /**
     * Get the widget title.
     *
     * @since 1.0.0
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Logo', 'togo-framework');
    }

    /**
     * Get the widget icon.
     *
     * @since 1.0.0
     *
     * @return string The widget icon.
     */
    public function get_icon_part()
    {
        return 'eicon-logo';
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
    }

    /**
     * Add the content section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_section()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('logo_style', [
            'label'   => esc_html__('Logo Style', 'togo'),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'light' => esc_html__('Light', 'togo'),
                'dark'  => esc_html__('Dark', 'togo'),
            ],
            'default' => 'light',
        ]);

        $this->add_responsive_control('max_width', [
            'label'      => esc_html__('Max Width', 'togo'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => [
                'unit' => 'px',
            ],
            'size_units' => ['px', '%'],
            'range'      => [
                '%'  => [
                    'min' => 1,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            'default'    => [
                'unit' => 'px',
                'size' => 80,
            ],
            'selectors'  => [
                '{{WRAPPER}} .site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * Render the widget output.
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $logo_style = $settings['logo_style'];
        echo \Togo\Templates::site_logo($logo_style);
    }
}
