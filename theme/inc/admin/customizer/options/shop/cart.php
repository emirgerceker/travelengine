<?php
// Cart
\Togo\Kirki::add_section('cart', array(
    'title'    => esc_html__('Cart', 'togo'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'cart_customize',
    'label'    => esc_html__('Holding Time', 'togo'),
    'section'  => 'cart',
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'radio-buttonset',
    'settings' => 'enable_holding_time',
    'label'    => esc_html__('Enable', 'togo'),
    'section'  => 'cart',
    'priority' => $priority++,
    'default'  => '0',
    'choices'  => array(
        '0' => esc_attr__('No', 'togo'),
        '1' => esc_attr__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => 'holding_time',
    'label'     => esc_attr__('Time', 'togo'),
    'section'   => 'cart',
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => 900,
    'choices'   => array(
        'min'  => 5,
        'max'  => 7200,
        'step' => 5,
    ),
    'active_callback' => [
        [
            'setting'  => 'enable_holding_time',
            'operator' => '==',
            'value'    => '1',
        ],
    ],
]);
