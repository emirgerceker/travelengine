<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get the current page URL.
$current_url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
// Remove param status
$current_url = remove_query_arg('status', $current_url);

$order_id = $_GET['invoice'];
if (empty($order_id)) {
    return; // No order ID found.
}
$order = wc_get_order($order_id);
$item = $order->get_items();
$products = [];
foreach ($item as $key => $value) {
    $product_id = $value->get_product_id();
    array_push($products, $product_id);
}
?>
<div class="invoice-wrapper" id="printable-area">
    <div class="invoice-item invoice-company">
        <div class="invoice-item__left">
            <div class="logo">
                <?php echo \Togo\Templates::site_logo(); ?>
            </div>
        </div>
        <div class="invoice-item__right">
            <h6 class="company-name"><?php echo $args['company_name']; ?></h6>
            <p class="company-address"><?php echo sprintf(__('Address: %s', 'togo-framework'), $args['company_address']); ?></p>
            <p class="company-phone"><?php echo sprintf(__('Phone: %s', 'togo-framework'), $args['company_phone']); ?></p>
            <p class="company-email"><?php echo sprintf(__('Email: %s', 'togo-framework'), $args['company_email']); ?></p>
        </div>
    </div>
    <div class="invoice-item bill-info">
        <div class="invoice-item__left">
            <h6><?php echo esc_html__('Bill to', 'togo-framework'); ?></h6>
            <p><span><?php echo esc_html__('Customer name:', 'togo-framework'); ?></span><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></p>
            <?php if ($order->get_billing_email()) : ?>
                <p><span><?php echo esc_html__('Customer Email:', 'togo-framework'); ?></span><?php echo $order->get_billing_email(); ?></p>
            <?php endif; ?>
            <?php if ($order->get_billing_phone()) : ?>
                <p><span><?php echo esc_html__('Customer Phone:', 'togo-framework'); ?></span><?php echo $order->get_billing_phone(); ?></p>
            <?php endif; ?>
        </div>
        <div class="invoice-item__right">
            <p><?php echo sprintf(__('Invoice number: %s', 'togo-framework'), $order_id); ?></p>
            <p><?php echo sprintf(__('Date: %s', 'togo-framework'), $order->get_date_created()->date('M d, Y')); ?></p>
        </div>
    </div>
    <div class="invoice-item particular-top">
        <div class="invoice-item__left">
            <span><?php echo esc_html__('Particulars', 'togo-framework'); ?></span>
        </div>
        <div class="invoice-item__right">
            <span><?php echo esc_html__('Total', 'togo-framework'); ?></span>
        </div>
    </div>
    <?php
    foreach ($products as $product_id) {
        echo '<div class="invoice-item particular-main">';
        $reservation_data = get_post_meta($order_id, 'trip_order_reservation_data_' . $product_id, true);
        if (!empty($reservation_data)) {
            echo '<div class="invoice-item__left">';
            $services_with_price = $reservation_data['services_with_price'];
            $services_total_price = $reservation_data['services_total_price'];
            $pricing_type = $reservation_data['pricing_type'];
            $guests_price = $reservation_data['guests_price'] ? explode(',', $reservation_data['guests_price']) : array();
            $guests = $reservation_data['guests'];
            $pricing_categories = wp_get_post_terms($product_id, 'togo_trip_pricing_categories');
            echo '<h6>' . esc_html(get_the_title($product_id)) . '</h6>';
            if (!empty($pricing_categories) && $pricing_type === 'per_person') {
                foreach ($pricing_categories as $key => $category) {
                    if ($guests[$key] > 0 && $guests_price[$key] > 0) {
                        echo '<p>' . \Togo_Framework\Helper::togo_format_price($guests_price[$key]) . ' x ' . $guests[$key] . ' ' . esc_html($category->slug) . '</p>';
                    }
                }
            } elseif (!empty($pricing_categories) && $pricing_type === 'per_group') {
                if ($guests[0] > 0 && $guests_price[0] > 0) {
                    echo '<p>' . \Togo_Framework\Helper::togo_format_price($guests_price[0]) . ' / ' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo-framework'), $guests[0]) . '</p>';
                }
            }
            echo '<h6 class="extra-services">' . esc_html('Extra Services', 'togo-framework') . '</h6>';
            if (!empty($services_with_price)) {
                foreach ($services_with_price as $key => $service) {
                    echo '<p>' . esc_html($service) . '</p>';
                }
            }
            echo '</div>';
            echo '<div class="invoice-item__right">';
            $services_with_price = $reservation_data['services_with_price'];
            $services_total_price = $reservation_data['services_total_price'];
            $pricing_type = $reservation_data['pricing_type'];
            $guests_price = $reservation_data['guests_price'] ? explode(',', $reservation_data['guests_price']) : array();
            $guests = $reservation_data['guests'];
            $pricing_categories = wp_get_post_terms($product_id, 'togo_trip_pricing_categories');
            echo '<h6>' . esc_html(get_the_title($product_id)) . '</h6>';
            if (!empty($pricing_categories) && $pricing_type === 'per_person') {
                foreach ($pricing_categories as $key => $category) {
                    if ($guests[$key] > 0 && $guests_price[$key] > 0) {
                        echo '<p>' . \Togo_Framework\Helper::togo_format_price($guests_price[$key] * $guests[$key]) . '</p>';
                    }
                }
            } elseif (!empty($pricing_categories) && $pricing_type === 'per_group') {
                if ($guests[0] > 0 && $guests_price[0] > 0) {
                    echo '<p>' . \Togo_Framework\Helper::togo_format_price($guests_price[0]) . '</p>';
                }
            }
            echo '<h6 class="extra-services">' . esc_html('Extra Services', 'togo-framework') . '</h6>';
            if (!empty($services_with_price)) {
                foreach ($services_with_price as $key => $service) {
                    echo '<p>' . esc_html($services_total_price[$key]) . '</p>';
                }
            }
            echo '</div>';
        }
        echo '</div>';
    }
    ?>
    <div class="invoice-item particular-bottom">
        <div class="invoice-item__left">
            <p><?php echo esc_html__('Subtotal', 'togo-framework'); ?></p>
            <p><?php echo esc_html__('Discount', 'togo-framework'); ?></p>
            <p><?php echo esc_html__('Tax', 'togo-framework'); ?></p>
            <p class="total"><?php echo esc_html__('Total', 'togo-framework'); ?></p>
        </div>
        <div class="invoice-item__right">
            <p><?php echo wc_price($order->get_total()); ?></p>
            <p><?php echo wc_price($order->get_discount_total()); ?></p>
            <p><?php echo wc_price($order->get_total_tax()); ?></p>
            <p class="total"><?php echo wc_price($order->get_total()); ?></p>
        </div>
    </div>
    <div class="payment-details">
        <h6><?php echo esc_html__('Payment details', 'togo-framework'); ?></h6>
        <div class="payment-details__item">
            <p class="label"><?php echo esc_html__('Payment method', 'togo-framework'); ?></p>
            <p><?php echo esc_html($order->get_payment_method_title()); ?></p>
        </div>
        <div class="payment-details__item">
            <p class="label"><?php echo esc_html__('Payment amount', 'togo-framework'); ?></p>
            <p><?php echo wc_price($order->get_total()); ?></p>
        </div>
        <div class="payment-details__item">
            <p class="label"><?php echo esc_html__('Date', 'togo-framework'); ?></p>
            <p><?php echo esc_html($order->get_date_created()->date('M d, Y')); ?></p>
        </div>
        <?php if ($order->get_transaction_id()) : ?>
            <div class="payment-details__item">
                <p class="label"><?php echo esc_html__('Payment method', 'togo-framework'); ?></p>
                <p><?php echo esc_html($order->get_payment_method_title()); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="invoice-action">
        <a href="<?php echo esc_url($current_url); ?>" class="togo-button underline">
            <?php echo esc_html__('Back to bookings', 'togo-framework'); ?>
        </a>
        <button onclick="printDiv('printable-area')" class="togo-button line print-invoice with-icon">
            <?php echo \Togo\Icon::get_svg('printer'); ?>
            <?php echo esc_html__('Print', 'togo-framework'); ?>
        </button>
    </div>
</div>
<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>