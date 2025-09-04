<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$activity = $args['activity'] ?? null;
$settings = $args['settings'] ?? null;

if (!$activity) {
    return;
}

$activity_slug = $activity['activity'];
$activity_thumbnail = $activity['thumbnail'];
$activity_thumbnail_url = $activity['thumbnail']['url'];
$activity_icon = $activity['icon'];
$activity_icon_url = $activity['icon']['url'];

// Get term by id
$term = get_term_by('slug', $activity_slug, 'togo_trip_activities');

if ($term) {
    $activity_id = $term->term_id;
    $term_link = get_term_link($activity_id, 'togo_trip_activities');
    $description = $term->description;
    echo '<div class="togo-activity-grid-item togo-activity-layout-2">';
    if ($activity_thumbnail_url) {
        echo '<div class="togo-activity-grid-image">';
        echo '<a href="' . $term_link . '">';
        echo \Togo_Image::get_elementor_attachment([
            'settings'      => $activity,
            'size_settings' => $settings,
            'image_key' => 'thumbnail',
            'image_size_key' => 'thumbnail',
        ]);
        echo '</a>';
        if ($activity_icon_url) {
            echo '<div class="togo-activity-grid-icon">';
            echo '<img src="' . esc_url($activity_icon_url) . '" alt="' . esc_attr($term->name) . '">';
            echo '</div>';
        }
        echo '</div>';
    }
    echo '<div class="togo-activity-grid-content">';
    echo '<h3><a href="' . $term_link . '">' . esc_attr($term->name) . '</a></h3>';
    echo '</div>';
    echo '</div>';
}
