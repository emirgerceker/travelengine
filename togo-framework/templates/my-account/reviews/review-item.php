<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get the current user ID.
$current_user = wp_get_current_user();
if (!$current_user || !isset($current_user->ID)) {
    return; // User not found or not logged in.
}

$tour_id = $args['tour'];

if (empty($tour)) {
    return; // No items found in the order.
}

// Get the current page URL.
$current_url = home_url($_SERVER['REQUEST_URI']);
// Add query parameter to the URL.
$new_review_url = add_query_arg('new', $tour_id, get_permalink($current_url));

// Get the setting value.
$single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
$single_trip_max_star = \Togo\Helper::setting('single_trip_max_star') ? \Togo\Helper::setting('single_trip_max_star') : 5;

$product_link = get_permalink($tour_id);
$args_review = array(
    'post_type' => 'togo_review',
    'posts_per_page' => 1,
    'post_status' => array('publish', 'pending'),
    'author' => $current_user->ID,
    'meta_query' => array(
        array(
            'key' => 'review_trip_id',
            'value' => $tour_id,
            'compare' => '=',
        )
    )
);

$trip_reviews = get_posts($args_review);

if ($trip_reviews) {
    // Remove all query parameter from the URL.
    $current_url = remove_query_arg(array_keys($_GET), get_permalink($current_url));
    $edit_review_url = add_query_arg('edit', $trip_reviews[0]->ID, $current_url);
} else {
    $edit_review_url = '#';
}

echo '<div class="review-item">';
echo '<div class="review-item__top">';
echo sprintf(
    '<h3 class="review-item__title"><span class="review-item__label">%s:</span><a href="%s" target="_blank">%s %s</a></h3>',
    esc_html__('In tour', 'togo-framework'),
    esc_url($product_link),
    esc_html(get_the_title($tour_id)),
    \Togo\Icon::get_svg('external-link')
);
if ($trip_reviews) {
    $trip_review_status = get_post_status($trip_reviews[0]->ID);
    if ($trip_review_status === 'pending') {
        echo '<span class="review-item__status pending">' . esc_html__('Waiting for approval', 'togo-framework') . '</span>';
    }
    if ($trip_review_status === 'publish') {
        echo '<span class="review-item__status published">' . esc_html__('Published', 'togo-framework') . '</span>';
    }
}
echo '</div>';
echo '<div class="review-item__content">';
if ($trip_reviews) {
    $percent = 0;
    $review_id = $trip_reviews[0]->ID;
    $review_content = $trip_reviews[0]->post_content;
    $review_date = date('M d, Y', strtotime($trip_reviews[0]->post_date));
    $trip_reviews_images = get_post_meta($review_id, 'trip_reviews_images', true);
    $overall = 0;
    foreach ($single_trip_reviews as $key => $value) {
        $value = get_post_meta($review_id, 'trip_reviews_' . $key, true);
        $overall += intval($value);
    }
    $star = $overall / count($single_trip_reviews);
    if ($star) {
        $percent = $star / $single_trip_max_star * 100;
    }
    echo '<div class="review-item__rating">';
    for ($i = 1; $i <= $single_trip_max_star; $i++) {
        echo \Togo\Icon::get_svg('star');
    }
    echo '<span class="current-stars" style="width: ' . $percent . '%">';
    for ($i = 1; $i <= $single_trip_max_star; $i++) {
        echo \Togo\Icon::get_svg('star');
    }
    echo '</span>';
    echo '</div>';
    if ($review_content) {
        echo '<div class="review-item__description">';
        echo '<p>' . esc_html($review_content) . '</p>';
        echo '</div>';
    }
    if ($trip_reviews_images) {
        $trip_reviews_images = explode('|', $trip_reviews_images);
        echo '<div class="review-item__images">';
        foreach ($trip_reviews_images as $image) {
            echo '<div class="review-item__image">';
            echo wp_get_attachment_image($image, 'thumbnail');
            echo '</div>';
        }
        echo '</div>';
    }
    echo '<div class="review-item__meta">';
    if ($review_date) {
        echo sprintf(
            '<div class="review-item__date">%s %s</div>',
            esc_html__('On', 'togo-framework'),
            esc_html($review_date)
        );
    }
    echo '<div class="review-item__actions">';
    echo sprintf(
        '<a href="%s" class="edit-review">%s %s</a>',
        esc_url($edit_review_url),
        \Togo\Icon::get_svg('pencil'),
        esc_html__('Edit', 'togo-framework')
    );
    echo sprintf(
        '<a href="#delete-review" data-review-id="%s" class="delete-review togo-open-modal">%s %s</a>',
        esc_attr($trip_reviews[0]->ID),
        \Togo\Icon::get_svg('trash'),
        esc_html__('Delete', 'togo-framework')
    );
    echo '</div>';
    echo '</div>';
} else {
    echo sprintf(
        '<a href="%s" class="leave-review">%s %s</a>',
        esc_url($new_review_url),
        \Togo\Icon::get_svg('pencil'),
        esc_html__('Leave a review', 'togo-framework')
    );
}
echo '</div>';
echo '</div>';
