<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class Metabox
 */
class Init
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
     * Constructor SP_Loader
     * *******************************************************
     */
    public function __construct()
    {
        /**
         *  Backend
         */
        require TOGO_FRAMEWORK_PATH . '/inc/backend/customizer/init.php';
        \Togo_Framework\Customizer::instance();
        /**
         *  Classes
         */
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-enqueue.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-helper.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-ajax.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-template.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-woo.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-capability.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/class-import.php';
        require TOGO_FRAMEWORK_PATH . '/inc/payment/woo/init.php';
        require TOGO_FRAMEWORK_PATH . '/inc/classes/trip/class-togo-trip-posts.php';


        \Togo_Framework\Enqueue::instance();
        \Togo_Framework\Helper::instance();
        \Togo_Framework\Ajax::instance();
        \Togo_Framework\Woocommerce::instance();
        \Togo_Framework\Template::instance();
        \Togo_Framework\Capability::instance();
        \Togo_Framework\Import::instance();
        \Togo_Framework\Payment\Woo\Init::instance();
        \Togo_Framework\Trip\Posts::instance();
    }
}
