<?php
/*
Plugin Name: Togo Framework
Description: Framework for Togo theme
Author: Uxper
Version: 1.0.2
Author URI: https://uxper.co
Text Domain: togo-framework
Domain Path: /languages/
*/
defined('ABSPATH') || exit;

if (! defined('TOGO_FRAMEWORK_PATH')) {
	define('TOGO_FRAMEWORK_PATH', plugin_dir_path(__FILE__));
}

if (! defined('TOGO_FRAMEWORK_DIR')) {
	define('TOGO_FRAMEWORK_DIR', plugin_dir_url(__FILE__));
}

require_once TOGO_FRAMEWORK_PATH . 'togo-framework-setup.php';

\Togo_Framework\Setup::instance();
