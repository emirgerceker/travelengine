<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

class WC_Product_Togo_Trip extends Uxper_Woo_Product_Abstract
{

	/**
	 * Get the product if ID is passed, otherwise the product is new and empty.
	 * This class should NOT be instantiated, but the wc_get_product() function
	 * should be used. It is possible, but the wc_get_product() is preferred.
	 *
	 * @param int|WC_Product|object $product Product to init.
	 */
	public function __construct($product = 0)
	{
		parent::__construct($product);

		$this->set_status(get_post_status($this->get_id()));
	}

	function generate_price()
	{
		$product_id = $this->get_id();
		$price = 0;

		$reservation_data = \Togo_Framework\Helper::get_transient_woo_booking($product_id);

		if (!empty($reservation_data['total_price'])) {
			$price = $reservation_data['total_price'];
		}
		return $price;
	}

	function generate_sold_individually()
	{
		return false;
	}

	function generate_stock_status()
	{
		return 'stock';
	}

	function generate_stock_quantity()
	{
		return null;
	}
}
