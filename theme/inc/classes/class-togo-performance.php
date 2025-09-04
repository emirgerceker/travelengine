<?php
defined('ABSPATH') || exit;

if (!class_exists('Togo_Performance')) {
	class Togo_Performance
	{

		protected static $instance = null;

		static function instance()
		{
			if (null === self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {}
	}

	Togo_Performance::instance()->initialize();
}
