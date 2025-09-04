<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if the user is logged in before proceeding.
if (!is_user_logged_in()) {
    return;
}

// Get the current user ID.
$current_user = wp_get_current_user();
if (!$current_user || !isset($current_user->ID)) {
    return; // User not found or not logged in.
}
// Get the user ID.
$user_id = $current_user->ID;

// Get the reviews for the current user.
// You can adjust the arguments as needed to fetch the reviews you want.
$args = array(
    'customer' => $user_id,
    'posts_per_page' => 10,
    'status' => array('wc-completed'), // Adjust the status as needed.
);

// Fetch the orders using the WooCommerce function.
$orders = wc_get_orders($args);

$tours = [];

// Check if there are any orders.
if (empty($orders)) {
    echo '<div class="template-empty">';
    echo '<h5>' . __('No tours booked...yet!', 'togo-framework') . '</h5>';
    echo '<a href="' . esc_url(home_url('/')) . '" class="togo-button full-filled">' . __('Book a Tour', 'togo-framework') . '</a>';
    echo '</div>';
} else {
    foreach ($orders as $order) {
        $item = $order->get_items();
        if (!empty($item)) {
            foreach ($item as $key => $value) {
                $product_id = $value->get_product_id();
                if ($product_id) {
                    array_push($tours, $product_id);
                }
            }
        }
    }
}

// Pagination setup
$posts_per_page = get_option('posts_per_page');
$items_per_page = apply_filters('togo_framework_reviews_per_page', $posts_per_page);
$total_items = count($tours);
$current_page = max(1, get_query_var('paged', 1));
$total_pages = ceil($total_items / $items_per_page);

// Slice the tours array for the current page
$tours_to_display = array_slice($tours, ($current_page - 1) * $items_per_page, $items_per_page);
?>
<div class="my-account-wrapper">
    <?php
    if (isset($_GET['new']) && $_GET['new'] != '') {
        \Togo_Framework\Helper::togo_get_template('my-account/reviews/add-review.php');
    } else if (isset($_GET['edit']) && $_GET['edit'] != '') {
        \Togo_Framework\Helper::togo_get_template('my-account/reviews/edit-review.php');
    } else if (isset($_GET['success']) && $_GET['success'] === 'true') {
        \Togo_Framework\Helper::togo_get_template('my-account/reviews/review-thankyou.php');
    } else {
    ?>
        <div class="review-items">
            <?php
            // Loop through the tours for the current page and display the reviews.
            foreach ($tours_to_display as $tour) {
                \Togo_Framework\Helper::togo_get_template('my-account/reviews/review-item.php', array(
                    'tour' => $tour,
                ));
            }
            ?>
        </div>
        <?php
        if ($total_pages > 1) {
            echo '<div class="togo-pagination">';
            echo paginate_links(array(
                'base' => add_query_arg('paged', '%#%'),
                'format' => '?paged=%#%',
                'total' => $total_pages,
                'prev_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
                'next_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
                'type'      => 'list',  // Display as a list.
            ));
            echo '</div>';
        }
        ?>
    <?php } ?>
</div>
<div class="togo-modal togo-modal-delete-review" id="delete-review">
    <div class="togo-modal-overlay"></div>
    <div class="togo-modal-content">
        <div class="togo-modal-header">
            <h3 class="togo-modal-title"><?php echo esc_html__('Delete Review', 'togo-framework'); ?></h3>
            <div class="togo-modal-close"><?php echo \Togo\Icon::get_svg('x'); ?></div>
        </div>
        <div class="togo-modal-body">
            <p><?php echo esc_html__('Are you sure you want to delete this review?', 'togo-framework'); ?></p>
        </div>
        <div class="togo-modal-footer">
            <input type="hidden" name="review_id" value="">
            <a href="#" class="togo-modal-close togo-button line"><?php echo esc_html__('Cancel', 'togo-framework'); ?></a>
            <a href="#" class="togo-button full-filled action-delete-review">
                <?php echo esc_html__('Delete', 'togo-framework'); ?>
                <?php echo \Togo\Icon::get_svg('spinner-one', 'loading-icon'); ?></a>
        </div>
    </div>
</div>