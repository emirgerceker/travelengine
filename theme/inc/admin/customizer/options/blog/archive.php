<?php

// Blog archive
\Togo\Kirki::add_section('blog_archive', array(
	'title'    => esc_html__('Blog Archive', 'togo'),
	'panel'    => $panel,
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => 'blog_archive_customize',
	'label'    => esc_html__('Blog Archive', 'togo'),
	'section'  => 'blog_archive',
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'radio-image',
	'settings'  => 'blog_archive_sidebar_position',
	'label'     => esc_html__('Sidebar Layout', 'togo'),
	'section'   => 'blog_archive',
	'transport' => 'auto',
	'priority'  => $priority++,
	'default'   => $default['blog_archive_sidebar_position'],
	'choices'   => [
		'left'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/left-sidebar.png',
		'none'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/no-sidebar.png',
		'right' => get_template_directory_uri() . '/inc/admin/customizer/assets/images/right-sidebar.png',
	],
]);

\Togo\Kirki::add_field('theme', array(
	'type'            => 'select',
	'settings'        => 'blog_archive_active_sidebar',
	'label'           => esc_html__('Sidebar', 'togo'),
	'description'     => esc_html__('Select sidebar that will display on blog archive pages.', 'togo'),
	'section'         => 'blog_archive',
	'priority'        => $priority++,
	'default'         => $default['blog_archive_active_sidebar'],
	'choices'         => Togo\Helper::get_registered_sidebars(),
	'active_callback' => [
		[
			'setting'  => 'blog_archive_sidebar_position',
			'operator' => '!==',
			'value'    => 'none',
		]
	],
));

\Togo\Kirki::add_field('theme', [
	'type'      => 'slider',
	'settings'  => 'blog_archive_sidebar_width',
	'label'     => esc_html__('Sidebar Width', 'togo'),
	'section'   => 'blog_archive',
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['blog_archive_sidebar_width'],
	'choices'   => [
		'min'  => 270,
		'max'  => 420,
		'step' => 1,
	],
	'output'    => array(
		array(
			'element'  => '#secondary.sidebar-blog-archive',
			'property' => 'flex-basis',
			'units'    => 'px',
			'media_query' 	=> '@media (min-width: 992px)',
		),
		array(
			'element'  => '#secondary.sidebar-blog-archive',
			'property' => 'max-width',
			'units'    => 'px',
			'media_query' 	=> '@media (min-width: 992px)',
		),
	),
	'active_callback' => [
		[
			'setting'  => 'blog_archive_sidebar_position',
			'operator' => '!==',
			'value'    => 'none',
		]
	],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'select',
	'settings' => 'blog_archive_page_title_layout',
	'label'    => esc_html__('Page Title', 'togo'),
	'section'  => 'blog_archive',
	'priority' => $priority++,
	'default'  => $default['blog_archive_page_title_layout'],
	'choices'  => Togo_Page_Title::get_list(true),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'radio-buttonset',
	'settings'  => 'blog_archive_pagination_position',
	'label'     => esc_html__('Pagination Position', 'togo'),
	'section'   => 'blog_archive',
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['blog_archive_pagination_position'],
	'choices'   => array(
		'left'   => esc_attr__('Left', 'togo'),
		'center' => esc_attr__('Center', 'togo'),
		'right'  => esc_attr__('Right', 'togo'),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => 'blog_card',
	'label'    => esc_html__('Blog Card', 'togo'),
	'section'  => 'blog_archive',
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'radio-buttonset',
	'settings'  => 'blog_card_layout',
	'label'     => esc_html__('Layout', 'togo'),
	'section'   => 'blog_archive',
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['blog_card_layout'],
	'choices'   => array(
		'default'   => esc_attr__('Default', 'togo'),
		'grid'   => esc_attr__('Grid', 'togo'),
		'list' => esc_attr__('List', 'togo'),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => 'blog_archive_header',
	'label'    => esc_html__('Header', 'togo'),
	'section'  => 'blog_archive',
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
	'type'        => 'select',
	'settings'    => 'blog_archive_top_bar_type',
	'label'       => esc_html__('Top Bar Style', 'togo'),
	'description' => esc_html__('Select top bar style that displays on blog archive pages.', 'togo'),
	'section'     => 'blog_archive',
	'priority'    => $priority++,
	'default'     => $default['blog_archive_top_bar_type'],
	'choices'     => Togo\Theme::get_list_templates(true, 'togo_top_bar'),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'select',
	'settings'    => 'blog_archive_header_type',
	'label'       => esc_html__('Header Style', 'togo'),
	'description' => esc_html__('Select header style that displays on blog archive pages.', 'togo'),
	'section'     => 'blog_archive',
	'priority'    => $priority++,
	'default'     => $default['blog_archive_header_type'],
	'choices'     => Togo\Theme::get_list_templates(true, 'togo_header'),
));
