<?php

// Single Product
\Togo\Kirki::add_section('single_product', array(
    'title'    => esc_html__('Product Single', 'togo'),
    'panel'    => $panel,
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'notice',
    'settings' => 'single_product_customize',
    'label'    => esc_html__('Single Product', 'togo'),
    'section'  => 'single_product',
    'priority' => $priority++,
]);
