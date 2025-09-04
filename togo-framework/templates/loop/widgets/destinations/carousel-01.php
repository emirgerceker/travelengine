<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$destination = $args['destination'] ?? null;

$destination_slug = $destination['destination'];
$destination_thumbnail = $destination['thumbnail'];

// Count the number of posts by term ID
$term = get_term_by('slug', $destination_slug, 'togo_trip_destinations');

if ($term) {
    $post_count = $term->count;
    $destination_id = $term->term_id;
    $thumbnail = get_term_meta($destination_id, 'togo_trip_destinations_thumbnail', true) ?: [];
    $thumbnail_url = array_key_exists('url', $thumbnail) ? $thumbnail['url'] : '';

    if (!empty($destination_thumbnail['url'])) {
        $thumbnail_url = $destination_thumbnail['url'];
    }
    $term_link = get_term_link($destination_id, 'togo_trip_destinations');

    echo '<div class="togo-destination-item carousel-01" style="background-image: url(' . esc_url($thumbnail_url) . ')">';
    if ($term_link) {
        echo '<a href="' . $term_link . '">';
    }
    echo '<span>' . esc_attr($term->name) . '</span>';
    if ($term_link) {
        echo '</a>';
    }
    echo '</div>';
}
