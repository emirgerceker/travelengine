<?php

/**
 * Template part for displaying a trip.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$trip_id = isset($trip_id) ? $trip_id : get_the_ID();

$trip_card_layout = apply_filters('togo_trip_card_layout', \Togo\Helper::setting('trip_card_layout'));
$trip_card_grid_style = apply_filters('togo_trip_card_grid_style', \Togo\Helper::setting('trip_card_grid_style'));

if ($trip_card_layout == 'grid') {
    $layout = $trip_card_layout . '-' . $trip_card_grid_style;
} else {
    $layout = $trip_card_layout;
}

\Togo_Framework\Helper::togo_get_template('content/trip/trip-' . $layout . '.php', array('trip_id' => $trip_id));
