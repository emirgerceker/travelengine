<?php

$section  = 'button';
$priority = 1;

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

// General
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('General', 'togo'),
	'panel'    => $panel,
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'kirki_typography',
	'settings'    => 'button_typography',
	'label'       => esc_attr__('Font Settings', 'togo'),
	'description' => esc_attr__('These settings control the typography for all button text.', 'togo'),
	'section'     => $section,
	'transport'   => 'auto',
	'default'     => array(
		'font-family'    => $default['button_font_family'],
		'font-size'      => $default['button_font_size'],
		'line-height'    => $default['button_line_height'],
		'variant'        => $default['button_variant'],
		'letter-spacing' => $default['button_letter_spacing'],
		'text-transform' => $default['button_text_transform'],
	),
	'choices'     => array(
		'variant' => $font_weights,
	),
));
