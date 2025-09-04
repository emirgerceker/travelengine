<?php

$priority = 3;
$section  = 'color';

// Color
\Togo\Kirki::add_section($section, array(
	'title'    => esc_html__('Color', 'togo'),
	'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'primary_color',
	'label'     => esc_html__('Primary Color', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['primary_color'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'secondary_color',
	'label'     => esc_html__('Secondary Color', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['secondary_color'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'accent_color',
	'label'     => esc_html__('Accent Color', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['accent_color'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'text_color_01',
	'label'     => esc_html__('Text Color 01', 'togo'),
	'description' => esc_html__('Use for heading, sub text, link...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['text_color_01'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'text_color_02',
	'label'     => esc_html__('Text Color 02', 'togo'),
	'description' => esc_html__('Use for heading, sub text, link...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['text_color_02'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'text_color_03',
	'label'     => esc_html__('Text Color 03', 'togo'),
	'description' => esc_html__('Use for heading, sub text, link...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['text_color_03'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'text_color_04',
	'label'     => esc_html__('Text Color 04', 'togo'),
	'description' => esc_html__('Use for heading, sub text, link...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['text_color_04'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'text_color_05',
	'label'     => esc_html__('Text Color 05', 'togo'),
	'description' => esc_html__('Use for heading, sub text, link...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['text_color_05'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'text_color_06',
	'label'     => esc_html__('Text Color 06', 'togo'),
	'description' => esc_html__('Use for heading, sub text, link...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['text_color_06'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'border_color_01',
	'label'     => esc_html__('Border Color 01', 'togo'),
	'description' => esc_html__('Use for shadows, borders and lines...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['border_color_01'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'border_color_02',
	'label'     => esc_html__('Border Color 02', 'togo'),
	'description' => esc_html__('Use for shadows, borders and lines...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['border_color_02'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'border_color_03',
	'label'     => esc_html__('Border Color 03', 'togo'),
	'description' => esc_html__('Use for shadows, borders and lines...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['border_color_03'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'border_color_04',
	'label'     => esc_html__('Border Color 04', 'togo'),
	'description' => esc_html__('Use for shadows, borders and lines...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['border_color_04'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'border_color_05',
	'label'     => esc_html__('Border Color 05', 'togo'),
	'description' => esc_html__('Use for shadows, borders and lines...', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['border_color_05'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'icon_color_01',
	'label'     => esc_html__('Icon Color 01', 'togo'),
	'description' => esc_html__('Use for icons.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['icon_color_01'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'icon_color_02',
	'label'     => esc_html__('Icon Color 02', 'togo'),
	'description' => esc_html__('Use for icons.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['icon_color_02'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'icon_color_03',
	'label'     => esc_html__('Icon Color 03', 'togo'),
	'description' => esc_html__('Use for icons.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['icon_color_03'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'icon_color_04',
	'label'     => esc_html__('Icon Color 04', 'togo'),
	'description' => esc_html__('Use for icons.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['icon_color_04'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'icon_color_05',
	'label'     => esc_html__('Icon Color 05', 'togo'),
	'description' => esc_html__('Use for icons.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['icon_color_05'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'tone_color_01',
	'label'     => esc_html__('Tone 01', 'togo'),
	'description' => esc_html__('Use for alternative states.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['tone_color_01'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'tone_color_02',
	'label'     => esc_html__('Tone 02', 'togo'),
	'description' => esc_html__('Use for alternative states.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['tone_color_02'],
]);

\Togo\Kirki::add_field('theme', [
	'type'      => 'color-alpha',
	'settings'  => 'tone_color_03',
	'label'     => esc_html__('Tone 03', 'togo'),
	'description' => esc_html__('Use for alternative states.', 'togo'),
	'section'   => $section,
	'priority'  => $priority++,
	'transport' => 'auto',
	'default'   => $default['tone_color_03'],
]);
