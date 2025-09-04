<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Woocommerce
 */
class Woocommerce
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
        add_filter('woocommerce_show_admin_notice', array($this, 'hide_woocommerce_admin_notice'), 10, 2);
        add_filter('woocommerce_cart_item_quantity', array($this, 'disable_cart_quantity_fields'), 10, 3);
        remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
        add_action('woocommerce_checkout_order_summary', 'woocommerce_order_review', 10);
        add_filter('woocommerce_checkout_fields', array($this, 'hide_billing_address_fields'));
        add_filter('woocommerce_checkout_fields', array($this, 'add_class_to_billing_address_field'));
        add_filter('woocommerce_email_order_items_table', array($this, 'customize_admin_new_order_email'), 10, 2);
        add_filter('woocommerce_email_styles', array($this, 'add_custom_css_to_woocommerce_emails'));
        // Move the coupon field to the Review Order section
        add_action('woocommerce_review_order_before_cart_subtotal', array($this, 'custom_coupon_form_in_order_summary'), 10);
        add_filter('woocommerce_cart_item_removed_notice', '__return_false', 10, 2);
        add_filter('woocommerce_notices', array($this, 'remove_undo_notice'));
        add_action('woocommerce_cart_loaded_from_session', array($this, 'remove_item_from_cart'));
    }

    function hide_woocommerce_admin_notice($show, $notice)
    {
        if ('template_files' === $notice) {
            return false;
        }

        return $show;
    }

    public function disable_cart_quantity_fields($quantity, $cart_item_key, $cart_item)
    {
        return '<span>' . $cart_item['quantity'] . '</span>';
    }

    public function hide_billing_address_fields($fields)
    {
        // Remove the billing company field
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_state']);
        unset($fields['billing']['billing_city']);

        // Alternatively, you can hide a field using CSS
        // $fields['billing']['billing_company']['class'] = array( 'hidden-field' );

        return $fields;
    }

    public function add_class_to_billing_address_field($fields)
    {
        // Add a custom class to the billing address field
        $fields['billing']['billing_phone']['class'][] = 'form-row-first';
        $fields['billing']['billing_email']['class'][] = 'form-row-last';

        return $fields;
    }

    public function customize_admin_new_order_email($output, $order)
    {
        ob_start();

        // Loop through order items
        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();

            if ($product) {
                echo '<tr>';
                $reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($product->get_id());
                if (!empty($reservation_data)) {
                    $booking_date = $reservation_data['booking_date'];
                    $trip_id = $reservation_data['trip_id'];
                    $pricing_type = $reservation_data['pricing_type'];
                    $guests = $reservation_data['guests'];
                    $time_type = $reservation_data['time_type'];
                    $time = $reservation_data['time'];
                    $opening_hours = $reservation_data['opening_hours'];
                    $many_days_start_time = $reservation_data['many_days_start_time'];
                    $services_without_price = $reservation_data['services_without_price'];
                    $date_format = get_option('date_format');
                    $pricing_categories = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
                    echo '<td class="togo-td" colspan="2">';
                    echo '<div class="reservation-detail">';
                    echo '<h6>' . esc_html($product->get_name()) . '</h6>';
                    if (
                        $time_type == 'start_times'
                    ) {
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('calendar-check');
                        echo '<span class="value">' . date($date_format, strtotime($booking_date)) . '</span>';
                        echo '</div>';
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('clock-circle');
                        echo '<span class="value">' . \Togo_Framework\Helper::convert24To12($time) . '</span>';
                        echo '</div>';
                    } elseif ($time_type == 'opening_hours') {
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('calendar-check');
                        echo '<span class="value">' . date($date_format, strtotime($booking_date)) . '</span>';
                        echo '</div>';
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('clock-circle');
                        echo '<span class="value">' . esc_html__('Open at', 'togo-framework') . ' ' . $opening_hours . '</span>';
                        echo '</div>';
                    } elseif ($time_type == 'many_days') {
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('calendar-check');
                        echo '<span class="value">' . date($date_format, strtotime($booking_date)) . '</span>';
                        echo '</div>';
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('clock-circle');
                        echo '<span class="value">' . esc_html__('Departure at', 'togo-framework') . ' ' . $many_days_start_time . '</span>';
                        echo '</div>';
                    }

                    if (!empty($pricing_categories) && $pricing_type == 'per_person') {
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('users-group');
                        echo '<div class="values">';
                        foreach ($pricing_categories as $key => $category) {
                            echo '<span class="value">' . $guests[$key] . ' ' . esc_html($category->name) . '</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                    } elseif (!empty($pricing_categories) && $pricing_type == 'per_group') {
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('users-group');
                        echo '<div class="values">';
                        echo '<span class="value">' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo-framework'), $guests[0]) . '</span>';
                        echo '</div>';
                        echo '</div>';
                    }

                    if (!empty($services_without_price)) {
                        echo '<div class="item">';
                        echo \Togo\Icon::get_svg('room-service');
                        echo '<div class="values">';
                        foreach ($services_without_price as $service) {
                            echo '<span class="value">' . esc_html($service) . '</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</td>';
                }
                echo '<td class="togo-td">' . wc_price($product->get_price()) . '</td>';
                echo '</tr>';
            }
        }

        $output = ob_get_clean();

        return $output;
    }

    public function add_custom_css_to_woocommerce_emails($css)
    {
        // Custom CSS to add
        $custom_css = "
        .togo-td{
            border: 1px solid #e5e5e5;
        }
        .reservation-detail {
            display: flex;
            flex-direction: column;
            row-gap: 4px;
        }
        .reservation-detail h6  {
            margin: 0;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .reservation-detail .item {
            display: flex;
        }
        .reservation-detail .item .togo-svg-icon {
            margin-right: 8px;
        }
        .reservation-detail .item svg {
            width: 20px;
        }
        .reservation-detail .values {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .reservation-detail .value {
            font-weight: 400;
            font-size: 14px;
        }
        .reservation-detail .value:after {
            content: ',';
            margin-right: 5px;
        }
        .reservation-detail .value:last-child:after {
            content: '';
        }
    ";

        return $css . $custom_css; // Append custom CSS
    }

    public function custom_coupon_form_in_order_summary()
    {
?>
        <div class="coupon-wrapper">
            <a href="#" class="open-coupon"><?php echo esc_html__('Enter Promo Code', 'togo-framework'); ?></a>
            <div class="coupon-field">
                <input type="text" name="custom_coupon_code" id="custom_coupon_code" class="input-text" placeholder="<?php _e('Enter your coupon', 'woocommerce'); ?>" />
                <a href="#" id="togo-coupon-code"><?php _e('Apply', 'woocommerce'); ?></a>
            </div>
            <div class="coupon-notice"></div>
        </div>
<?php
    }

    public function remove_undo_notice($notices)
    {
        if (isset($notices['success'])) {
            foreach ($notices['success'] as $key => $notice) {
                if (strpos($notice, 'removed') !== false) {
                    unset($notices['success'][$key]);
                }
            }
        }

        return $notices;
    }

    public function remove_item_from_cart()
    {
        $cart = WC()->cart;
        foreach ($cart->get_cart() as $key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($product_id);
            if (empty($reservation_data)) {
                $cart->remove_cart_item($key);
            }
        }
    }
}
