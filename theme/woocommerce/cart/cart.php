<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="woocommerce-cart-wrapper">

	<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
		<h2><?php esc_html_e('Shopping cart', 'togo'); ?></h2>
		<?php do_action('woocommerce_before_cart_table'); ?>

		<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<?php do_action('woocommerce_before_cart_contents'); ?>

			<?php
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
				/**
				 * Filter the product name.
				 *
				 * @since 2.1.0
				 * @param string $product_name Name of the product in the cart.
				 * @param array $cart_item The product in the cart.
				 * @param string $cart_item_key Key for the product in the cart.
				 */
				$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
					<div class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

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

						<div class="product-name" data-title="<?php esc_attr_e('Product', 'togo'); ?>">
							<h6>
								<?php
								if (! $product_permalink) {
									echo wp_kses_post($product_name . '&nbsp;');
								} else {
									/**
									 * This filter is documented above.
									 *
									 * @since 2.1.0
									 */
									echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
								}
								?>
							</h6>
							<?php
							do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

							// Meta data.
							echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

							// Backorder notification.
							if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'togo') . '</p>', $product_id));
							}
							?>
						</div>

						<div class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'togo'); ?>">

							<?php
							echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.

							if (get_post_type($cart_item['product_id']) === 'togo_trip') {
								$reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($cart_item['product_id']);
								if (!empty($reservation_data)) {
									$services_with_price = $reservation_data['services_with_price'];
									$pricing_type = $reservation_data['pricing_type'];
									$guests_price = $reservation_data['guests_price'] ? explode(',', $reservation_data['guests_price']) : array();
									$guests = $reservation_data['guests'];
									$pricing_categories = wp_get_post_terms($cart_item['product_id'], 'togo_trip_pricing_categories');

									if (!empty($pricing_categories) && $pricing_type === 'per_person') {
										foreach ($pricing_categories as $key => $category) {
											if ($guests[$key] > 0 && $guests_price[$key] > 0) {
												echo '<span class="value">' . \Togo_Framework\Helper::togo_format_price($guests_price[$key]) . ' x ' . $guests[$key] . ' ' . esc_html($category->slug) . '</span>';
											}
										}
									} elseif (!empty($pricing_categories) && $pricing_type === 'per_group') {
										if ($guests[0] > 0 && $guests_price[0] > 0) {
											echo '<span class="value">' . \Togo_Framework\Helper::togo_format_price($guests_price[0]) . ' / ' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo'), $guests[0]) . '</span>';
										}
									}
									if (!empty($services_with_price)) {
										foreach ($services_with_price as $service) {
											echo '<span class="value">' . esc_html($service) . '</span>';
										}
									}
								}
							}

							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove togo-button line" aria-label="%s" data-product_id="%s" data-product_sku="%s">' . \Togo\Icon::get_svg('trash') . '</a>',
									esc_url(wc_get_cart_remove_url($cart_item_key)),
									/* translators: %s is the product name */
									esc_attr(sprintf(__('Remove %s from cart', 'togo'), wp_strip_all_tags($product_name))),
									esc_attr($product_id),
									esc_attr($_product->get_sku())
								),
								$cart_item_key
							);
							?>
						</div>
					</div>
			<?php
				}
			}
			?>

			<?php do_action('woocommerce_cart_contents'); ?>

			<?php do_action('woocommerce_after_cart_contents'); ?>
		</div>
		<?php do_action('woocommerce_after_cart_table'); ?>
	</form>

	<?php do_action('woocommerce_before_cart_collaterals'); ?>

	<div class="cart-collaterals">
		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
		?>
	</div>

</div>

<?php do_action('woocommerce_after_cart'); ?>