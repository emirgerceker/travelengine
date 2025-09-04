<?php

$panel    = 'blog';
$priority = 13;

// Blog Panel
\Togo\Kirki::add_panel($panel, array(
	'title'    => esc_html__('Blog', 'togo'),
	'priority' => $priority++,
));
