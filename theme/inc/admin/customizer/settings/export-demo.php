<?php
add_action('customize_register', 'togo_customizer_demo_exporter', 999999);
function togo_customizer_demo_exporter($wp_customize)
{
	if (empty($_REQUEST['export'])) {
		return;
	}

	$core_options = array(
		'blogname',
		'blogdescription',
		'show_on_front',
		'page_on_front',
		'page_for_posts',
	);

	$theme    = get_stylesheet();
	$template = get_template();
	$charset  = get_option('blog_charset');
	$mods     = get_theme_mods();
	$data     = array(
		'template' => $template,
		'mods'     => $mods ? $mods : array(),
		'options'  => array()
	);

	// Get options from the Customizer API.
	$settings = $wp_customize->settings();

	foreach ($settings as $key => $setting) {

		if ('option' == $setting->type) {

			// Don't save widget data.
			if ('widget_' === substr(strtolower($key), 0, 7)) {
				continue;
			}

			// Don't save sidebar data.
			if ('sidebars_' === substr(strtolower($key), 0, 9)) {
				continue;
			}

			// Don't save core options.
			if (in_array($key, $core_options)) {
				continue;
			}

			$data['options'][$key] = $setting->value();
		}
	}

	// Plugin developers can specify additional option keys to export.
	$option_keys = apply_filters('cei_export_option_keys', array());

	foreach ($option_keys as $option_key) {
		$data['options'][$option_key] = get_option($option_key);
	}

	if (function_exists('wp_get_custom_css_post')) {
		$data['wp_css'] = wp_get_custom_css();
	}

	// Set the download headers.
	$filename = 'customizer.dat';
	header('Content-disposition: attachment; filename=' . $filename);
	header('Content-Type: application/octet-stream; charset=' . $charset);

	// Serialize the export data.
	echo serialize($data);

	// Start the download.
	die();
}
