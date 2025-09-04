<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get the current page URL.
$current_url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
// Remove param status
$current_url = remove_query_arg('status', $current_url);

$order_id = $_GET['view'];
$order = wc_get_order($order_id);
$item = $order->get_items();
$products = [];
foreach ($item as $key => $value) {
    $product_id = $value->get_product_id();
    array_push($products, $product_id);
}
?>
<div class="booking-details">
    <div class="booking-details__summary">
        <div class="booking-details__summary-item">
            <h6><?php echo esc_html__('Order status', 'togo-framework'); ?></h6>
            <div class="booking-details__summary-content">
                <span class="<?php echo $order->get_status(); ?>"><?php echo wc_get_order_status_name($order->get_status()); ?></span>
            </div>
        </div>
        <div class="booking-details__summary-item">
            <h6><?php echo esc_html__('Order Summary', 'togo-framework'); ?></h6>
            <div class="booking-details__summary-content">
                <ul>
                    <li>
                        <p class="label"><?php echo esc_html__('Order ID', 'togo-framework'); ?>:</p>
                        <p class="content">#<?php echo $order->get_id(); ?></p>
                    </li>
                    <li>
                        <p class="label"><?php echo esc_html__('Order Date', 'togo-framework'); ?>:</p>
                        <p class="content"><?php echo $order->get_date_created()->date('M d, Y'); ?></p>
                    </li>
                    <li class="tour-name">
                        <p class="label"><?php echo esc_html__('Tour', 'togo-framework'); ?>:</p>
                        <p class="content">
                            <?php
                            foreach ($item as $key => $value) {
                                $product_id = $value->get_product_id();
                                $product_name = get_the_title($product_id);
                                $product_url = get_the_permalink($product_id);
                                echo '<a href="' . esc_url($product_url) . '">' . esc_html($product_name) . '</a>';
                            }
                            ?>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="booking-details__summary-item">
            <h6><?php echo esc_html__('Billing details', 'togo-framework'); ?></h6>
            <div class="booking-details__summary-content">
                <ul>
                    <li>
                        <p class="label"><?php echo esc_html__('First Name', 'togo-framework'); ?>:</p>
                        <p class="content"><?php echo $order->get_billing_first_name(); ?></p>
                    </li>
                    <li>
                        <p class="label"><?php echo esc_html__('Last Name', 'togo-framework'); ?>:</p>
                        <p class="content"><?php echo $order->get_billing_last_name(); ?></p>
                    </li>
                    <li>
                        <p class="label"><?php echo esc_html__('Email', 'togo-framework'); ?>:</p>
                        <p class="content"><?php echo $order->get_billing_email(); ?></p>
                    </li>
                    <li>
                        <p class="label"><?php echo esc_html__('Phone', 'togo-framework'); ?>:</p>
                        <p class="content"><?php echo $order->get_billing_phone(); ?></p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="booking-detail-actions">
            <a href="<?php echo esc_url($current_url); ?>" class="togo-button full-filled">
                <?php echo esc_html__('Back to bookings', 'togo-framework'); ?>
            </a>
            <?php if ($order->get_status() != 'cancelled' && $order->get_status() != 'completed') : ?>
                <a href="#cancel-booking" class="togo-button underline togo-open-modal">
                    <?php echo esc_html__('Cancel booking', 'togo-framework'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="booking-details__price">
        <h6><?php echo esc_html__('Price Breakdown', 'togo-framework'); ?></h6>
        <?php
        foreach ($products as $product_id) {
            $reservation_data = get_post_meta($order_id, 'trip_order_reservation_data_' . $product_id, true);
            if (!empty($reservation_data)) {
                echo '<div class="booking-details__price-item">';
                $services_with_price = $reservation_data['services_with_price'];
                $services_total_price = $reservation_data['services_total_price'];
                $pricing_type = $reservation_data['pricing_type'];
                $guests_price = $reservation_data['guests_price'] ? explode(',', $reservation_data['guests_price']) : array();
                $guests = $reservation_data['guests'];
                $pricing_categories = wp_get_post_terms($product_id, 'togo_trip_pricing_categories');
                echo '<div class="product-name">';
                echo '<h6>' . esc_html(get_the_title($product_id)) . '</h6>';
                echo '</div>';
                if (!empty($pricing_categories) && $pricing_type === 'per_person') {
                    echo '<div class="product-price">';
                    foreach ($pricing_categories as $key => $category) {
                        if ($guests[$key] > 0 && $guests_price[$key] > 0) {
                            echo '<div class="item">';
                            echo '<span class="value">' . \Togo_Framework\Helper::togo_format_price($guests_price[$key]) . ' x ' . $guests[$key] . ' ' . esc_html($category->slug) . '</span>';
                            echo '<span class="price">' . \Togo_Framework\Helper::togo_format_price($guests_price[$key] * $guests[$key]) . '</span>';
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                } elseif (!empty($pricing_categories) && $pricing_type === 'per_group') {
                    echo '<div class="product-price">';
                    if ($guests[0] > 0 && $guests_price[0] > 0) {
                        echo '<div class="item">';
                        echo '<span class="value">' . \Togo_Framework\Helper::togo_format_price($guests_price[0]) . ' / ' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo-framework'), $guests[0]) . '</span>';
                        echo '<span class="price">' . \Togo_Framework\Helper::togo_format_price($guests_price[0]) . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                if (!empty($services_with_price)) {
                    echo '<div class="product-service">';
                    foreach ($services_with_price as $key => $service) {
                        echo '<div class="item">';
                        echo '<span class="value">' . esc_html($service) . '</span>';
                        echo '<span class="price">' . esc_html($services_total_price[$key]) . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        ?>
        <div class="booking-details__price-subtotal">
            <p class="label"><?php echo esc_html__('Subtotal', 'togo-framework'); ?></p>
            <p class="content"><?php echo wc_price($order->get_total()); ?></p>
        </div>
    </div>
</div>
<?php if ($order->get_status() != 'cancelled' && $order->get_status() != 'completed') : ?>
    <div class="togo-modal togo-modal-cancel-booking" id="cancel-booking">
        <div class="togo-modal-overlay"></div>
        <div class="togo-modal-content">
            <div class="togo-modal-header">
                <h3 class="togo-modal-title"><?php echo esc_html__('Cancel booking', 'togo-framework'); ?></h3>
                <div class="togo-modal-close"><?php echo \Togo\Icon::get_svg('x'); ?></div>
            </div>
            <div class="togo-modal-body">
                <p><?php echo esc_html__('Are you sure you want to cancel this booking?', 'togo-framework'); ?></p>
            </div>
            <div class="togo-modal-footer">
                <input type="hidden" name="booking_id" value="<?php echo esc_attr($order_id); ?>">
                <a href="#" class="togo-modal-close togo-button line"><?php echo esc_html__('Cancel', 'togo-framework'); ?></a>
                <a href="#" class="togo-button full-filled action-cancel-booking">
                    <?php echo esc_html__('Agree', 'togo-framework'); ?>
                    <?php echo \Togo\Icon::get_svg('spinner-one', 'loading-icon'); ?></a>
            </div>
        </div>
    </div>
<?php endif; ?>