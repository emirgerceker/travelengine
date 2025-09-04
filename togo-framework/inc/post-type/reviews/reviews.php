<?php

namespace Togo_Framework\Post_Type;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Reviews init
 *
 * @since 1.0.0
 */
class Reviews
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
        // Hook the post type and taxonomy registration to the 'init' action
        add_filter('uxper_register_post_type', array($this, 'register_post_type'));
    }

    /**
     * Register Post Type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type($post_types)
    {
        $post_types['togo_review'] = array(
            'label'           => esc_html__('Reviews', 'togo-framework'),
            'singular_name'   => esc_html__('Reviews', 'togo-framework'),
            'supports'        => array('title', 'editor'),
            'menu_icon'       => 'dashicons-star-filled',
            'can_export'      => true,
            'show_in_rest'    => false,
            'capability_type' => 'review',
            'map_meta_cap'    => true,
            'menu_position'   => 4,
            'rewrite'         => array(
                'slug' => apply_filters('togo_review_slug', 'togo_review'),
            ),
        );

        return $post_types;
    }
}
