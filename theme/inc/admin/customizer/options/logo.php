<?php

$section  = 'logo';
$prefix   = 'logo_';
$priority = 2;

// Logo
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Logo', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', array(
	'type'     => 'number',
	'settings' => $prefix . 'width',
	'label'    => esc_html__('Logo Max Width', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'width'],
	'choices'  => [
		'min'  => 0,
		'max'  => 800,
		'step' => 1,
	],
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'image',
	'settings' => $prefix . 'dark',
	'label'    => esc_html__('Logo Dark', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'dark'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'image',
	'settings' => $prefix . 'dark_retina',
	'label'    => esc_html__('Logo Dark Retina', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'dark_retina'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'image',
	'settings' => $prefix . 'light',
	'label'    => esc_html__('Logo Light', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'light'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'image',
	'settings' => $prefix . 'light_retina',
	'label'    => esc_html__('Logo Light Retina', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'light_retina'],
]);
