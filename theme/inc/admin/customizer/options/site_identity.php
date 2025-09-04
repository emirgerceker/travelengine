<?php

$section  = 'site_identity';
$priority = 1;

// Site Identity
\Togo\Kirki::add_section($section, array(
    'title'    => esc_html__('Site Identity', 'togo'),
    'priority' => $priority++,
));
