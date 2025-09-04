<?php

/**
 * Wishlist widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Wishlist Widget.
 *
 * @package Togo_Elementor
 */
class Togo_My_Wishlist_Widget extends Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-my-wishlist';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('My Wishlist', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-heart';
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
    }

    /**
     * Add the content section for the widget.
     *
     * @return void
     */
    protected function add_content_section()
    {
        /**
         * Content section.
         *
         * @var array The content section.
         */
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Columns responsive control
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => __('1', 'togo-framework'),
                    '2' => __('2', 'togo-framework'),
                    '3' => __('3', 'togo-framework'),
                    '4' => __('4', 'togo-framework'),
                ],
                'default' => '4',
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
        \Togo_Framework\Helper::togo_get_template('my-account/wishlist/wishlist.php', $settings);
    }
}
