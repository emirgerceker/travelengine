<?php

$section  = 'socials';
$prefix   = 'socials_';
$priority = 16;

// Socials Profile
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Socials', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_facebook',
	'label'    => esc_html__('Facebook', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_facebook'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_twitter',
	'label'    => esc_html__('Twitter', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_twitter'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_instagram',
	'label'    => esc_html__('Instagram', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_instagram'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_youtube',
	'label'    => esc_html__('Youtube', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_youtube'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_google_plus',
	'label'    => esc_html__('Google Plus', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_google_plus'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_skype',
	'label'    => esc_html__('Skype', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_skype'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_linkedin',
	'label'    => esc_html__('Linkedin', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_linkedin'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_pinterest',
	'label'    => esc_html__('Pinterest', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_pinterest'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_slack',
	'label'    => esc_html__('Slack', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_slack'],
]);

\Togo\Kirki::add_field('theme', [
	'type'     => 'text',
	'settings' => 'url_rss',
	'label'    => esc_html__('RSS', 'togo'),
	'section'  => $section,
	'priority' => $priority++,
	'default'  => $default['url_rss'],
]);
