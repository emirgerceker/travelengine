<?php

/**
 * Elementor widget for displaying destinations.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Destination_Widget
 *
 * Elementor widget for displaying destinations.
 *
 * @since 1.0.0
 */
class Togo_Destination_Widget extends Base
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
        return 'togo-destination';
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
        return __('Destination', 'togo-framework');
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
        return 'eicon-map-pin';
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
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

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'layout-01' => __('Layout 1', 'togo-framework'),
                    'layout-02' => __('Layout 2', 'togo-framework'),
                ],
                'default' => 'layout-01',
            ]
        );

        $this->add_control(
            'destination',
            [
                'label' => __('Destination', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_destinations', true),
                'default' => '',
            ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => __('Thumbnail', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'show_number_of_trips',
            [
                'label' => __('Show number of trips', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content style section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => __('Title', 'togo-framework'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-destination-meta .name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Hover Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-destination-meta .name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .togo-destination-meta .name',
            ]
        );

        $this->add_control(
            'tour_heading',
            [
                'label' => __('Tour', 'togo-framework'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'tour_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-destination-meta .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tour_typography',
                'selector' => '{{WRAPPER}} .togo-destination-meta .count',
            ]
        );

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
        $destination = $settings['destination'];
        $layout = $settings['layout'];

        \Togo_Framework\Helper::togo_get_template('loop/widgets/destination/' . $layout . '.php', array(
            'destination' => $destination,
            'settings' => $settings,
        ));
    }
}
