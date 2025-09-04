<?php
$section  = 'button_underline';
$prefix   = 'button_underline_';

// Button
\Togo\Kirki::add_section($section, array(
    'title'    => esc_html__('Underline', 'togo'),
    'panel'    => $panel,
    'priority' => $priority++,
));

// Content
\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'notice_button',
    'label'    => esc_html__('Underline', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'      => 'multicolor',
    'settings'  => $prefix . 'color',
    'label'     => esc_html__('Color', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'choices'   => array(
        'normal' => esc_attr__('Normal', 'togo'),
        'hover'  => esc_attr__('Hover', 'togo'),
    ),
    'default'     => array(
        'normal' => $default[$prefix . 'color'],
        'hover'  => $default[$prefix . 'hover_color'],
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'      => 'multicolor',
    'settings'  => $prefix . 'border_color',
    'label'     => esc_html__('Border Color', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'choices'   => array(
        'normal' => esc_attr__('Normal', 'togo'),
        'hover'  => esc_attr__('Hover', 'togo'),
    ),
    'default'     => array(
        'normal' => $default[$prefix . 'border_color'],
        'hover'  => $default[$prefix . 'hover_border_color'],
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'      => 'spacing',
    'settings'  => $prefix . 'padding',
    'label'     => esc_attr__('Padding', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => array(
        'top'    => '0px',
        'right'  => '0px',
        'bottom' => '6px',
        'left'   => '0px',
    ),
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => $prefix . 'radius',
    'label'     => esc_attr__('Radius', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => $default[$prefix . 'radius'],
    'choices'   => array(
        'min'  => 0,
        'max'  => 50,
        'step' => 1,
    ),
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'select',
    'settings'  => $prefix . 'border',
    'label'     => esc_attr__('Border', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => $default[$prefix . 'border'],
    'choices'   => [
        'none'   => esc_attr__('None', 'togo'),
        'solid'  => esc_attr__('Solid', 'togo'),
        'double' => esc_attr__('Double', 'togo'),
        'dashed' => esc_attr__('Dashed', 'togo'),
        'groove' => esc_attr__('Groove', 'togo'),
    ],
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => $prefix . 'border_top',
    'label'     => esc_attr__('Border Top', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => 0,
    'choices'   => array(
        'min'  => 0,
        'max'  => 10,
        'step' => 1,
    ),
    'active_callback' => [
        [
            'setting'  => $prefix . 'border',
            'operator' => '!==',
            'value'    => 'none',
        ]
    ],
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => $prefix . 'border_right',
    'label'     => esc_attr__('Border Right', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => 0,
    'choices'   => array(
        'min'  => 0,
        'max'  => 10,
        'step' => 1,
    ),
    'active_callback' => [
        [
            'setting'  => $prefix . 'border',
            'operator' => '!==',
            'value'    => 'none',
        ]
    ],
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => $prefix . 'border_bottom',
    'label'     => esc_attr__('Border Bottom', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => 1,
    'choices'   => array(
        'min'  => 0,
        'max'  => 10,
        'step' => 1,
    ),
    'active_callback' => [
        [
            'setting'  => $prefix . 'border',
            'operator' => '!==',
            'value'    => 'none',
        ]
    ],
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => $prefix . 'border_left',
    'label'     => esc_attr__('Border Left', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => 0,
    'choices'   => array(
        'min'  => 0,
        'max'  => 10,
        'step' => 1,
    ),
    'active_callback' => [
        [
            'setting'  => $prefix . 'border',
            'operator' => '!==',
            'value'    => 'none',
        ]
    ],
]);
