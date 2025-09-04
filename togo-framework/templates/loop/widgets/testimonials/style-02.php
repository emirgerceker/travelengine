<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$testimonial = $args['testimonial'] ?? null;
$testimonial_star = $testimonial['testimonial_star'];
$testimonial_heading = $testimonial['testimonial_heading'] ?? '';
$testimonial_text = $testimonial['testimonial_text'] ?? '';
$testimonial_author = $testimonial['testimonial_author'] ?? '';
$testimonial_position = $testimonial['testimonial_position'] ?? '';
$testimonial_image = $testimonial['testimonial_image'] ?? [];

echo '<div class="togo-testimonial-item togo-testimonial-style-02">';
if ($testimonial_star) {
    echo '<div class="togo-testimonial-star">';
    echo \Togo\Icon::get_svg('star');
    echo \Togo\Icon::get_svg('star');
    echo \Togo\Icon::get_svg('star');
    echo \Togo\Icon::get_svg('star');
    echo \Togo\Icon::get_svg('star');
    echo '<div class="togo-testimonial-star-default">';
    for ($i = 0; $i < intval($testimonial_star); $i++) {
        echo \Togo\Icon::get_svg('star');
    }
    echo '</div>';
    echo '</div>';
}
echo '<div class="togo-testimonial-content">';
if ($testimonial_heading) {
    echo '<h3 class="togo-testimonial-heading">' . esc_html($testimonial_heading) . '</h3>';
}
echo '<p>' . esc_html($testimonial_text) . '</p>';
echo '</div>';
echo '<div class="togo-testimonial-author">';
if (!empty($testimonial_image['url'])) {
    echo '<div class="togo-testimonial-image">';
    echo '<img src="' . esc_url($testimonial_image['url']) . '" alt="' . esc_attr($testimonial_author) . '">';
    echo '</div>';
}
echo '<div class="togo-testimonial-meta">';
echo '<span class="togo-testimonial-name">' . esc_html($testimonial_author) . '</span>';
echo '<span class="togo-testimonial-position">' . esc_html($testimonial_position) . '</span>';
echo '</div>';
echo '</div>';
echo '</div>';
