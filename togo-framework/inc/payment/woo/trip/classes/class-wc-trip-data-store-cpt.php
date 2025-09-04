<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * WC Product Data Store: Stored in CPT.
 *
 * @version  3.0.0
 * @category Class
 * @author   WooThemes
 */
class WC_Togo_Trip_Data_Store extends Uxper_Woo_Data_Store_Custom
{

	public function __construct()
	{
		$this->set_post_type();
	}

	function set_post_type()
	{
		$this->custom_post_type = 'togo_trip';
	}
}
