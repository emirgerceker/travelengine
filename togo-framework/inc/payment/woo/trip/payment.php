<?php

namespace Togo_Framework\Payment\Woo\Trip;

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Class Metabox
 */
class Payment
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
		add_action('wp_loaded', array($this, 'uxper_include_togo_trip_payment_class'));
		add_filter('uxper_filter_checkout_integration_post_types', array($this, 'uxper_add_togo_trip_to_post_types_payment'), 100);
		add_action('wp', array($this, 'uxper_set_notice_for_togo_trip_page'));
		add_action('woocommerce_add_to_cart_handler_togo_trip', array($this, 'uxper_add_to_cart_togo_trip_action'));
		add_action('woocommerce_return_to_shop_redirect', array($this, 'uxper_override_empty_cart_link_togo_trip'));
		add_action('woocommerce_before_cart', array($this, 'uxper_validate_togo_trip_reservation_data_before_cart_loaded'));
		add_action('uxper_action_woocommerce_before_side_area_cart_content', array($this, 'uxper_validate_togo_trip_reservation_data_before_cart_loaded'));
		add_filter('woocommerce_available_variation', array($this, 'wc_available_variation_max_qty'), 10, 3);
		add_action('woocommerce_after_cart_item_name', array($this, 'uxper_add_additional_cart_info'));
		add_action('woocommerce_thankyou', array($this, 'booking_trip_payment_complete'));
		// ensure session is initialized early for trip products
		add_action('init', array($this, 'ensure_woocommerce_session_for_trips'));
		//add_action('woocommerce_init', array($this, 'triggle_remove_product_from_cart'));
		// hook into custom events
		//add_action('remove_product_from_cart_event', [$this, 'remove_product_from_cart']);
	}

	public function uxper_include_togo_trip_payment_class()
	{
		require_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/trip/classes/class-wc-product-trip.php';
		require_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/trip/classes/class-wc-order-item-trip.php';
		require_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/trip/classes/class-wc-order-item-trip-store.php';
		require_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/trip/classes/class-wc-trip-data-store-cpt.php';
	}

	public function uxper_add_togo_trip_to_post_types_payment($post_types)
	{
		$post_types[] = 'togo_trip';

		return $post_types;
	}

	public function uxper_set_notice_for_togo_trip_page()
	{
		add_action('uxper_action_before_togo_trip_post_content', 'wc_print_notices', 10);
	}

	/**
	 * ensure WooCommerce session is initialized early for trip pages
	 */
	public function ensure_woocommerce_session_for_trips()
	{
		if (is_admin() || !class_exists('WooCommerce')) {
			return;
		}

		$is_trip_page = is_singular('togo_trip') || 
						(isset($_REQUEST['add-to-cart']) && get_post_type($_REQUEST['add-to-cart']) === 'togo_trip') ||
						get_query_var('post_type') === 'togo_trip';

		if ($is_trip_page && function_exists('WC')) {
			if (is_null(WC()->session)) {
				WC()->session = new WC_Session_Handler();
				WC()->session->init();
			}
			
			if (!WC()->session->get_customer_unique_id()) {
				WC()->session->set_customer_session_cookie(true);
			}
		}
	}

	public function uxper_add_to_cart_togo_trip_action()
	{
		$product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['add-to-cart']));
		$checkout = isset($_REQUEST['checkout']) ? absint($_REQUEST['checkout']) : 0;
		$quantity = 1;
		$passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);

		$product = wc_get_product($product_id);

		// ensure WooCommerce session is initialized
		if (is_null(WC()->session) || !WC()->session->get_customer_unique_id()) {
			WC()->session->init();
			WC()->session->set_customer_session_cookie(true);
		}

		// check if there is reservation data
		$reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($product_id);
		
		if (empty($reservation_data)) {
			// try to get data from possible transients
			$session_id = WC()->session->get_customer_unique_id();
			$transient_key = "togo_trip_reservation_data_{$product_id}_{$session_id}";
			$backup_data = get_transient($transient_key);
			
			if (empty($backup_data)) {
				wp_safe_redirect(get_permalink($product_id));
				exit;
			} else {
				$reservation_data = $backup_data;
			}
		}

		if ($checkout == 1) {
			WC()->cart->empty_cart();
		} else {
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				if ($cart_item['product_id'] == $product_id) {
					WC()->cart->remove_cart_item($cart_item_key);
				}
			}
		}

		if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) !== false) {

			if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
				wc_add_to_cart_message(array($product_id => $quantity), true);
			}
			if ($checkout == 1) {
				wp_safe_redirect(wc_get_checkout_url());
			} else {
				wp_safe_redirect(wc_get_cart_url());
			}
			exit;
		}
	}

	public function uxper_override_empty_cart_link_togo_trip($url)
	{
		return esc_url(get_post_type_archive_link('togo_trip'));
	}

	public function uxper_validate_togo_trip_reservation_data_before_cart_loaded()
	{
		$items = WC()->cart->get_cart();

		if (!empty($items)) {
			foreach ($items as $item) {
				$product_id = $item['data']->get_id();
				if (get_post_type($product_id) === 'togo_trip') {
				}
			}
		}
	}

	public function wc_available_variation_max_qty($data, $product, $variation)
	{
		$data['max_qty'] = 15;

		return $data;
	}

	public function uxper_add_reservation_details_template($cart_item, $product_id, $reservation_items = array(), $print = true)
	{
		if (!empty($product_id) && get_post_type($product_id) === 'togo_trip') {
			$reservation_data = !empty($reservation_items) ? $reservation_items : \Togo_Framework\Helper::get_transient_woo_booking($product_id);
			if (empty($reservation_data)) {
				return;
			}
			$enable_holding_time = \Togo\Helper::setting('enable_holding_time');
			$booking_date = $reservation_data['booking_date'];
			$trip_id = $reservation_data['trip_id'];
			$pricing_type = $reservation_data['pricing_type'];
			$guests = $reservation_data['guests'];
			$time_type = $reservation_data['time_type'];
			$time = $reservation_data['time'];
			$opening_hours = $reservation_data['opening_hours'];
			$many_days_start_time = $reservation_data['many_days_start_time'];
			$services_without_price = $reservation_data['services_without_price'];
			$schedule_time = $reservation_data['schedule_time'];
			$date_format = get_option('date_format');
			$pricing_categories = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
			ob_start();
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

			if ($enable_holding_time && $schedule_time) {
				echo '<div class="item">';
				echo \Togo\Icon::get_svg('clock-circle');
				echo '<span class="schedule-time" data-time="' . $schedule_time . '">' . sprintf(__('We’ll hold your spot for <span>%d</span> minutes.', 'togo-framework'), '') . '</span>';
				echo '</div>';
			}

			echo '</div>';
			return ob_get_clean();
		}
	}

	public function uxper_add_additional_cart_info($cart_item)
	{
		echo self::uxper_add_reservation_details_template($cart_item, $cart_item['product_id']);
	}

	public function booking_trip_payment_complete($order_id)
	{
		if (!$order_id) {
			return;
		}

		// Allow modification of the order_id, e.g., for partial payment scenarios
		$order_id = apply_filters('uxper_woo_payment_complete_order_id', $order_id);

		// Get the order object
		$order = wc_get_order($order_id);
		if (!$order) {
			return;
		}

		// Define the post data for the new booking post
		$post_data = [
			'post_title'  => '#' . $order_id, // Use order ID as the title
			'post_status' => 'publish', // Publish or pending
			'post_type'   => 'togo_booking', // Custom post type
			'post_author' => 1, // Author ID (can be dynamic)
		];

		// Insert the post into the database
		$post_id = wp_insert_post($post_data);

		// Check for errors during post creation
		if (is_wp_error($post_id)) {
			error_log('Failed to create booking post for Order ID: ' . $order_id);
			return;
		}

		update_post_meta($post_id, 'order_id', $order_id);
	}

	// public function schedule_product_add_to_cart($product_id, $schedule_time)
	// {
	// 	if (!function_exists('WC') || !WC()->cart) {
	// 		return; // Ensure WooCommerce is loaded and cart is available.
	// 	}

	// 	$cart_item_key = WC()->cart->add_to_cart($product_id);
	// 	if ($cart_item_key && ! wp_next_scheduled('remove_product_from_cart_event')) {
	// 		// Schedule product removal ? minutes after adding
	// 		wp_schedule_single_event($schedule_time, 'remove_product_from_cart_event', [$cart_item_key]);
	// 	}
	// }

	// public function triggle_remove_product_from_cart()
	// {
	// 	do_action('remove_product_from_cart_event', $cart_item_key);
	// }

	// public function remove_product_from_cart($cart_item_key)
	// {
	// 	// Use 'wp' or 'template_redirect' to ensure WooCommerce cart is initialized
	// 	add_action('woocommerce_init', function () use ($cart_item_key) {
	// 		// Get the current cart
	// 		$cart = WC()->cart->get_cart();

	// 		// Check if the item exists in the cart
	// 		if (isset($cart[$cart_item_key])) {
	// 			WC()->cart->remove_cart_item($cart_item_key);
	// 		}
	// 	});
	// }
}
