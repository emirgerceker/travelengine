<?php

$panel    = 'trip';
$priority = 13;

// Trip Panel
\Togo\Kirki::add_panel($panel, array(
    'title'    => esc_html__('Trip', 'togo-framework'),
    'priority' => $priority++,
));
