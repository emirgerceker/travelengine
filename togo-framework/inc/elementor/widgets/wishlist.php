<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Togo_Wishlist_Widget extends Base
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
        return 'togo-wishlist';
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
        return __('Wishlist', 'togo-framework');
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
        return 'eicon-heart-o';
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

        $this->add_control(
            'wishlist_url',
            [
                'label' => __('Wishlist URL', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
                'dynamic' => [
                    'active' => true,
                ],
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

        echo '<div class="togo-wishlist">';

        $url = $settings['wishlist_url']['url'];
        $target = $settings['wishlist_url']['is_external'] ? $settings['wishlist_url']['target'] : '_self';

        if (!empty($url)) {
            echo '<a href="' . esc_url($url) . '" target="' . esc_attr($target) . '" >';
        }

        echo \Togo\Icon::get_svg('heart-01');

        if (!empty($url)) {
            echo '</a>';
        }

        echo '</div>';
    }
}
