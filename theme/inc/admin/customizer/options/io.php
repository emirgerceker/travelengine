<?php

$priority = 19;
$section  = 'io';

// IO Control
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Import / Export', 'togo'),
	'priority' => 9999,
));

\Togo\Kirki::add_field('theme', [
	'type'        => 'custom',
	'settings'    => 'import_control',
	'label'       => esc_html__('Import', 'togo'),
	'description' => esc_html__('Click the button below to import the customization settings for this theme.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '<div class="import-control io-control"><button name="Import" id="togo-customizer-import" class="button-primary button">' . __('Import', 'togo') . '</button><input type="file" id="import-file" name="import-file" style="display:none;"/></div>',
]);

\Togo\Kirki::add_field('theme', [
	'type'        => 'custom',
	'settings'    => 'export_control',
	'label'       => esc_html__('Export', 'togo'),
	'description' => esc_html__('Click the button below to export the customization settings for this theme.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '<div class="export-control io-control"><a href="' . get_site_url() . '/wp-admin/options.php?page=togo_export_customizer_options" id="togo-customizer-export" class="button-primary button">' . __('Export', 'togo') . '</a></div>',
]);

if (WP_DEBUG) {
	\Togo\Kirki::add_field('theme', [
		'type'        => 'custom',
		'settings'    => 'export_demo_control',
		'label'       => esc_html__('Export for Demo', 'togo'),
		'description' => esc_html__('Click the button below to export the customization settings for this theme.', 'togo'),
		'section'     => $section,
		'priority'    => $priority++,
		'default'     => '<div class="export-control io-control"><form action=""><input type="submit" class="button-primary button" name="export" value="' . __('Export', 'togo') . '"/></form></div>',
	]);
}

\Togo\Kirki::add_field('theme', array(
	'type'        => 'custom',
	'settings'    => 'reset_control',
	'label'       => esc_html__('Reset', 'togo'),
	'description' => esc_html__('Click the button below to reset the customization settings for this theme.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '<div class="reset-control io-control"><button name="Reset" id="togo-customizer-reset" class="button-primary button">' . __('Reset Options', 'togo') . '</button></div>',
));
