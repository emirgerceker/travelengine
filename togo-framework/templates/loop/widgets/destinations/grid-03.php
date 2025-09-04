<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$destination = $args['destination'] ?? null;
$show_count = $args['show_count'] ?? false;
$layout = $args['layout'] ?? '1';

$destination_slug = $destination['destination'];
$destination_thumbnail = $destination['thumbnail'];

$image_size = isset($image_size) ? $image_size : \Togo\Helper::setting('trip_card_image_size', '600x400');

$trip_card_image_size = $image_size ? $image_size : \Togo\Helper::setting('trip_card_image_size');
if ($trip_card_image_size) {
    $trip_card_image_size = explode('x', $trip_card_image_size);
    $width = isset($trip_card_image_size[0]) ? intval($trip_card_image_size[0]) : 600;
    $height = isset($trip_card_image_size[1]) ? intval($trip_card_image_size[1]) : 450;
} else {
    $width = 600;
    $height = 450;
}

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

    global $wpdb;

    $getThumbnaiId = $wpdb->get_row( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid = %s LIMIT 1",
        $thumbnail_url
    ));
    $thumbnaiId = $getThumbnaiId ? $getThumbnaiId->ID : 0;

    if ($thumbnaiId) {
        $thumbnail_url = \Togo_Image::get_attachment_url_by_id(array('id' => $thumbnaiId, 'size' => 'custom', 'width' => $width, 'height' => $height, 'details' => false));
    }

    echo '<div class="togo-destination-grid-item togo-destination-' . esc_attr($layout) . '">';
    echo '<div class="togo-destination-grid-item-inner">';
    if ($thumbnail_url) {
        echo '<div class="togo-destination-grid-image">';
        echo '<a href="' . $term_link . '">';
        echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($term->name) . '">';
        echo '</a>';
        echo '</div>';
        echo '<svg width="0" height="0"><defs><clipPath id="shape-clipCurve" clipPathUnits="objectBoundingBox"><path d="M0,0 H1 Q0.88,0.5 1,1 H0 Z" /></clipPath></defs></svg>';
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
    echo '</div>';
}
