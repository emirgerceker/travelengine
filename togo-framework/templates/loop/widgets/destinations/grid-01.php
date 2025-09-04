<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$destination = $args['destination'] ?? null;
$show_count = $args['show_count'] ?? false;

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

    echo '<div class="togo-destination-grid-item">';
    if ($thumbnail_url) {
        echo '<div class="togo-destination-grid-image">';
        echo '<a href="' . $term_link . '">';
        echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($term->name) . '">';
        echo '</a>';
        echo '</div>';
    }
    echo '<div class="togo-destination-grid-content">';
    echo '<h3><a href="' . $term_link . '">' . esc_attr($term->name) . '</a></h3>';
    if ($show_count) {
        echo '<div class="togo-destination-grid-count">';
        echo sprintf(
            '<p>%d %s</p>',
            esc_html($post_count),
            esc_html(_n('trip', 'trips', $post_count, 'togo-framework'))
        );
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}
