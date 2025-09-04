<?php

$priority = 6;
$section  = 'footer';
$prefix   = 'footer_';

// Footer
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Footer', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => $prefix . 'notice',
	'label'    => esc_html__('Footer Customize', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => $prefix . 'enable_footer',
	'label'       => esc_html__('Enable Footer', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 'yes',
	'choices'     => array(
		'no' => esc_html__('Hide', 'togo'),
		'yes' => esc_html__('Show', 'togo'),
	),
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'select',
	'settings' => $prefix . 'type',
	'label'    => esc_html__('Footer Type', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'type'],
	'choices'  => Togo_Customize::togo_get_footers(false),
]);

\Togo\Kirki::add_field('theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => $prefix . 'copyright_enable',
	'label'       => esc_html__('Display Copyright', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__('Hide', 'togo'),
		'1' => esc_html__('Show', 'togo'),
	),
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => $prefix . 'copyright_text',
	'label'    => esc_html__('Copyright', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default[$prefix . 'copyright_text'],
]);
