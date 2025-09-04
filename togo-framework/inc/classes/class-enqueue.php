<?php

namespace Togo_Framework;

if (!defined('ABSPATH')) {
    exit;
}

class Enqueue
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
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor SP_Loader
     * *******************************************************
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 9999);
    }

    public static function enqueue_scripts()
    {
        wp_enqueue_script('togo-vimeo-player', 'https://player.vimeo.com/api/player.js', array(), null, true);
    }
}
