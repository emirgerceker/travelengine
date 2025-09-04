<?php

$priority = 17;
$section  = 'social_sharing';

// Layout
\Togo\Kirki::add_section($section, array(
	'title'    => esc_attr__('Social Sharing', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'multicheck',
	'settings'    => 'social_sharing_item_enable',
	'label'       => esc_attr__('Sharing Links', 'togo'),
	'description' => esc_attr__('Check to the box to enable social share links.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array('facebook', 'twitter', 'linkedin', 'tumblr'),
	'choices'     => array(
		'facebook' => esc_attr__('Facebook', 'togo'),
		'twitter'  => esc_attr__('Twitter', 'togo'),
		'linkedin' => esc_attr__('Linkedin', 'togo'),
		'tumblr'   => esc_attr__('Tumblr', 'togo'),
		'email'    => esc_attr__('Email', 'togo'),
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'sortable',
	'settings'    => 'social_sharing_order',
	'label'       => esc_attr__('Order', 'togo'),
	'description' => esc_attr__('Controls the order of social share links.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'facebook',
		'twitter',
		'linkedin',
		'tumblr',
		'email',
	),
	'choices'     => array(
		'facebook' => esc_attr__('Facebook', 'togo'),
		'twitter'  => esc_attr__('Twitter', 'togo'),
		'linkedin' => esc_attr__('Linkedin', 'togo'),
		'tumblr'   => esc_attr__('Tumblr', 'togo'),
		'email'    => esc_attr__('Email', 'togo'),
	),
));
