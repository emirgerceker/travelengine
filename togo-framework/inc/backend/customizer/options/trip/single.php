<?php
$section = 'single_trip';
// Single trip
\Togo\Kirki::add_section('single_trip', array(
    'title'    => esc_html__('Single Trip', 'togo-framework'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'single_trip_customize',
    'label'    => esc_html__('Review', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'enable_approve_review',
    'label'       => esc_html__('Review approval', 'togo-framework'),
    'description' => esc_html__('Enable review approval when user submit review', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'no',
    'choices'     => array(
        'no' => esc_html__('No', 'togo-framework'),
        'yes' => esc_html__('Yes', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'number',
    'settings' => 'single_trip_max_star',
    'label'    => esc_html__('Max Star', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '5',
    'choices'  => [
        'min'  => 1,
        'max'  => 15,
        'step' => 1,
    ],
));

\Togo\Kirki::add_field('theme', array(
    'type'         => 'repeater',
    'settings'     => 'single_trip_reviews',
    'section'      => $section,
    'priority'     => $priority++,
    'button_label' => esc_html__('Add New', 'togo-framework'),
    'row_label'    => array(
        'type'  => 'field',
        'field' => 'text',
    ),
    'default'      => array(
        array(
            'text' => esc_attr__('Guide', 'togo-framework'),
        ),
        array(
            'text' => esc_attr__('Service', 'togo-framework'),
        ),
        array(
            'text' => esc_attr__('Transportation', 'togo-framework'),
        ),
        array(
            'text' => esc_attr__('Organization', 'togo-framework'),
        ),
    ),
    'fields'       => array(
        'text'       => array(
            'type'        => 'text',
            'label'       => esc_attr__('Text', 'togo-framework'),
            'default'     => '',
        ),
    ),
));
