<?php

namespace Togo\Woo;

defined('ABSPATH') || exit;

class Helper
{
    /**
     * Returns true if on a page which uses WooCommerce templates exclude single product (cart and checkout are standard pages with shortcodes and which are also included)
     *
     * @access public
     * @return bool
     */
    public static function is_woocommerce_page_without_product()
    {
        if (function_exists('is_shop') && is_shop()) {
            return true;
        }

        if (function_exists('is_product_taxonomy') && is_product_taxonomy()) {
            return true;
        }

        if (is_post_type_archive('product')) {
            return true;
        }

        $the_id = get_the_ID();

        if ($the_id !== false) {
            $woocommerce_keys = array(
                'woocommerce_shop_page_id',
                'woocommerce_terms_page_id',
                'woocommerce_cart_page_id',
                'woocommerce_checkout_page_id',
                'woocommerce_pay_page_id',
                'woocommerce_thanks_page_id',
                'woocommerce_myaccount_page_id',
                'woocommerce_edit_address_page_id',
                'woocommerce_view_order_page_id',
                'woocommerce_change_password_page_id',
                'woocommerce_logout_page_id',
                'woocommerce_lost_password_page_id',
            );

            foreach ($woocommerce_keys as $wc_page_id) {
                if ($the_id == get_option($wc_page_id, 0)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
     *
     * @access public
     * @return bool
     */
    public static function is_woocommerce_page()
    {
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            return true;
        }

        $woocommerce_keys = array(
            "woocommerce_shop_page_id",
            "woocommerce_terms_page_id",
            "woocommerce_cart_page_id",
            "woocommerce_checkout_page_id",
            "woocommerce_pay_page_id",
            "woocommerce_thanks_page_id",
            "woocommerce_myaccount_page_id",
            "woocommerce_edit_address_page_id",
            "woocommerce_view_order_page_id",
            "woocommerce_change_password_page_id",
            "woocommerce_logout_page_id",
            "woocommerce_lost_password_page_id",
        );

        foreach ($woocommerce_keys as $wc_page_id) {
            if (get_the_ID() == get_option($wc_page_id, 0)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if on a archive product pages.
     *
     * @access public
     * @return bool
     */
    public static function is_product_archive()
    {
        if (is_post_type_archive('product') || (function_exists('is_product_taxonomy') && is_product_taxonomy())) {
            return true;
        }

        return false;
    }
}
