<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Togo_Trip_Banner_Widget extends Carousel_Base
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
        return 'togo-trip-banner';
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
        return __('Trip Banner', 'togo-framework');
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
        return 'eicon-nested-carousel';
    }

    /**
     * Retrieves the script dependencies for the widget.
     *
     * The script dependencies are the JavaScript files that need to be loaded
     * for the widget to function properly.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array The script dependencies for the widget.
     */
    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-carousel');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        parent::register_controls();
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

        $args = array(
            'post_type' => 'togo_trip',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $query = new \WP_Query($args);
        $trip_list = array();

        if ($query) {
            foreach ($query->posts as $trip) {
                $trip_id = $trip->ID;
                $trip_title = $trip->post_title;
                $trip_list[$trip_id] = $trip_title;
            }
        }

        $this->add_control(
            'trip',
            [
                'label' => __('Trip', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $trip_list,
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
        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);
        // Get post by trip id
        $trip_id = $settings['trip'];
        $trip = get_post($trip_id);
        $trip_title = $trip->post_title;
        $trip_excerpt = $trip->post_excerpt;
        $terms_durations = wp_get_post_terms($trip_id, 'togo_trip_durations');
        $terms_types = wp_get_post_terms($trip_id, 'togo_trip_types');
        $trip_min_guest = get_post_meta($trip_id, 'trip_minimum_guests', true) ? get_post_meta($trip_id, 'trip_minimum_guests', true) : 0;
        $trip_max_guest = get_post_meta($trip_id, 'trip_maximum_guests', true);
        $trip_itinerary = get_post_meta($trip_id, 'trip_itinerary', true);
        $no_image = get_template_directory_uri() . '/assets/images/no-image.jpg';

        echo '<div class="trip-banner-widget">';
        echo '<div class="trip-banner-inner">';
        echo '<h2 class="trip-banner-title">' . $trip_title . '</h2>';
        echo '<p class="trip-banner-excerpt">' . $trip_excerpt . '</p>';
        echo '<div class="trip-banner-info">';
        if (!empty($terms_durations) && !is_wp_error($terms_durations)) {
            echo '<div class="trip-duration">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('clock-circle');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Duration', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<span class="trip-duration-text">' . $terms_durations[0]->name . '</span>';
            echo '</div>';
        }
        if (!empty($terms_types) && !is_wp_error($terms_types)) {
            echo '<div class="trip-types">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('box');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Tour type', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="trip-types-list">';
            foreach ($terms_types as $term) {
                echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        if (!empty($trip_max_guest)) {
            echo '<div class="trip-guests">';
            echo '<div class="togo-tooltip">';
            echo \Togo\Icon::get_svg('users-group');
            echo '<div class="togo-tooltip-content">';
            echo '<p>' . esc_html__('Group size', 'togo-framework') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<span class="trip-guest-text">' . $trip_min_guest . ' - ' . $trip_max_guest . ' ' . esc_html__('guests', 'togo-framework') . '</span>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="trip-banner-footer">';
        echo \Togo_Framework\Helper::get_price_of_trip($trip_id);
        echo '<div class="trip-button">';
        echo '<a href="' . get_permalink($trip_id) . '" class="togo-button full-filled">' . esc_html__('Book tour', 'togo-framework') . '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        if (!empty($trip_itinerary) && $trip_itinerary[0]['trip_itinerary_title'] != '') {
            echo '<div class="trip-itinerary-slider">';
            echo '<div ' . $this->get_render_attribute_string('slider') . '>';
            echo '<div class="swiper-wrapper">';
            foreach ($trip_itinerary as $index => $itinerary) {
                $thumb = $itinerary['trip_itinerary_image']['url'] ? $itinerary['trip_itinerary_image']['url'] : $no_image;
                echo '<div class="swiper-slide">';
                echo '<div class="trip-itinerary">';
                if ($thumb) {
                    echo '<div class="trip-itinerary-thumb">';
                    echo '<img src="' . $thumb . '" alt="' . $itinerary['trip_itinerary_title'] . '">';
                    echo '</div>';
                }
                echo '<div class="trip-itinerary-content">';
                echo '<h3 class="trip-itinerary-title">' . $itinerary['trip_itinerary_title'] . '</h3>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}
