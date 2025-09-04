<?php

namespace Togo;

defined('ABSPATH') || exit;

class Woo
{

	protected static $instance = null;

	public static function instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		add_action('wp', array($this, 'add_actions'), 0);
	}

	public function add_actions() {}
}
