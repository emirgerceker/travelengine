<?php

$priority = 7;
$section  = 'layout';
$prefix   = 'layout_';

// Layout
\Togo\Kirki::add_section($section, array(
	'title'    => esc_attr__('Layout', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'      => 'radio-image',
	'settings'  => 'layout_content',
	'label'     => esc_attr__('Layout Type', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'postMessage',
	'default'   => $default['layout_content'],
	'choices'   => [
		'boxed'     => get_template_directory_uri() . '/inc/admin/customizer/assets/images/boxed.png',
		'fullwidth' => get_template_directory_uri() . '/inc/admin/customizer/assets/images/full-width.png',
	],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'slider',
	'settings'  => 'boxed_width',
	'label'     => esc_attr__('Boxed Width', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['boxed_width'],
	'choices'   => [
		'min'  => 400,
		'max'  => 1920,
		'step' => 1,
	],
	'active_callback' => [
		[
			'setting'  => 'layout_content',
			'operator' => '==',
			'value'    => 'boxed',
		]
	],
	'output'    => array(
		array(
			'element'  => 'body.boxed',
			'property' => 'max-width',
			'units'    => 'px',
		),
	),
]);

// Background
\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => 'notice_bg_color',
	'label'    => esc_html__('Background', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'body_background_color',
	'label'     => esc_html__('Body Background', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['body_background_color'],
	'output'    => array(
		array(
			'element'  => 'html',
			'property' => 'background-color',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'content_background_color',
	'label'     => esc_html__('Content Background', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['content_background_color'],
	'output'    => array(
		array(
			'element'  => 'body.boxed',
			'property' => 'background-color',
		),
	),
	'active_callback' => [
		[
			'setting'  => 'layout_content',
			'operator' => '==',
			'value'    => 'boxed',
		]
	],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'image',
	'settings'  => 'bg_body_image',
	'label'     => esc_html__('Body BG Image', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['bg_body_image'],
	'output'    => array(
		array(
			'element'  => 'html',
			'property' => 'background-image',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'select',
	'settings'  => 'bg_body_size',
	'label'     => esc_html__('Background Size', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['bg_body_size'],
	'choices'   => [
		'auto'    => esc_attr__('Auto', 'togo'),
		'cover'   => esc_attr__('Cover', 'togo'),
		'contain' => esc_attr__('Contain', 'togo'),
		'initial' => esc_attr__('Initial', 'togo'),
	],
	'output'    => array(
		array(
			'element'  => 'html',
			'property' => 'background-size',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'select',
	'settings'  => 'bg_body_repeat',
	'label'     => esc_html__('Background Repeat', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['bg_body_repeat'],
	'choices'   => [
		'no-repeat' => esc_attr__('No Repeat', 'togo'),
		'repeat'    => esc_attr__('Repeat', 'togo'),
		'repeat-x'  => esc_attr__('Repeat X', 'togo'),
		'repeat-y'  => esc_attr__('Repeat Y', 'togo'),
	],
	'output'    => array(
		array(
			'element'  => 'html',
			'property' => 'background-repeat',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'select',
	'settings'  => 'bg_body_position',
	'label'     => esc_html__('Background Position', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['bg_body_position'],
	'choices'   => [
		'left top'      => esc_attr__('Left Top', 'togo'),
		'left center'   => esc_attr__('Left Center', 'togo'),
		'left bottom'   => esc_attr__('Left Bottom', 'togo'),
		'right top'     => esc_attr__('Right Top', 'togo'),
		'right center'  => esc_attr__('Right Center', 'togo'),
		'right bottom'  => esc_attr__('Right Bottom', 'togo'),
		'center top'    => esc_attr__('Center Top', 'togo'),
		'center center' => esc_attr__('Center Center', 'togo'),
		'center bottom' => esc_attr__('Center Bottom', 'togo'),
	],
	'output'    => array(
		array(
			'element'  => 'html',
			'property' => 'background-position',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'select',
	'settings'  => 'bg_body_attachment',
	'label'     => esc_html__('Background Attachment', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['bg_body_attachment'],
	'choices'   => [
		'scroll' => esc_attr__('Scroll', 'togo'),
		'fixed'  => esc_attr__('Fixed', 'togo'),
	],
	'output'    => array(
		array(
			'element'  => 'html',
			'property' => 'background-attachment',
		),
	),
]);
