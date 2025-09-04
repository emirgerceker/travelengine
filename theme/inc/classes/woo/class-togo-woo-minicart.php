<?php

namespace Togo\Woo;

defined('ABSPATH') || exit;

class Minicart
{

    protected static $instance = null;

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('wc_ajax_update_cart_item', array($this, 'update_cart_item'));
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'minicart_count_fragments'), 10, 1);
    }

    public function update_cart_item()
    {
        if (
            empty($_POST['cart_item_key']) || ! isset($_POST['qty'])
        ) {
            wp_send_json_error();
            exit;
        }

        $cart_item_key = wc_clean($_POST['cart_item_key']);
        $qty           = floatval($_POST['qty']);

        check_admin_referer('togo-update-cart-qty--' . $cart_item_key, 'security');

        ob_start();

        WC()->cart->set_quantity($cart_item_key, $qty);

        if (
            $cart_item_key && false !== WC()->cart->set_quantity($cart_item_key, $qty)
        ) {
            \WC_AJAX::get_refreshed_fragments();
        } else {
            wp_send_json_error();
        }
    }

    public function minicart_count_fragments($fragments)
    {
        $fragments['span.togo-minicart-icon-qty'] = '<span class="togo-minicart-icon-qty" data-counter="' . WC()->cart->get_cart_contents_count() . '">' . WC()->cart->get_cart_contents_count() . '</span>';
        return $fragments;
    }
}
