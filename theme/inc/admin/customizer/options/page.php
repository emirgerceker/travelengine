<?php

$priority = 8;
$section  = 'page';
$prefix   = 'page_';

// Layout
\Togo\Kirki::add_section($section, array(
	'title'    => esc_attr__('Page', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => $prefix . 'notice_header',
	'label'    => esc_html__('Header', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
	'type'        => 'select',
	'settings'    => $prefix . 'top_bar_type',
	'label'       => esc_html__('Top Bar', 'togo'),
	'description' => esc_html__('Select top bar that displays on blog archive pages.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => $default[$prefix . 'top_bar_type'],
	'choices'     => Togo\Theme::get_list_templates(true, 'togo_top_bar'),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'select',
	'settings'    => $prefix . 'header_type',
	'label'       => esc_html__('Header Style', 'togo'),
	'description' => esc_html__('Select header style that displays on blog archive pages.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => $default[$prefix . 'header_type'],
	'choices'     => Togo\Theme::get_list_templates(true, 'togo_header'),
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => $prefix . 'notice_sidebar',
	'label'    => esc_html__('Sidebar', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'radio-image',
	'settings'  => $prefix . 'sidebar_position',
	'label'     => esc_html__('Sidebar Layout', 'togo'),
	'section'   => $section,
	'transport' => 'auto',
	'priority'  => $priority++,
	'default'   => $default[$prefix . 'sidebar_position'],
	'choices'   => [
		'left'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/left-sidebar.png',
		'none'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/no-sidebar.png',
		'right' => get_template_directory_uri() . '/inc/admin/customizer/assets/images/right-sidebar.png',
	],
]);

\Togo\Kirki::add_field('theme', array(
	'type'            => 'select',
	'settings'        => $prefix . 'active_sidebar',
	'label'           => esc_html__('Sidebar', 'togo'),
	'description'     => esc_html__('Select sidebar that will display on blog archive pages.', 'togo'),
	'section'         => $section,
	'priority'        => $priority++,
	'default'         => $default[$prefix . 'active_sidebar'],
	'choices'         => Togo\Helper::get_registered_sidebars(),
	'active_callback' => [
		[
			'setting'  => $prefix . 'sidebar_position',
			'operator' => '!==',
			'value'    => 'none',
		]
	],
));

\Togo\Kirki::add_field('theme', [
	'type'      => 'slider',
	'settings'  => $prefix . 'sidebar_width',
	'label'     => esc_html__('Sidebar Width', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default[$prefix . 'sidebar_width'],
	'choices'   => [
		'min'  => 270,
		'max'  => 420,
		'step' => 1,
	],
	'active_callback' => [
		[
			'setting'  => $prefix . 'sidebar_position',
			'operator' => '!==',
			'value'    => 'none',
		]
	],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => $prefix . 'notice_page_title',
	'label'    => esc_html__('Page Title', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'select',
	'settings' => $prefix . 'page_title_layout',
	'label'    => esc_html__('Page Title', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'page_title_layout'],
	'choices'  => Togo_Page_Title::get_list(true),
]);
