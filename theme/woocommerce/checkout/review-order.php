<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
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

defined('ABSPATH') || exit;
?>
<div class="shop_table woocommerce-checkout-review-order-table">
	<div class="woocommerce-checkout-review-order-top">
		<?php
		do_action('woocommerce_review_order_before_cart_contents');

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($cart_item['product_id']);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
		?>
				<div class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
					<div class="product-info">
						<div class="product-thumbnail">
							<?php
							$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

							if (! $product_permalink) {
								echo wp_kses_post($thumbnail);
							} else {
								printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
							}
							?>
						</div>
						<div class="product-name">
							<?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
							<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?>
						</div>
					</div>

					<?php
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
					?>

					<?php
					if (!empty($reservation_data)) {
						$services_with_price = $reservation_data['services_with_price'];
						$services_total_price = $reservation_data['services_total_price'];
						$pricing_type = $reservation_data['pricing_type'];
						$guests_price = $reservation_data['guests_price'] ? explode(',', $reservation_data['guests_price']) : array();
						$guests = $reservation_data['guests'];
						$pricing_categories = wp_get_post_terms($cart_item['product_id'], 'togo_trip_pricing_categories');

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
								echo '<span class="value">' . \Togo_Framework\Helper::togo_format_price($guests_price[0]) . ' / ' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo'), $guests[0]) . '</span>';
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
					}
					?>

					<div class="product-total">
						<span class="label"><?php esc_html_e('Price', 'togo'); ?></span>
						<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
					</div>
				</div>
		<?php
			}
		}

		do_action('woocommerce_review_order_after_cart_contents');
		?>
	</div>
	<div class="woocommerce-checkout-review-order-bottom">

		<?php do_action('woocommerce_review_order_before_cart_subtotal'); ?>

		<div class="cart-subtotal">
			<div class="label"><?php esc_html_e('Subtotal', 'togo'); ?></div>
			<div class="price"><?php wc_cart_totals_subtotal_html(); ?></div>
		</div>

		<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<div class="label"><?php wc_cart_totals_coupon_label($coupon); ?></div>
				<div class="price"><?php wc_cart_totals_coupon_html($coupon); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

			<?php do_action('woocommerce_review_order_before_shipping'); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action('woocommerce_review_order_after_shipping'); ?>

		<?php endif; ?>

		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
			<div class="fee">
				<div class="label"><?php echo esc_html($fee->name); ?></div>
				<div class="price"><?php wc_cart_totals_fee_html($fee); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) : ?>
			<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
				<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited 
				?>
					<div class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<div class="label"><?php echo esc_html($tax->label); ?></div>
						<div class="price"><?php echo wp_kses_post($tax->formatted_amount); ?></div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="tax-total">
					<div class="label"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></div>
					<div class="price"><?php wc_cart_totals_taxes_total_html(); ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action('woocommerce_review_order_before_order_total'); ?>

		<div class="order-total">
			<div class="label"><?php esc_html_e('Total', 'togo'); ?></div>
			<div class="price"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action('woocommerce_review_order_after_order_total'); ?>

	</div>
</div>