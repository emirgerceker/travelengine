<?php

/**
 * Template part for displaying a trip.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

if (!defined('ABSPATH')) exit;

$trip_id = isset($trip_id) ? $trip_id : get_the_ID();
$image_size = isset($image_size) ? $image_size : \Togo\Helper::setting('trip_card_image_size', '600x400');
?>
<article class="type-trip togo-column type-trip-grid-05">
    <div class="trip-inner">
        <?php echo \Togo_Framework\Template::render_trip_wishlist($trip_id); ?>
        <?php echo \Togo_Framework\Template::render_trip_thumbnails($trip_id, $image_size); ?>
        <div class="trip-content">

            <?php echo \Togo_Framework\Template::render_trip_title($trip_id); ?>
            <div class="trip-footer">
                <?php echo \Togo_Framework\Template::render_trip_short_review($trip_id); ?>
                <?php echo \Togo_Framework\Template::render_trip_price($trip_id, true, false); ?>
            </div>
        </div>
    </div>
</article>