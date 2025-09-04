<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

class Uxper_Woo_Order_Item_Togo_Trip extends Uxper_Woo_Order_Item
{

	public function __construct($product = 0)
	{
		$this->set_post_type();
		parent::__construct($product);
	}

	function set_post_type()
	{
		$this->custom_post_type = 'togo_trip';
	}
}
