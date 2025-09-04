<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Customizer
 */
class Customizer
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
     * Instantiate the object.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', array($this, 'load_customizer'), 99);
    }

    /**
     * Load customizer sections when all widgets init
     */
    public function load_customizer()
    {
        \Togo\Kirki::add_config('theme', array(
            'option_type' => 'theme_mod',
            'capability'  => 'edit_theme_options',
        ));

        // Trip
        require_once TOGO_FRAMEWORK_PATH . '/inc/backend/customizer/options/trip/_panel.php';
        require_once TOGO_FRAMEWORK_PATH . '/inc/backend/customizer/options/trip/archive.php';
        require_once TOGO_FRAMEWORK_PATH . '/inc/backend/customizer/options/trip/single.php';
        require_once TOGO_FRAMEWORK_PATH . '/inc/backend/customizer/options/trip/card.php';
    }
}
