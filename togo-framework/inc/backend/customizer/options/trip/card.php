<?php
$section = 'trip_card';
// Trip Card
\Togo\Kirki::add_section('trip_card', array(
    'title'    => esc_html__('Trip Card', 'togo-framework'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'trip_card_layout_notice',
    'label'    => esc_html__('Layout', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'trip_card_layout',
    'label'    => esc_html__('Layout', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 'grid',
    'choices'  => [
        'grid'  => esc_html__('Grid', 'togo'),
        'list'  => esc_html__('List', 'togo'),
    ],
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'trip_card_grid_style',
    'label'    => esc_html__('Grid Style', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '01',
    'choices'  => [
        '01' => esc_html__('01', 'togo'),
        '02' => esc_html__('02', 'togo'),
        '03' => esc_html__('03', 'togo'),
        '04' => esc_html__('04', 'togo'),
        '05' => esc_html__('05', 'togo'),
    ],
    'active_callback' => array(
        array(
            'setting'  => 'trip_card_layout',
            'operator' => '==',
            'value'    => 'grid',
        ),
    )
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'trip_card_image_size',
    'label'       => esc_html__('Image Size', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '600x400',
));


\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_slider_thumbnail',
    'label'       => esc_html__('Enable Slider Thumbnail', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'no',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'trip_card_layout',
            'operator' => '==',
            'value'    => 'grid',
        ),
        array(
            'setting'  => 'trip_card_grid_style',
            'operator' => '!=',
            'value'    => '05',
        ),
    )
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_wishlist',
    'label'       => esc_html__('Enable Wishlist', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_rating',
    'label'       => esc_html__('Enable Rating', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_location',
    'label'       => esc_html__('Enable Location', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_duration',
    'label'       => esc_html__('Enable Duration', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_guests',
    'label'       => esc_html__('Enable Guests', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_tour_type',
    'label'       => esc_html__('Enable Tour Type', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'trip_card_layout',
            'operator' => '==',
            'value'    => 'list',
        ),
    )
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_description',
    'label'       => esc_html__('Enable Description', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'trip_card_layout',
            'operator' => '==',
            'value'    => 'list',
        ),
    )
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_button',
    'label'       => esc_html__('Enable Button View Tour', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'trip_card_layout',
            'operator' => '==',
            'value'    => 'list',
        ),
    )
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'trip_card_enable_map',
    'label'       => esc_html__('Enable Map', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo'),
        'yes' => esc_html__('Yes', 'togo'),
    ),
));
