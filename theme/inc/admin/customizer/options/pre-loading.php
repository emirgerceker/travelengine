<?php

$section  = 'pre_loading';
$prefix   = 'pre_loading_';
$priority = 11;

// Page Loading Effect
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Pre Loading', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'radio',
	'settings' => 'type_loading_effect',
	'label'    => esc_html__('Type Loading Effect', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['type_loading_effect'],
	'choices'  => [
		'none'          => esc_attr__('None', 'togo'),
		'css_animation' => esc_attr__('CSS Animation', 'togo'),
		'image'         => esc_attr__('Image', 'togo'),
	],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'page_loading_effect_bg_color',
	'label'     => esc_html__('Background Color', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => '#fff',
	'output'    => array(
		array(
			'element'  => '.page-loading-effect',
			'property' => 'background-color',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'page_loading_effect_shape_color',
	'label'     => esc_html__('Shape Color', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['accent_color'],
	'output'    => array(
		array(
			'element'  => '.togo-ldef-circle > span,.togo-ldef-facebook span,.togo-ldef-heart span,.togo-ldef-heart span:after,
			.togo-ldef-heart span:before,.togo-ldef-roller span:after,.togo-ldef-default span,.togo-ldef-ellipsis span,
			.togo-ldef-grid span,.togo-ldef-spinner span:after',
			'property' => 'background-color',
		),
		array(
			'element'  => '.togo-ldef-ripple span',
			'property' => 'border-color',
		),
		array(
			'element'  => '.togo-ldef-dual-ring:after,.togo-ldef-ring span,.togo-ldef-hourglass:after',
			'property' => 'border-top-color',
		),
		array(
			'element'  => '.togo-ldef-dual-ring:after,.togo-ldef-hourglass:after',
			'property' => 'border-bottom-color',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'type_loading_effect',
			'operator' => '=',
			'value'    => 'css_animation',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'radio-buttonset',
	'settings' => 'animation_loading_effect',
	'label'    => esc_html__('Animation Type', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['animation_loading_effect'],
	'choices'  => [
		'css-1'  => '<span class="togo-ldef-circle togo-ldef-loading"><span></span></span>',
		'css-2'  => '<span class="togo-ldef-dual-ring togo-ldef-loading"></span>',
		'css-3'  => '<span class="togo-ldef-facebook togo-ldef-loading"><span></span><span></span><span></span></span>',
		'css-4'  => '<span class="togo-ldef-heart togo-ldef-loading"><span></span></span>',
		'css-5'  => '<span class="togo-ldef-ring togo-ldef-loading"><span></span><span></span><span></span><span></span></span>',
		'css-6'  => '<span class="togo-ldef-roller togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>',
		'css-7'  => '<span class="togo-ldef-default togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>',
		'css-8'  => '<span class="togo-ldef-ellipsis togo-ldef-loading"><span></span><span></span><span></span><span></span></span>',
		'css-9'  => '<span class="togo-ldef-grid togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>',
		'css-10' => '<span class="togo-ldef-hourglass togo-ldef-loading"></span>',
		'css-11' => '<span class="togo-ldef-ripple togo-ldef-loading"><span></span><span></span></span>',
		'css-12' => '<span class="togo-ldef-spinner togo-ldef-loading"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>',
	],
	'active_callback' => array(
		array(
			'setting'  => 'type_loading_effect',
			'operator' => '=',
			'value'    => 'css_animation',
		),
	),
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'image',
	'settings' => 'image_loading_effect',
	'label'    => esc_html__('Image', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['image_loading_effect'],
	'active_callback' => array(
		array(
			'setting'  => 'type_loading_effect',
			'operator' => '=',
			'value'    => 'image',
		),
	),
]);
