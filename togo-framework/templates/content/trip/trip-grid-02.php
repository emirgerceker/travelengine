<?php

/**
 * Template part for displaying a trip.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$trip_id = isset($trip_id) ? $trip_id : get_the_ID();
$trip_card_enable_slider_thumbnail = apply_filters('togo_trip_card_enable_slider_thumbnail', \Togo\Helper::setting('trip_card_enable_slider_thumbnail'));
$image_size = isset($image_size) ? $image_size : \Togo\Helper::setting('trip_card_image_size', '600x400');
?>
<article class="type-trip togo-column type-trip-grid-02">
    <div class="trip-inner">
        <?php echo \Togo_Framework\Template::render_trip_wishlist($trip_id); ?>
        <?php
        if ($trip_card_enable_slider_thumbnail === 'yes') {
            echo \Togo_Framework\Template::render_trip_slider_thumbnails($trip_id, $image_size);
        } else {
            echo \Togo_Framework\Template::render_trip_thumbnails($trip_id, $image_size);
        }
        ?>
        <div class="trip-content">
            <?php echo \Togo_Framework\Template::render_trip_meta($trip_id); ?>
            <?php echo \Togo_Framework\Template::render_trip_title($trip_id); ?>
            <div class="trip-footer">
                <?php echo \Togo_Framework\Template::render_trip_price($trip_id); ?>
                <?php echo \Togo_Framework\Template::render_trip_map_icon($trip_id); ?>
            </div>
        </div>
    </div>
</article>