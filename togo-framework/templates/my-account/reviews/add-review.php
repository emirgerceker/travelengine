<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get the current user ID.
$current_user = wp_get_current_user();
if (!$current_user || !isset($current_user->ID)) {
    return; // User not found or not logged in.
}

// Get the user ID.
$user_id = $current_user->ID;

// Get current page URL without query parameters.
$current_url = home_url($_SERVER['REQUEST_URI']);

// Remove all query parameter from the URL.
$current_url = remove_query_arg(array_keys($_GET), $current_url);

// Add the "success" query parameter to the URL.
$current_url = add_query_arg('success', 'true', $current_url);

// Check if the 'new' parameter is set and not empty.
if (!isset($_GET['new']) || empty($_GET['new'])) {
    return;
}

$trip_id = intval($_GET['new']);
$trip_name = get_the_title($trip_id);
$single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
$single_trip_max_star = \Togo\Helper::setting('single_trip_max_star') ? \Togo\Helper::setting('single_trip_max_star') : 5;
?>
<div class="add-review-wrapper">
    <h3><?php echo esc_html__('Leave a review for', 'togo-framework') . ' ' . esc_html($trip_name); ?></h3>
    <form action="" method="post" multipart="multipart/form-data" class="my-review-form add-review-form">
        <?php
        if ($single_trip_reviews) {
            foreach ($single_trip_reviews as $key => $value) {
                echo '<div class="form-group rating-group">';
                echo '<label for="trip_reviews_' . $key . '">' . esc_html($value['text']) . '</label>';
                echo '<div class="rating">';
                for ($i = 0; $i < $single_trip_max_star; $i++) {
                    echo \Togo\Icon::get_svg('star');
                }
                echo '</div>';
                echo '<input type="hidden" name="trip_reviews[' . $key . ']" id="trip_reviews_' . $key . '" min="0" max="' . $single_trip_max_star . '" required>';
                echo '</div>';
            }
        }
        ?>
        <div class="form-group">
            <label for="trip_reviews_content"><?php echo esc_html__('Review', 'togo-framework'); ?></label>
            <textarea name="trip_reviews_content" id="trip_reviews_content" rows="5" placeholder="<?php echo esc_html__('Write your review here', 'togo-framework'); ?>" required></textarea>
        </div>
        <div class="form-group">
            <div class="label"><?php echo esc_html__('Images', 'togo-framework'); ?></div>
            <div class="review-gallery">
                <div id="image-preview"></div>
                <label for="review_images" class="upload-button">
                    <input type="file" name="review_images[]" id="review_images" accept="image/*" multiple>
                    <?php echo \Togo\Icon::get_svg('upload', 'upload-icon'); ?>
                    <?php echo \Togo\Icon::get_svg('spinner-one', 'loading-icon'); ?>
                </label>
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="trip_id" value="<?php echo esc_attr($trip_id); ?>">
            <input type="hidden" name="redirect" value="<?php echo esc_url($current_url); ?>">
            <button type="submit" class="togo-button full-filled"><?php echo esc_html__('Submit', 'togo-framework'); ?>
                <?php echo \Togo\Icon::get_svg('spinner-one', 'loading-icon'); ?>
            </button>
        </div>
    </form>
</div>