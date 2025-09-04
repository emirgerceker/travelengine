<?php

namespace Togo_Framework\Payment\Woo;

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Class Metabox
 */
class Checkout
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
		// Include files
		$this->include_files();

		// Override default WooCommerce hooks
		$this->override_hooks();
	}

	function include_files()
	{
		include_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/classes/class-wc-product.php';
		include_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/classes/class-wc-data-store.php';
		include_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/classes/class-wc-order-item.php';
		include_once TOGO_FRAMEWORK_PATH . 'inc/payment/woo/classes/class-wc-order-item-store.php';
	}

	function override_hooks()
	{
		add_filter('woocommerce_product_class', array($this, 'product_class'), 10, 4);
		add_filter('woocommerce_cart_item_class', array($this, 'checkout_item_classes'), 10, 4);
		add_filter('woocommerce_order_item_class', array($this, 'order_item_classes'), 10, 2);
		add_filter('woocommerce_data_stores', array($this, 'data_store_integration'), 10, 1);
		add_filter('woocommerce_product_type_query', array($this, 'data_store_post_type_override'), 10, 2);
		add_filter('woocommerce_order_type_to_group', array($this, 'data_store_post_type_to_order_type_group'), 10, 1);
		add_filter('woocommerce_checkout_create_order_line_item_object', array($this, 'order_line_item_object_override'), 10, 4);
		add_filter('woocommerce_get_order_item_classname', array($this, 'order_item_classname_override'), 10, 3);
		add_filter('woocommerce_get_items_key', array($this, 'order_item_override'), 10, 2);
		add_filter('woocommerce_order_get_items', array($this, 'order_item_types_global_extend'), 10, 3);
		add_filter('woocommerce_order_get_items', array($this, 'convert_order_item_trip_to_product_for_non_default_payment'), 11, 3);
		add_action('woocommerce_admin_order_item_headers', array($this, 'add_order_item_headers'));
		add_action('woocommerce_admin_order_item_values', array($this, 'add_order_item_values'), 11, 3);
	}

	public function supported_post_types()
	{
		return apply_filters('uxper_filter_checkout_integration_post_types', array());
	}

	public function product_class($classname, $product_type, $post_type, $product_id)
	{
		$post_types = $this->supported_post_types();

		if (in_array($post_type, $post_types)) {
			$classname = 'WC_Product_' . $this->transform_class_name($post_type);
		}

		return $classname;
	}

	public function data_store_integration($data_stores)
	{
		$custom_data_stores = array();
		$post_types         = $this->supported_post_types();

		foreach ($post_types as $post_type) {
			$custom_data_stores[$post_type]                 = 'WC_' . $this->transform_class_name($post_type) . '_Data_Store';
			$custom_data_stores['order-item-' . $post_type] = 'Uxper_Woo_Order_Item_' . $this->transform_class_name($post_type) . '_Data_Store';
		}

		return array_merge($data_stores, $custom_data_stores);
	}

	public function checkout_item_classes($classes, $cart_item, $cart_item_key)
	{
		$classes .= ' uxper-product-type-' . get_post_type($cart_item['product_id']);

		return $classes;
	}

	public function order_item_classes($classes, $item)
	{
		$classes .= ' uxper-product-type-' . get_post_type($item['product_id']);

		return $classes;
	}

	public function data_store_post_type_override($classname, $product_id)
	{
		$supported_types = $this->supported_post_types();
		foreach ($supported_types as $supported_type) {
			if ($supported_type == get_post_type($product_id)) {
				return $supported_type;
			}
		}

		return false;
	}

	public function data_store_post_type_to_order_type_group($order_groups)
	{
		$supported_types = $this->supported_post_types();

		foreach ($supported_types as $supported_type) {
			$type_edited                     = str_replace('-', '_', $supported_type);
			$order_groups[$supported_type] = $type_edited . '_lines';
		}

		return $order_groups;
	}

	public function order_line_item_object_override($order_item, $cart_item_key, $values, $order)
	{
		$supported_types = $this->supported_post_types();
		var_dump($supported_types);
		foreach ($supported_types as $supported_type) {
			if (get_post_type($values['product_id']) == $supported_type) {
				$classname  = 'Uxper_Woo_Order_Item_' . $this->transform_class_name($supported_type);
				$order_item = new $classname;
				break;
			}
		}

		return $order_item;
	}

	public function order_item_classname_override($classname, $item_type, $id)
	{
		$supported_types = $this->supported_post_types();

		foreach ($supported_types as $supported_type) {
			if ($item_type == $supported_type) {
				$classname = 'Uxper_Woo_Order_Item_' . $this->transform_class_name($supported_type);
				break;
			}
		}

		return $classname;
	}

	public function order_item_override($line_item, $item)
	{
		$supported_types = $this->supported_post_types();

		foreach ($supported_types as $supported_type) {
			$classname = 'Uxper_Woo_Order_Item_' . $this->transform_class_name($supported_type);

			if (is_a($item, $classname)) {
				$type      = str_replace('-', '_', $supported_type);
				$line_item = $type . '_lines';
				break;
			}
		}

		return $line_item;
	}

	public function order_item_types_global_extend($items, $order, $types)
	{
		if (is_array($types) && sizeof($types) == 1 && in_array('line_item', $types)) {
			$supported_types   = $this->supported_post_types();
			$supported_types[] = 'line_item';
			$items             = $order->get_items($supported_types, true);
		}

		return $items;
	}

	private function transform_class_name($name)
	{
		$name = ucfirst(str_replace('-', '_', $name));
		$name = implode('_', array_map('ucwords', explode('_', $name)));

		return $name;
	}

	/**
	 * Convert Uxper_WC_Order_Item_Room to WC_Order_Item_Prouct to by pass type check Fatal Errof
	 * when using Woocommerce Paypal Payment
	 *
	 * @param WC_Order_Item[] $items Array of WC_Order_Item
	 * @param WC_Order $order Array of WC_Order
	 * @param array $types WC Item types
	 * @return WC_Order_Item[]
	 **/
	public function convert_order_item_trip_to_product_for_non_default_payment($items, $order, $types)
	{
		$is_checkout = is_checkout();
		$is_thankyou = is_wc_endpoint_url('order-received');
		if ($is_checkout && $is_thankyou) {
			return $items;
		}

		if (!isset($_POST['payment_method'])) {
			return $items;
		}

		$is_default_gateway = in_array($_POST['payment_method'], ['bacs', 'cheque', 'cod']);
		if ($is_default_gateway) {
			return $items;
		}

		foreach ($items as $key => $item) {
			if ($item instanceof Uxper_Woo_Order_Item_Togo_Trip) {
				$converted = new WC_Order_Item_Product($item);
				$reflect = new ReflectionClass($converted);
				$set_prop_ref = $reflect->getMethod('set_prop');
				$set_prop_ref->setAccessible(TRUE);
				$set_prop_ref->invokeArgs($converted, ['product_id', $item->get_product_id()]);
				$items[$key] = $converted;
			}
		}

		return $items;
	}

	public function add_order_item_headers()
	{
		echo '<th class="custom-text">' . esc_html__('Details') .  '</th>';
	}

	public function add_order_item_values($product, $item, $item_id)
	{
		$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$product_id = $product instanceof WC_Product ? $product->get_id() : 0;
		$reservation_data = get_post_meta($order_id, 'trip_order_reservation_data_' . $product_id, true);
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
			if (!empty($reservation_data)) {
				echo '<td class="reservation-detail">';

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

				echo '</td>';
			}
		}
	}
}
