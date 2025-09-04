<?php

namespace Togo_Framework\Post_Type;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Razzi Addons init
 *
 * @since 1.0.0
 */
class Mega_Menu
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

    public function __construct()
    {
        // Hook the post type and taxonomy registration to the 'init' action
        add_filter('uxper_register_post_type', array($this, 'register_post_type'));
        add_post_type_support('togo_mega_menu', 'elementor');
    }

    public function register_post_type($post_types)
    {
        $post_types['togo_mega_menu'] = array(
            'label'           => esc_html__('Mega Menus', 'togo-framework'),
            'singular_name'   => esc_html__('Mega Menus', 'togo-framework'),
            'supports'        => array('title'),
            'menu_icon'       => 'dashicons-menu-alt',
            'can_export'      => true,
            'show_in_rest'    => false,
            'capability_type' => 'mega_menu',
            'map_meta_cap'    => true,
            'menu_position'   => 4,
            'rewrite'         => array(
                'slug' => apply_filters('togo_mega_menu_slug', 'togo_mega_menu'),
            ),
        );

        return $post_types;
    }
}
