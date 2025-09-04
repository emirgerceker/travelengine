<?php

// Single post
\Togo\Kirki::add_section('single_post', array(
    'title'    => esc_html__('Single Post', 'togo'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'single_post_customize',
    'label'    => esc_html__('Single Post', 'togo'),
    'section'  => 'single_post',
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
    'type'     => 'select',
    'settings' => 'single_post_style',
    'label'    => esc_html__('Style', 'togo'),
    'section'  => 'single_post',
    'priority' => $priority++,
    'default'  => $default['single_post_style'],
    'choices'  => [
        '01' => esc_html__('Style 01', 'togo'),
        '02' => esc_html__('Style 02', 'togo'),
    ],
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'radio-image',
    'settings'  => 'single_post_sidebar_position',
    'label'     => esc_html__('Sidebar Layout', 'togo'),
    'section'   => 'single_post',
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => $default['single_post_sidebar_position'],
    'choices'   => [
        'left'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/left-sidebar.png',
        'none'  => get_template_directory_uri() . '/inc/admin/customizer/assets/images/no-sidebar.png',
        'right' => get_template_directory_uri() . '/inc/admin/customizer/assets/images/right-sidebar.png',
    ],
    'active_callback' => [
        [
            'setting'  => 'single_post_style',
            'operator' => '==',
            'value'    => '01',
        ]
    ],
]);

\Togo\Kirki::add_field('theme', array(
    'type'            => 'select',
    'settings'        => 'single_post_active_sidebar',
    'label'           => esc_html__('Sidebar', 'togo'),
    'description'     => esc_html__('Select sidebar that will display on blog archive pages.', 'togo'),
    'section'         => 'single_post',
    'priority'        => $priority++,
    'default'         => $default['single_post_active_sidebar'],
    'choices'         => Togo\Helper::get_registered_sidebars(),
    'active_callback' => [
        [
            'setting'  => 'single_post_sidebar_position',
            'operator' => '!==',
            'value'    => 'none',
        ],
        [
            'setting'  => 'single_post_style',
            'operator' => '==',
            'value'    => '01',
        ]
    ],
));

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => 'single_post_sidebar_width',
    'label'     => esc_html__('Sidebar Width', 'togo'),
    'section'   => 'single_post',
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => $default['single_post_sidebar_width'],
    'choices'   => [
        'min'  => 270,
        'max'  => 420,
        'step' => 1,
    ],
    'output'    => array(
        array(
            'element'  => '#secondary.sidebar-single-post',
            'property' => 'flex-basis',
            'units'    => 'px',
            'media_query' => '@media (min-width: 992px)',
        ),
        array(
            'element'  => '#secondary.sidebar-single-post',
            'property' => 'max-width',
            'units'    => 'px',
            'media_query' => '@media (min-width: 992px)',
        ),
        array(
            'element'              => '.single-post #primary',
            'property'             => 'flex-basis',
            'value_pattern'       => 'calc( 100% - $px )',
            'media_query'         => '@media (min-width: 992px)',
        ),
        array(
            'element'              => '.single-post #primary',
            'property'             => 'max-width',
            'value_pattern'       => 'calc( 100% - $px )',
            'media_query'         => '@media (min-width: 992px)',
        ),
    ),
    'active_callback' => [
        [
            'setting'  => 'single_post_sidebar_position',
            'operator' => '!==',
            'value'    => 'none',
        ],
        [
            'setting'  => 'single_post_style',
            'operator' => '==',
            'value'    => '01',
        ]
    ],
]);

\Togo\Kirki::add_field('theme', [
    'type'     => 'select',
    'settings' => 'single_post_page_title_layout',
    'label'    => esc_html__('Page Title', 'togo'),
    'section'  => 'single_post',
    'priority' => $priority++,
    'default'  => $default['single_post_page_title_layout'],
    'choices'  => Togo_Page_Title::get_list(true),
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'radio-buttonset',
    'settings'  => 'single_post_share',
    'label'     => esc_html__('Share', 'togo'),
    'section'   => 'single_post',
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => $default['single_post_share'],
    'choices'   => array(
        'hide'   => esc_attr__('Hide', 'togo'),
        'show'   => esc_attr__('Show', 'togo'),
    ),
]);

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'single_post_header',
    'label'    => esc_attr__('Header', 'togo'),
    'section'  => 'single_post',
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'select',
    'settings'    => 'single_post_top_bar_type',
    'label'       => esc_html__('Top Bar Style', 'togo'),
    'description' => esc_html__('Select top_bar style that displays on blog archive pages.', 'togo'),
    'section'     => 'single_post',
    'priority'    => $priority++,
    'default'     => $default['single_post_top_bar_type'],
    'choices'     => Togo\Theme::get_list_templates(true, 'togo_top_bar'),
));


\Togo\Kirki::add_field('theme', array(
    'type'        => 'select',
    'settings'    => 'single_post_header_type',
    'label'       => esc_html__('Header Style', 'togo'),
    'description' => esc_html__('Select header style that displays on blog archive pages.', 'togo'),
    'section'     => 'single_post',
    'priority'    => $priority++,
    'default'     => $default['single_post_header_type'],
    'choices'     => Togo\Theme::get_list_templates(true, 'togo_header'),
));
