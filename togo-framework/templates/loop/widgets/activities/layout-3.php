<?php

/**
 * Template part for displaying a activity.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$activity = $args['activity'] ?? null;
$show_count = $args['show_count'] ?? false;

$activity_slug = $activity['activity'];
$activity_thumbnail = $activity['thumbnail'];

// Count the number of posts by term ID
$term = get_term_by('slug', $activity_slug, 'togo_trip_activities');

if ($term) {
    $post_count = $term->count;
    $activity_id = $term->term_id;
    $thumbnail = get_term_meta($activity_id, 'togo_trip_activities_thumbnail', true) ?: [];
    $thumbnail_url = array_key_exists('url', $thumbnail) ? $thumbnail['url'] : '';

    if (!empty($activity_thumbnail['url'])) {
        $thumbnail_url = $activity_thumbnail['url'];
    }
    $term_link = get_term_link($activity_id, 'togo_trip_activities');
    echo '<div class="togo-activity-grid-item togo-activity-layout-3">';
    echo '<div class="togo-activity-grid-item-inner">';
    if ($thumbnail_url) {
        echo '<div class="togo-activity-grid-image">';
        echo '<a href="' . $term_link . '">';
        echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($term->name) . '">';
        echo '</a>';
        echo '</div>';
    }
    echo '<div class="togo-activity-grid-content">';

    if ($show_count) {
        echo '<h3><a href="' . $term_link . '">' . esc_attr($term->name) . '</a> ';
        echo '<span class="togo-activity-grid-count-inline">';
        echo sprintf(
            '(%d)',
            esc_html($post_count)
        );
        echo '</span></h3>';
    } else {
        echo '<h3><a href="' . $term_link . '">' . esc_attr($term->name) . '</a></h3>';
        if ($show_count) {
            echo '<div class="togo-activity-grid-count">';
            echo sprintf(
                '<p>%d %s</p>',
                esc_html($post_count),
                esc_html(_n('trip', 'trips', $post_count, 'togo-framework'))
            );
            echo '</div>';
        }
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
}
