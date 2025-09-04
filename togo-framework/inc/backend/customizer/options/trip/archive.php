<?php
$section = 'archive_trip';
// General
\Togo\Kirki::add_section('archive_trip', array(
    'title'    => esc_html__('General', 'togo-framework'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_use_template_elementor',
    'label'       => esc_html__('Use Template Elementor (City Page)', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'no',
    'choices'     => array(
        'no' => esc_html__('No', 'togo-framework'),
        'yes' => esc_html__('Yes', 'togo-framework'),
    ),
));

// Archive trip
\Togo\Kirki::add_section('archive_trip', array(
    'title'    => esc_html__('Archive Trip', 'togo-framework'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'archive_trip_post_title',
    'label'    => esc_html__('Page title', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', [
    'type'      => 'slider',
    'settings'  => 'archive_trip_post_title_content_max_width',
    'label'     => esc_attr__('Content Max Width', 'togo'),
    'section'   => $section,
    'priority'  => $priority++,
    'default'   => '',
    'choices'   => [
        'min'  => 300,
        'max'  => 1920,
        'step' => 5,
    ],
    'output'    => array(
        array(
            'element'  => '.post-type-archive-togo_trip .page-title_inner .page-title_content, .tax-togo_trip_destinations .page-title_inner .page-title_content, .tax-togo_trip_activities .page-title_inner .page-title_content, .tax-togo_trip_types .page-title_inner .page-title_content, .tax-togo_trip_durations .page-title_inner .page-title_content, .tax-togo_trip_tod .page-title_inner .page-title_content, .tax-togo_trip_languages .page-title_inner .page-title_content',
            'property' => 'flex-basis',
            'units'    => 'px',
        ),
        array(
            'element'  => '.post-type-archive-togo_trip .page-title_inner .page-title_content, .tax-togo_trip_destinations .page-title_inner .page-title_content, .tax-togo_trip_activities .page-title_inner .page-title_content, .tax-togo_trip_types .page-title_inner .page-title_content, .tax-togo_trip_durations .page-title_inner .page-title_content, .tax-togo_trip_tod .page-title_inner .page-title_content, .tax-togo_trip_languages .page-title_inner .page-title_content',
            'property' => 'max-width',
            'units'    => 'px',
        ),
    ),
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_enable_page_title',
    'label'       => esc_html__('Page title', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '1',
    'choices'     => array(
        '0' => esc_html__('Hide', 'togo-framework'),
        '1' => esc_html__('Show', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_enable_breadcrumb',
    'label'       => esc_html__('Breadcrumb', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '0',
    'choices'     => array(
        '0' => esc_html__('Hide', 'togo-framework'),
        '1' => esc_html__('Show', 'togo-framework'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'archive_trip_enable_page_title',
            'operator' => '==',
            'value'    => '1',
        ),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'text',
    'settings' => 'archive_trip_post_title_text',
    'label'    => esc_html__('Heading', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => esc_html__('Trips', 'togo-framework'),
    'active_callback' => array(
        array(
            'setting'  => 'archive_trip_enable_page_title',
            'operator' => '==',
            'value'    => '1',
        ),
    ),
]);

\Togo\Kirki::add_field('theme', [
    'type'     => 'textarea',
    'settings' => 'archive_trip_post_title_description',
    'label'    => esc_html__('Description', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '',
]);

\Togo\Kirki::add_field('theme', [
    'type'     => 'image',
    'settings' => 'archive_trip_post_title_image',
    'label'    => esc_html__('Image', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '',
    'active_callback' => array(
        array(
            'setting'  => 'archive_trip_enable_page_title',
            'operator' => '==',
            'value'    => '1',
        ),
    ),
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_post_title_image_is_background',
    'label'       => esc_html__('Is Background', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '1',
    'choices'     => array(
        '0' => esc_html__('Hide', 'togo-framework'),
        '1' => esc_html__('Show', 'togo-framework'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'archive_trip_enable_page_title',
            'operator' => '==',
            'value'    => '1',
        ),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'      => 'color-alpha',
    'settings'  => 'archive_trip_post_title_background_color',
    'label'     => esc_html__('Background Color', 'togo-framework'),
    'section'   => $section,
    'priority'  => $priority++,
    'transport' => 'auto',
    'default'   => '',
    'output'    => array(
        array(
            'element'  => '.post-type-archive-togo_trip .page-title, .tax-togo_trip_destinations .page-title, .tax-togo_trip_activities .page-title, .tax-togo_trip_types .page-title, .tax-togo_trip_durations .page-title, .tax-togo_trip_tod .page-title, .tax-togo_trip_languages .page-title',
            'property' => 'background-color',
        ),
    ),
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'number',
    'settings' => 'archive_trip_post_title_padding_top',
    'label'    => esc_html__('Padding Top', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 80,
    'choices'  => [
        'min'  => 0,
        'max'  => 800,
        'step' => 1,
    ],
    'output'   => array(
        array(
            'element'  => '.post-type-archive-togo_trip .page-title, .tax-togo_trip_destinations .page-title, .tax-togo_trip_activities .page-title, .tax-togo_trip_types .page-title, .tax-togo_trip_durations .page-title, .tax-togo_trip_tod .page-title, .tax-togo_trip_languages .page-title',
            'property' => 'padding-top',
            'suffix'   => 'px',
        ),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'number',
    'settings' => 'archive_trip_post_title_padding_bottom',
    'label'    => esc_html__('Padding Bottom', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 80,
    'choices'  => [
        'min'  => 0,
        'max'  => 800,
        'step' => 1,
    ],
    'output'   => array(
        array(
            'element'  => '.post-type-archive-togo_trip .page-title, .tax-togo_trip_destinations .page-title, .tax-togo_trip_activities .page-title, .tax-togo_trip_types .page-title, .tax-togo_trip_durations .page-title, .tax-togo_trip_tod .page-title, .tax-togo_trip_languages .page-title',
            'property' => 'padding-bottom',
            'suffix'   => 'px',
        ),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'archive_trip_top_filters',
    'label'    => esc_html__('Top Filters', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'sortable',
    'settings'    => 'archive_trip_top_filter_order',
    'label'       => esc_attr__('Top Filter Order', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => array(
        'location',
        'date',
        'guest',
    ),
    'choices'     => array(
        'location' => esc_attr__('Location', 'togo-framework'),
        'date' => esc_attr__('Date', 'togo-framework'),
        'guest' => esc_attr__('Guest', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'archive_trip_layout',
    'label'    => esc_html__('Layout', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_columns_xl',
    'label'    => esc_html__('Columns (> 1500px)', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '4',
    'choices'  => [
        '1'  => esc_html__('Column 1'),
        '2'  => esc_html__('Column 2'),
        '3'  => esc_html__('Column 3'),
        '4'  => esc_html__('Column 4'),
        '5'  => esc_html__('Column 5'),
    ],
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_columns_lg',
    'label'    => esc_html__('Columns (> 1200px)', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '4',
    'choices'  => [
        '1'  => esc_html__('Column 1'),
        '2'  => esc_html__('Column 2'),
        '3'  => esc_html__('Column 3'),
        '4'  => esc_html__('Column 4'),
        '5'  => esc_html__('Column 5'),
    ],
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_columns_md',
    'label'    => esc_html__('Columns (> 992px)', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '3',
    'choices'  => [
        '1'  => esc_html__('Column 1'),
        '2'  => esc_html__('Column 2'),
        '3'  => esc_html__('Column 3'),
        '4'  => esc_html__('Column 4'),
        '5'  => esc_html__('Column 5'),
    ],
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_columns_sm',
    'label'    => esc_html__('Columns (> 767px)', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '2',
    'choices'  => [
        '1'  => esc_html__('Column 1'),
        '2'  => esc_html__('Column 2'),
        '3'  => esc_html__('Column 3'),
        '4'  => esc_html__('Column 4'),
        '5'  => esc_html__('Column 5'),
    ],
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_columns_xs',
    'label'    => esc_html__('Columns (> 320px)', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => '1',
    'choices'  => [
        '1'  => esc_html__('Column 1'),
        '2'  => esc_html__('Column 2'),
        '3'  => esc_html__('Column 3'),
        '4'  => esc_html__('Column 4'),
        '5'  => esc_html__('Column 5'),
    ],
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'archive_trip_maps',
    'label'    => esc_html__('Maps', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_enable_maps',
    'label'       => esc_html__('Enable Maps', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo-framework'),
        'yes' => esc_html__('Yes', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'archive_trip_filters',
    'label'    => esc_html__('Filters', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_filter_layout',
    'label'    => esc_html__('Layout', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 'left_filter',
    'choices'     => array(
        'left_filter' => esc_html__('Left Filter', 'togo-framework'),
        'top_filter' => esc_html__('Top Filter', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'     => 'number',
    'settings' => 'archive_trip_number_items_preview',
    'label'    => esc_html__('Number Items Preview', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 2,
    'choices'  => [
        'min'  => 0,
        'max'  => 5,
        'step' => 1,
    ],
    'active_callback' => array(
        array(
            'setting'  => 'archive_trip_filter_layout',
            'operator' => '==',
            'value'    => 'top_filter',
        ),
    )
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_filter_default',
    'label'       => esc_html__('Filter Content Default', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'open',
    'choices'     => array(
        'close' => esc_html__('Close', 'togo-framework'),
        'open' => esc_html__('Open', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_filter_open_first',
    'label'       => esc_html__('Open First Item', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => 'yes',
    'choices'     => array(
        'no' => esc_html__('No', 'togo-framework'),
        'yes' => esc_html__('Yes', 'togo-framework'),
    ),
    'active_callback' => array(
        array(
            'setting'  => 'archive_trip_filter_default',
            'operator' => '==',
            'value'    => 'close',
        ),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'sortable',
    'settings'    => 'archive_trip_filter_order',
    'label'       => esc_attr__('Order items', 'togo-framework'),
    'description' => esc_attr__('Controls the order of filter items.', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => array(
        'price',
        'types',
        'activities',
        'durations',
        'tod',
        'languages',
    ),
    'choices'     => array(
        'price' => esc_attr__('Price', 'togo-framework'),
        'types' => esc_attr__('Types', 'togo-framework'),
        'activities' => esc_attr__('Activities', 'togo-framework'),
        'durations' => esc_attr__('Durations', 'togo-framework'),
        'tod' => esc_attr__('Time Of Day', 'togo-framework'),
        'languages' => esc_attr__('Languages', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'archive_trip_pagination',
    'label'    => esc_html__('Pagination', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
]);

\Togo\Kirki::add_field('theme', array(
    'type'     => 'select',
    'settings' => 'archive_trip_pagination_align',
    'label'    => esc_html__('Align', 'togo-framework'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => 'left',
    'choices'     => array(
        'left' => esc_html__('Left', 'togo-framework'),
        'center' => esc_html__('Center', 'togo-framework'),
        'right' => esc_html__('Right', 'togo-framework'),
    ),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'archive_trip_pagination_show_info',
    'label'       => esc_html__('Show Pagination Info', 'togo-framework'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => '1',
    'choices'     => array(
        '0' => esc_html__('No', 'togo-framework'),
        '1' => esc_html__('Yes', 'togo-framework'),
    ),
));
