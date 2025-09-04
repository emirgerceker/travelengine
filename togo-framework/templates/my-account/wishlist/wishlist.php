<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$columns = $args['columns'] ? $args['columns'] : 4;
$columns_tablet = isset($args['columns_tablet']) && !empty($args['columns_tablet']) ? $args['columns_tablet'] : 3;
$columns_mobile = isset($args['columns_mobile']) && !empty($args['columns_mobile']) ? $args['columns_mobile'] : 2;
$user_id = get_current_user_id();
$wishlist = get_user_meta($user_id, 'togo_wishlist', true);
if (!empty($wishlist)) {
    echo '<div class="my-wishlist">';
    echo '<div class="trip-list togo-row togo-row-cols-lg-' . $columns . ' togo-row-cols-md-' . $columns_tablet . ' togo-row-cols-xs-' . $columns_mobile . '">';
    foreach ($wishlist as $trip_id) {
        \Togo_Framework\Helper::togo_get_template('content-trip.php', array('trip_id' => $trip_id));
    }
    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="template-empty">';
    echo '<h5>' . __('No tours booked...yet!', 'togo-framework') . '</h5>';
    echo '<p>' . __('Select the heart icon to save the tours you liked.', 'togo-framework') . '</p>';
    echo '<a href="' . esc_url(home_url('/')) . '" class="togo-button full-filled">' . __('Find tours', 'togo-framework') . '</a>';
    echo '</div>';
}
