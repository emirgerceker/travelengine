<?php

$priority = 15;
$panel    = 'button';

// Blog Panel
\Togo\Kirki::add_panel($panel, array(
	'title'    => esc_html__('Button', 'togo'),
	'priority' => $priority++,
));
