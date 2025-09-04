<?php

namespace Togo_Framework\Payment\Woo;

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
         *  Classes
         */
        if (function_exists('WC')) {
            require TOGO_FRAMEWORK_PATH . '/inc/payment/woo/checkout.php';
            require TOGO_FRAMEWORK_PATH . '/inc/payment/woo/trip/payment.php';

            \Togo_Framework\Payment\Woo\Checkout::instance();
            \Togo_Framework\Payment\Woo\Trip\Payment::instance();
        }
    }
}
