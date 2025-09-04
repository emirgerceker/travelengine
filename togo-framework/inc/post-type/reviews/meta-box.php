<?php

namespace Togo_Framework\Post_Type\Reviews;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Trips
 */
class Metabox
{
    /**
     * Instance
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Initiator
     *
     * @since 1.0.0
     * @return object
     */
    public static function instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        add_filter('uxper_meta_box_config', array($this, 'register_meta_boxes'));
        add_filter('reviews_fields', array($this, 'add_reviews_fields'));
    }

    /**
     * Register Metabox
     *
     * @param $meta_boxes
     *
     * @return array
     */
    public function register_meta_boxes($configs)
    {
        $format_number = '^[0-9]+([.][0-9]+)?$';
        $configs['reviews_meta_boxes'] = apply_filters('reviews_meta_boxes', array(
            'id'        => 'togo_reviews_options',
            'name'      => esc_html__('Review Settings', 'togo-framework'),
            'post_type' => array('togo_review'),
            'section'   => array_merge(
                apply_filters('reviews_meta_boxes_top', array()),
                apply_filters(
                    'reviews_meta_boxes_main',
                    array_merge(
                        array(
                            array(
                                'id'     => "trip_reviews_tabs",
                                'title'  => esc_html__('Reviews', 'togo-framework'),
                                'icon'   => 'dashicons-star-half',
                                'fields' => array_merge(
                                    array(
                                        array(
                                            'id' => "review_trip_id",
                                            'title' => esc_html__('Trip ID', 'togo-framework'),
                                            'pattern' => "{$format_number}",
                                            'type' => 'text',
                                        ),
                                        array(
                                            'id'    => "trip_reviews_images",
                                            'title' => esc_html__('Images', 'togo-framework'),
                                            'type'  => 'gallery',
                                        ),
                                    ),
                                    apply_filters('reviews_fields', array()),
                                )
                            ),
                        )
                    )
                ),
                apply_filters('trips_meta_boxes_bottom', array())
            ),
        ));

        return apply_filters('trips_register_meta_boxes', $configs);
    }

    /**
     * Add review fields to the reviews meta box.
     *
     * This function retrieves settings for the maximum star rating and 
     * review items, and adds them as fields to the reviews meta box.
     * Each review item is configured as a numeric input field with a 
     * specified range from 0 to the maximum star rating.
     *
     * @param array $fields Existing fields for the meta box.
     * @return array Updated fields with added review items.
     */

    public function add_reviews_fields($fields)
    {
        $single_trip_max_star = \Togo\Helper::setting('single_trip_max_star') ? \Togo\Helper::setting('single_trip_max_star') : 5;
        $single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
        $review_items = [];
        $format_number = '^[0-9]+([.][0-9]+)?$';
        foreach ($single_trip_reviews as $key => $value) {
            $review_items[] = array(
                'id' => "trip_reviews_" . $key,
                'title' => $value['text'],
                'type' => 'text',
                'input_type' => 'number',
                'pattern' => "{$format_number}",
                'col' => '3',
                'args' => array(
                    'min' => 0,
                    'max' => $single_trip_max_star,
                )
            );
        }
        $fields[] = array(
            'type' => 'row',
            'col' => '12',
            'fields' => $review_items
        );
        return $fields;
    }
}
