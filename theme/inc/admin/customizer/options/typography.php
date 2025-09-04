<?php

$priority = 4;
$section  = 'typography';
$prefix   = 'typography_';

$font_weights = array(
	'200',
	'200italic',
	'300',
	'300italic',
	'regular',
	'italic',
	'500',
	'500italic',
	'600',
	'600italic',
	'700',
	'700italic',
	'800',
	'800italic',
	'900',
	'900italic',
);

// Typography
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Typography', 'togo'),
	'priority' => $priority++,
));

// Body Font
\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => $prefix . 'notice_body_font',
	'label'    => esc_html__('Body Font', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'body',
	'label'       => esc_html__('Font Settings', 'togo'),
	'description' => esc_html__('These settings control the typography for all body text.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => $default['font-family'],
		'font-size'      => $default['font-size'],
		'variant'        => $default['variant'],
		'line-height'    => $default['line-height'],
		'letter-spacing' => $default['letter-spacing'],
	),
	'choices'     => array(
		'variant' => $font_weights,
	),
	'output'      => array(
		array(
			'element' => 'body',
		),
	),
));

// Heading Font
\Togo\Kirki::add_field('theme', [
	'type'     => 'notice',
	'settings' => $prefix . 'notice_heading_font',
	'label'    => esc_html__('Heading Font', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
	'type'        => 'kirki_typography',
	'settings'    => $prefix . 'heading',
	'label'       => esc_html__('Font Settings', 'togo'),
	'description' => esc_html__('These settings control the typography for all heading text.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => $default['heading-font-family'],
		'line-height'    => $default['heading-line-height'],
		'variant'        => $default['heading-variant'],
		'letter-spacing' => $default['heading-letter-spacing'],
	),
	'choices'     => array(
		'variant' => $font_weights,
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'slider',
	'settings'    => 'h1_font_size',
	'label'       => esc_html__('Font size', 'togo'),
	'description' => esc_html__('H1', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 56,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'slider',
	'settings'    => 'h2_font_size',
	'description' => esc_html__('H2', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 48,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'slider',
	'settings'    => 'h3_font_size',
	'description' => esc_html__('H3', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 40,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'slider',
	'settings'    => 'h4_font_size',
	'description' => esc_html__('H4', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 36,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'slider',
	'settings'    => 'h5_font_size',
	'description' => esc_html__('H5', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 32,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'slider',
	'settings'    => 'h6_font_size',
	'description' => esc_html__('H6', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => 24,
	'transport'   => 'auto',
	'choices'     => array(
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	),
));
