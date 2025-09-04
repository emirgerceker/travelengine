<?php

$priority = 5;
$section  = 'header';
$prefix   = 'header_';

// Header
\Togo\Kirki::add_section($section, array(
    'title'    => esc_html__('Header', 'togo'),
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => $prefix . 'notice',
    'label'    => esc_html__('Header Customize', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'select',
    'settings'    => 'top_bar_type',
    'label'       => esc_html__('Top Bar', 'togo'),
    'description' => esc_html__('Select top bar that displays on site.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => $default[$prefix . 'type'],
    'choices'     => Togo\Theme::get_list_templates(true, 'togo_top_bar'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'select',
    'settings'    => $prefix . 'type',
    'label'       => esc_html__('Header Style', 'togo'),
    'description' => esc_html__('Select header style that displays on site.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => $default[$prefix . 'type'],
    'choices'     => Togo\Theme::get_list_templates(true, 'togo_header'),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'radio-buttonset',
    'settings' => $prefix . 'overlay',
    'label'    => esc_html__('Header Overlay', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => $default[$prefix . 'overlay'],
    'choices'  => array(
        '0' => esc_attr__('No', 'togo'),
        '1' => esc_attr__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'radio-buttonset',
    'settings' => $prefix . 'float',
    'label'    => esc_html__('Header Float', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => $default[$prefix . 'float'],
    'choices'  => array(
        '0' => esc_attr__('No', 'togo'),
        '1' => esc_attr__('Yes', 'togo'),
    ),
));
