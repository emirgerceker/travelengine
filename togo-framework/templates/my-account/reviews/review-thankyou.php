<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get current page URL without query parameters.
$current_url = home_url($_SERVER['REQUEST_URI']);

// Remove all query parameter from the URL.
$current_url = remove_query_arg(array_keys($_GET), $current_url);

$enable_approve_review = \Togo\Helper::setting('enable_approve_review');
?>
<div class="review-thankyou">
    <?php
    if ($enable_approve_review == 'yes') {
        echo \Togo\Icon::get_svg('info-circle', 'pending-icon');
        echo '<h4>' . esc_html__('Your review is awaiting approval.', 'togo-framework') . '</h4>';
        echo '<p>' . esc_html__('We will notify you when your review is approved.', 'togo-framework') . '</p>';
        echo '<a href="' . esc_url($current_url) . '" class="togo-button full-filled">' . esc_html__('Go back to reviews', 'togo-framework') . '</a>';
    } else {
        echo \Togo\Icon::get_svg('check-circle', 'success-icon');
        echo '<h4>' . esc_html__('Your review has been submitted successfully.', 'togo-framework') . '</h4>';
        echo '<p>' . esc_html__('Thank you for your review.', 'togo-framework') . '</p>';
        echo '<a href="' . esc_url($current_url) . '" class="togo-button full-filled">' . esc_html__('Go back to reviews', 'togo-framework') . '</a>';
    }
    ?>
</div>