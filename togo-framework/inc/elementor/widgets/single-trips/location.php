<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Single_Trips;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Location extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-location';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Location', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-map-pin';
    }

    public function get_categories()
    {
        return ['single-trips'];
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls() {}

    /**
     * Render the widget output.
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (!is_singular('togo_trip')) {
            return;
        }
        $id = get_the_ID();
        $taxonomy = 'togo_trip_destinations';

        $terms = wp_get_post_terms($id, $taxonomy);


        if (!empty($terms) && !is_wp_error($terms)) {
            echo '<div class="togo-st-location">';
            foreach ($terms as $term) {
                echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
            }
            echo '</div>';
        }
    }
}
