<?php

$priority = 18;
$section  = 'site_advanced';

// Layout
\Togo\Kirki::add_section($section, array(
    'title'    => esc_attr__('Advanced', 'togo'),
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => $prefix . 'advanced',
    'label'    => esc_html__('Advanced', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'back_to_top',
    'label'       => esc_html__('Back To Top', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '0',
    'choices'     => array(
        '0' => esc_html__('OFF', 'togo'),
        '1' => esc_html__('ON', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'content_protected',
    'label'       => esc_html__('Content Protected', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '0',
    'choices'     => array(
        '0' => esc_html__('OFF', 'togo'),
        '1' => esc_html__('ON', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => $prefix . 'advanced_api',
    'label'    => esc_html__('API', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'text',
    'settings' => 'togo_google_map_api',
    'label'    => esc_html__('Google Map API', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '',
    'description' => esc_html__('This sets the Google Map API.', 'togo'),
));
