<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$destination_slug = $args['destination'] ?? null;
$settings = $args['settings'] ?? array();

if (!$destination_slug) {
    return;
}

// Get term by id
$term = get_term_by('slug', $destination_slug, 'togo_trip_destinations');
if (!$term) return;
$destination_id = $term->term_id;
$term_link = get_term_link($destination_id, 'togo_trip_destinations');
$no_image = get_template_directory_uri() . '/assets/images/no-image.jpg';
$thumbnail = get_term_meta($destination_id, 'togo_trip_destinations_thumbnail', true) ?: [];
$thumbnail_url = array_key_exists('url', $thumbnail) ? $thumbnail['url'] : $no_image;
if (!empty($settings['thumbnail']['url'])) {
    $thumbnail_url = $settings['thumbnail']['url'];
}
echo '<div class="togo-destination layout-01">';
echo '<a href="' . $term_link . '"></a>';
echo '<div class="togo-destination-thumbnail">';
echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($term->name) . '">';
echo '</div>';
echo '<div class="togo-destination-content">';
echo '<div class="togo-destination-meta">';
echo '<a href="' . $term_link . '"><span class="name">' . esc_attr($term->name) . '</span></a>';
if ($settings['show_number_of_trips']) {
    echo sprintf(
        '<span class="count">%s %s</span>',
        esc_html($term->count),
        esc_html(_n('tour', 'tours', $term->count, 'togo-framework'))
    );
}
echo '</div>';
echo '</div>';
echo '</div>';
