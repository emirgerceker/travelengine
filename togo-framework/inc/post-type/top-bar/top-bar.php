<?php

namespace Togo_Framework\Post_Type;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Top_Bar init
 *
 * @since 1.0.0
 */
class Top_Bar
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
        add_post_type_support('togo_top_bar', 'elementor');
    }

    /**
     * Register Post Type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type($post_types)
    {
        $post_types['togo_top_bar'] = array(
            'label'           => esc_html__('Top Bar', 'togo-framework'),
            'singular_name'   => esc_html__('Top Bar', 'togo-framework'),
            'supports'        => array('title'),
            'menu_icon'       => 'dashicons-calendar-alt',
            'can_export'      => true,
            'show_in_rest'    => false,
            'capability_type' => 'top_bar',
            'map_meta_cap'    => true,
            'menu_position'   => 4,
            'rewrite'         => array(
                'slug' => apply_filters('togo_top_bar_slug', 'togo_top_bar'),
            ),
        );

        return $post_types;
    }
}
