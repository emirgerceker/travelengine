<?php

/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

if (! defined('ABSPATH')) {
	exit;
}

if (! apply_filters('woocommerce_order_item_visible', true, $item)) {
	return;
}
?>
<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order)); ?>">

	<td class="woocommerce-table__product-name product-name">
		<?php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);

		echo wp_kses_post(apply_filters('woocommerce_order_item_name', $product_permalink ? sprintf('<a href="%s">%s</a>', $product_permalink, $item->get_name()) : $item->get_name(), $item, $is_visible));

		$reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($product->get_id());
		if (!empty($reservation_data)) {
			update_post_meta($order->get_id(), 'trip_order_reservation_data_' . $product->get_id(), $reservation_data);
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
			if (!empty($reservation_data)) {
				echo '<div class="reservation-detail">';

				if ($time_type == 'start_times') {
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
					echo '<span class="value">' . esc_html__('Open at', 'togo') . ' ' . $opening_hours . '</span>';
					echo '</div>';
				} elseif ($time_type == 'many_days') {
					echo '<div class="item">';
					echo \Togo\Icon::get_svg('calendar-check');
					echo '<span class="value">' . date($date_format, strtotime($booking_date)) . '</span>';
					echo '</div>';
					echo '<div class="item">';
					echo \Togo\Icon::get_svg('clock-circle');
					echo '<span class="value">' . esc_html__('Departure at', 'togo') . ' ' . $many_days_start_time . '</span>';
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
					echo '<span class="value">' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo'), $guests[0]) . '</span>';
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
			}
		}

		do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);

		wc_display_item_meta($item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
		?>
	</td>

	<td class="woocommerce-table__product-total product-total">
		<?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
	</td>

</tr>

<?php if ($show_purchase_note && $purchase_note) : ?>

	<tr class="woocommerce-table__product-purchase-note product-purchase-note">

		<td colspan="2"><?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?></td>

	</tr>

<?php endif; ?>