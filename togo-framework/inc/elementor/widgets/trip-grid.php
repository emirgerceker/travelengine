<?php

/**
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Trip_Grid_Widget
 *
 * Elementor widget for displaying the trip.
 *
 * @since 1.0.0
 */
class Togo_Trip_Grid_Widget extends Base
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
        return 'togo-trip-grid';
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
        return __('Trip Grid', 'togo-framework');
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
        return 'eicon-posts-grid';
    }

    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-el-google-maps');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_items_section();
        $this->add_content_items_style_section();
        $this->add_content_order_section();
        $this->add_content_filters_section();
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
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 100,
                'step' => 1,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => __('1 Column', 'togo-framework'),
                    '2' => __('2 Columns', 'togo-framework'),
                    '3' => __('3 Columns', 'togo-framework'),
                    '4' => __('4 Columns', 'togo-framework'),
                    '5' => __('5 Columns', 'togo-framework'),
                    '6' => __('6 Columns', 'togo-framework'),
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __('Row Gap', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-trip-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Add the content items section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_items_section()
    {
        $this->start_controls_section(
            'content_items_section',
            [
                'label' => __('Items', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => '01',
                'options' => [
                    '01' => __('01', 'togo-framework'),
                    '02' => __('02', 'togo-framework'),
                    '03' => __('03', 'togo-framework'),
                    '04' => __('04', 'togo-framework'),
                    '05' => __('05', 'togo-framework'),
                ],
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __('Image Size', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '600x450',
                'description' => __('Enter the image size in the format width x height (e.g., 600x450).', 'togo-framework'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content items style section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_items_style_section()
    {
        $this->start_controls_section(
            'content_items_style_section',
            [
                'label' => __('Items Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .trip-title a',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content order section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_order_section()
    {
        $this->start_controls_section(
            'content_order_section',
            [
                'label' => __('Order', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __('Order By', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __('Date', 'togo-framework'),
                    'title' => __('Title', 'togo-framework'),
                    'rand' => __('Random', 'togo-framework'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => __('Ascending', 'togo-framework'),
                    'DESC' => __('Descending', 'togo-framework'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content filters section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_filters_section()
    {
        $destinations = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_destinations', true);
        $activities = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_activities', true);
        $types = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_types', true);
        $durations = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_durations', true);
        $tod = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_tod', true);
        $languages = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_languages', true);
        $services = \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_services', true);

        $this->start_controls_section(
            'content_filters_section',
            [
                'label' => __('Filters', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'destinations',
            [
                'label' => __('Destinations', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $destinations,
                'default' => '',
            ]
        );

        $this->add_control(
            'activities',
            [
                'label' => __('Activities', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $activities,
                'default' => '',
            ]
        );

        $this->add_control(
            'types',
            [
                'label' => __('Types', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $types,
                'default' => '',
            ]
        );

        $this->add_control(
            'durations',
            [
                'label' => __('Durations', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $durations,
                'default' => '',
            ]
        );

        $this->add_control(
            'tod',
            [
                'label' => __('Time of Day', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $tod,
                'default' => '',
            ]
        );

        $this->add_control(
            'languages',
            [
                'label' => __('Languages', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $languages,
                'default' => '',
            ]
        );

        $this->add_control(
            'services',
            [
                'label' => __('Services', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $services,
                'default' => '',
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
        $columns = $settings['columns'] ? $settings['columns'] : 3;
        $columns_tablet = isset($settings['columns_tablet']) && !empty($settings['columns_tablet']) ? $settings['columns_tablet'] : 2;
        $columns_mobile = isset($settings['columns_mobile']) && !empty($settings['columns_mobile']) ? $settings['columns_mobile'] : 1;
        $posts_per_page = $settings['posts_per_page'] ? $settings['posts_per_page'] : 6;
        $layout = $settings['layout'] ? $settings['layout'] : '01';
        $tax_query = array();
        $meta_query = array();
        $order_by = $settings['order_by'] ? $settings['order_by'] : 'date';
        $order = $settings['order'] ? $settings['order'] : 'DESC';
        if ($settings['destinations'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_destinations',
                'field' => 'slug',
                'terms' => $settings['destinations'],
            );
        }
        if ($settings['activities'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_activities',
                'field' => 'slug',
                'terms' => $settings['activities'],
            );
        }
        if ($settings['types'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_types',
                'field' => 'slug',
                'terms' => $settings['types'],
            );
        }
        if ($settings['durations'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_durations',
                'field' => 'slug',
                'terms' => $settings['durations'],
            );
        }
        if ($settings['tod'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_tod',
                'field' => 'slug',
                'terms' => $settings['tod'],
            );
        }
        if ($settings['languages'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_languages',
                'field' => 'slug',
                'terms' => $settings['languages'],
            );
        }
        if ($settings['services'] !== '') {
            $tax_query[] = array(
                'taxonomy' => 'togo_trip_services',
                'field' => 'slug',
                'terms' => $settings['services'],
            );
        }
        $args = array(
            'post_type' => 'togo_trip',
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
            'orderby' => $order_by,
            'order' => $order,
            'tax_query' => $tax_query,
            'meta_query' => $meta_query,
        );
        $query = new \WP_Query($args);
        $this->add_render_attribute('wrapper', 'class', 'togo-trip-grid togo-row togo-row-cols-xl-' . $columns . ' togo-row-cols-lg-' . $columns . ' togo-row-cols-md-' . $columns . ' togo-row-cols-sm-' . $columns_tablet . ' togo-row-cols-xs-' . $columns_mobile);

        if ($query->have_posts()) {
            echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
            while ($query->have_posts()) {
                $query->the_post();
                \Togo_Framework\Helper::togo_get_template('content/trip/trip-grid-' . $layout . '.php', array('trip_id' => get_the_ID(), 'image_size' => $settings['image_size']));
            }
            echo '</div>';
        } else {
            echo '<p>' . __('No trips found.', 'togo-framework') . '</p>';
        }

        echo \Togo_Framework\Template::render_itinerary_popup();
    }
}
