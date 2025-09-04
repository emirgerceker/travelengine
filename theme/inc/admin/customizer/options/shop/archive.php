<?php
\Togo\Kirki::add_field('theme', [
	'type'     => 'select',
	'settings' => 'product_archive_page_title_layout',
	'label'    => esc_html__('Page Title', 'togo'),
	'section'  => 'woocommerce_product_catalog',
	'priority' => $priority++,
	'default'  => $default['product_archive_page_title_layout'],
	'choices'  => Togo_Page_Title::get_list(true),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'radio-image',
	'settings'  => 'product_archive_sidebar_position',
	'label'     => esc_html__('Sidebar Layout', 'togo'),
	'section'   => 'woocommerce_product_catalog',
	'transport' => 'auto',
	'priority'  => $priority++,
	'default'   => $default['product_archive_sidebar_position'],
	'choices'   => [
		'left'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/left-sidebar.png',
		'none'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/no-sidebar.png',
		'right' => get_template_directory_uri() . '/inc/admin/customizer/assets/images/right-sidebar.png',
	],
]);

\Togo\Kirki::add_field('theme', array(
	'type'            => 'select',
	'settings'        => 'product_archive_active_sidebar',
	'label'           => esc_html__('Sidebar', 'togo'),
	'description'     => esc_html__('Select sidebar that will display on shop archive pages.', 'togo'),
	'section'         => 'woocommerce_product_catalog',
	'priority'        => $priority++,
	'default'         => $default['product_archive_active_sidebar'],
	'choices'         => \Togo\Helper::get_registered_sidebars(),
	'active_callback' => [
		[
			'setting'  => 'product_archive_sidebar_position',
			'operator' => '!==',
			'value'    => 'none',
		]
	],
));
