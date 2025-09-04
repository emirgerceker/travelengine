<?php

/**
 * Template part for displaying a destination.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$settings = $args['settings'] ?? null;
$item = $args['item'] ?? null;

echo '<div class="modern-carousel-item">';
echo '<div class="modern-carousel-item-inner">';
echo '<div class="modern-carousel-item-image">';
echo \Togo_Image::get_elementor_attachment([
    'settings'      => $item,
    'size_settings' => $settings,
]);
echo '</div>';
echo '<div class="modern-carousel-item-content">';
echo '<h6 class="modern-carousel-item-subtitle">' . esc_html($item['subtitle']) . '</h6>';
echo '<h2 class="modern-carousel-item-title">' . esc_html($item['heading']) . '</h2>';
echo '<div class="modern-carousel-item-text wysiwyg">' . wp_kses_post($item['description']) . '</div>';
echo '<a href="' . esc_url($item['button_link']['url']) . '" class="togo-button line">' . esc_html($item['button_text']) . '</a>';
echo '</div>';
echo '</div>';
echo '</div>';
