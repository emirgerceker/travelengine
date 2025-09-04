<?php

$section  = 'currency';
$priority = 13;

// Logo
\Togo\Kirki::add_section($section, array(
    'title'    => esc_html__('Currency', 'togo'),
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'select',
    'settings' => 'currency',
    'label'    => esc_html__('Currency', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 'USD',
    'description' => esc_html__('This controls what currency symbol is used.', 'togo'),
    'choices'  => \Togo_Framework\Helper::get_all_currency(),
]);

\Togo\Kirki::add_field('theme', [
    'type'     => 'select',
    'settings' => 'currency_position',
    'label'    => esc_html__('Currency Position', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 'left',
    'description' => esc_html__('This controls position of currency symbol.', 'togo'),
    'choices'  => [
        'left'  => esc_html__('Left', 'togo'),
        'right' => esc_html__('Right', 'togo'),
        'left_space'  => esc_html__('Left with Space', 'togo'),
        'right_space' => esc_html__('Right with Space', 'togo'),
    ],
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'text',
    'settings' => 'currency_thousand_separator',
    'label'    => esc_html__('Thousand Separator', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => ',',
    'description' => esc_html__('This sets the thousand separator of displayed price.', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'text',
    'settings' => 'currency_decimal_separator',
    'label'    => esc_html__('Decimal Separator', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '.',
    'description' => esc_html__('This sets the decimal separator of displayed price.', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'number',
    'settings' => 'currency_number_of_decimals',
    'label'    => esc_html__('Number of Decimals', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 2,
    'description' => esc_html__('This sets the number of decimals point shown in displayed price.', 'togo'),
    'choices'  => [
        'min'  => 0,
        'max'  => 10,
        'step' => 1,
    ],
));
