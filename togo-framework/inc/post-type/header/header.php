<?php

namespace Togo_Framework\Post_Type;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Header init
 *
 * @since 1.0.0
 */
class Header
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
        add_post_type_support('togo_header', 'elementor');
    }

    /**
     * Register Post Type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type($post_types)
    {
        $post_types['togo_header'] = array(
            'label'           => esc_html__('Header', 'togo-framework'),
            'singular_name'   => esc_html__('Header', 'togo-framework'),
            'supports'        => array('title'),
            'menu_icon'       => 'dashicons-calendar-alt',
            'can_export'      => true,
            'show_in_rest'    => false,
            'capability_type' => 'header',
            'map_meta_cap'    => true,
            'menu_position'   => 4,
            'rewrite'         => array(
                'slug' => apply_filters('togo_header_slug', 'togo_header'),
            ),
        );

        return $post_types;
    }
}
