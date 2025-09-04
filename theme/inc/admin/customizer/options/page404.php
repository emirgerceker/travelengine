<?php
$priority = 10;
$section  = 'page404';
$prefix   = 'page404_';

// Notices
\Togo\Kirki::add_section($section, array(
	'title'    => esc_attr__('404 Page', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'background',
	'settings'    => 'page404_background_body',
	'label'       => esc_html__('Background', 'togo'),
	'description' => esc_html__('Controls outer background area in boxed mode.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => array(
		'background-color'      => '#fff',
		'background-image'      => '',
		'background-repeat'     => 'no-repeat',
		'background-size'       => 'cover',
		'background-attachment' => 'fixed',
		'background-position'   => 'center center',
	),
	'output'      => array(
		array(
			'element' => '.error404',
		),
	),
));

\Togo\Kirki::add_field('theme', array(
	'type'     => 'image',
	'settings' => 'page404_image',
	'label'    => esc_html__('Image', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => TOGO_IMAGES . '/page-404-image.png',
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'text',
	'settings'    => 'page404_title',
	'label'       => esc_html__('Title', 'togo'),
	'description' => esc_html__('Controls the title that display on error 404 page.', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__('Page not found!', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
	'type'        => 'editor',
	'settings'    => 'page404_text',
	'label'       => esc_html__('Text', 'togo'),
	'description' => esc_html__('Controls the text that display below title', 'togo'),
	'section'     => $section,
	'priority'    => $priority++,
	'default'     => esc_html__("This could be because of a typo, an out of date link, or that the page you requested doesn't exist.", 'togo'),
));
