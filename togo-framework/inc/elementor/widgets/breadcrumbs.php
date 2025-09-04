<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Breadcrumb extends Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-breadcrumb';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Breadcrumbs', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-product-breadcrumbs';
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

        /**
         * Home text control.
         *
         * @var array The home text control.
         */
        $this->add_control(
            'home_text',
            [
                'label' => __('Home Text', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Home', 'togo-framework'),
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
        // Settings
        $separator = '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14" fill="none"><path d="M1 13L7 7L1 1" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        $home = $settings['home_text'];
        $before = '<span class="current">'; // Tag before the current crumb
        $after = '</span>'; // Tag after the current crumb

        // Main Breadcrumbs
        echo '<nav class="breadcrumbs" aria-label="Breadcrumbs">';
        echo '<a href="' . home_url() . '">' . $home . '</a>' . $separator;

        if (is_home() || is_front_page()) {
            echo $before . 'Blog' . $after;
        } elseif (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>' . $separator;
            }
            echo $before . get_the_title() . $after;
        } elseif (is_page()) {
            echo $before . get_the_title() . $after;
        } elseif (is_category()) {
            echo $before . single_cat_title('', false) . $after;
        } elseif (is_tag()) {
            echo $before . single_tag_title('', false) . $after;
        } elseif (is_author()) {
            echo $before . 'Author: ' . get_the_author() . $after;
        } elseif (is_search()) {
            echo $before . 'Search Results for: ' . get_search_query() . $after;
        } elseif (is_404()) {
            echo $before . '404 Not Found' . $after;
        }

        echo '</nav>';
    }
}
