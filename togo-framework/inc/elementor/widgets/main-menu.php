<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Main_Menu_Widget
 *
 * Elementor widget for displaying the main menu.
 *
 * @since 1.0.0
 */
class Togo_Main_Menu_Widget extends Base
{
    /**
     * Get the widget name.
     *
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'togo-main-menu';
    }

    /**
     * Get the widget title.
     *
     * Retrieve the widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Main Menu', 'togo-framework');
    }

    /**
     * Get the widget icon part.
     *
     * Retrieve the icon part for the widget.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Icon part.
     */
    public function get_icon_part()
    {
        return 'eicon-nav-menu';
    }

    /**
     * Register the controls for the widget.
     *
     * This method adds the content section and content style section to the widget.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
    }

    /**
     * Add the content section controls.
     *
     * This method adds the content section to the widget.
     *
     * @since 1.0.0
     * @access protected
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

        // Get list menu
        $this->add_control(
            'menu',
            [
                'label' => esc_html__('Menu', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => \Togo\Helper::get_all_menus(),
                'default' => 'main_menu',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content style section controls.
     *
     * This method adds the content style section to the widget.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .desktop-menu .menu > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__('Color Hover', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .desktop-menu .menu > li > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_padding',
            [
                'label' => __('Item Padding', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .site-menu .menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Writes the HTML for the current frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo '<div class="main-menu site-menu desktop-menu" data-id="main-menu">';
        $args = array();
        $theme_location = $settings['menu'] ? $settings['menu'] : 'main_menu';

        $defaults = array(
            'theme_location' => $theme_location,
            'container'      => 'ul',
            'menu_class'     => 'menu sm sm-simple',
            'extra_class'    => '',
        );

        $args = wp_parse_args($args, $defaults);

        if (has_nav_menu('main_menu') && class_exists('Togo_Walker_Nav_Menu')) {
            $args['walker'] = new \Togo_Walker_Nav_Menu;
        }

        if (has_nav_menu('main_menu')) {
            wp_nav_menu($args);
        }
        echo '</div>';
    }
}
