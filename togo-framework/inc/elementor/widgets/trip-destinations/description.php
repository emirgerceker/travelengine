<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Trip_Destinations;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Description extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-td-description';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip Destinations - Description', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-text';
    }

    public function get_categories()
    {
        return ['trip-destinations'];
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
    }

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
            'hide_link_01',
            [
                'label' => esc_html__('Hide Link 01', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
                'selectors' => [
                    '{{WRAPPER}} .togo-td-description__links .togo-button:first-child' => '{{VALUE}}',
                ],
                'selectors_dictionary'    => [
                    'yes' => 'display: none;',
                    'no'  => 'display: flex;',
                ],
            ]
        );

        $this->add_control(
            'hide_link_02',
            [
                'label' => esc_html__('Hide Link 02', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
                'selectors' => [
                    '{{WRAPPER}} .togo-td-description__links .togo-button:last-child' => '{{VALUE}}',
                ],
                'selectors_dictionary'    => [
                    'yes' => 'display: none;',
                    'no'  => 'display: flex;',
                ],
            ]
        );

        $this->end_controls_section();
    }

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
            'padding',
            [
                'label' => __('Padding', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output.
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (!is_tax('togo_trip_destinations')) {
            return;
        }

        // Get the current taxonomy term
        $term = get_queried_object();

        // Get the term name
        $term_id = $term->term_id;
        $term_name = $term->name;

        $description = get_term_meta($term_id, 'togo_trip_destinations_description', true);
        $link_01 = get_term_meta($term_id, 'togo_trip_destinations_link_01', true);
        $link_02 = get_term_meta($term_id, 'togo_trip_destinations_link_02', true);

        if (empty($description) && empty($link_01) && empty($link_02)) {
            return;
        }

        echo '<div class="togo-td-description">';
        echo esc_html($description);
        echo '<div class="togo-td-description__links">';
        if (!empty($link_01)) {
            echo '<a class="togo-button line" href="' . esc_url($link_01) . '">' . esc_html__('Best time to visit', 'togo-framework') . \Togo\Icon::get_svg('external-link') . '</a>';
        }
        if (!empty($link_02)) {
            echo '<a class="togo-button line" href="' . esc_url($link_02) . '">' . esc_html__('Best thing to do', 'togo-framework') . \Togo\Icon::get_svg('external-link') . '</a>';
        }
        echo '</div>';
        echo '</div>';
    }
}
