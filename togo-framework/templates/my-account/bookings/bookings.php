<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$show_filter = $args['show_filter'];
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
// Get current page number
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = $args['posts_per_page'] ? $args['posts_per_page'] : 10;
$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';

// Get the current page URL.
$current_url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
// Remove param status
$current_url = remove_query_arg('status', $current_url);

$args = array(
    'customer' => $user_id,
    'posts_per_page' => $posts_per_page,
    'paged' => $paged
);

if (!empty($status) && $status != 'all') {
    $args['status'] = $status;
}

$orders = wc_get_orders($args);

$total_orders = wc_get_orders(array(
    'customer' => $user_id,
    'status' => $status,
    'paginate' => true
));
$total_pages = ceil($total_orders->total / $posts_per_page);
echo '<div class="togo-my-bookings">';
if ($show_filter == 'yes') {
    echo '<ul class="filter-status">';
    echo '<li><a href="' . add_query_arg('status', 'all') . '" class="' . ($status == 'all' ? 'active' : '') . '">' . __('All', 'togo-framework') . '</a></li>';
    echo '<li><a href="' . add_query_arg('status', 'completed') . '" class="' . ($status == 'completed' ? 'active' : '') . '">' . __('Completed', 'togo-framework') . '</a></li>';
    echo '<li><a href="' . add_query_arg('status', 'pending') . '" class="' . ($status == 'pending' ? 'active' : '') . '">' . __('Pending', 'togo-framework') . '</a></li>';
    echo '<li><a href="' . add_query_arg('status', 'processing') . '" class="' . ($status == 'processing' ? 'active' : '') . '">' . __('Processing', 'togo-framework') . '</a></li>';
    echo '<li><a href="' . add_query_arg('status', 'on-hold') . '" class="' . ($status == 'on-hold' ? 'active' : '') . '">' . __('On Hold', 'togo-framework') . '</a></li>';
    echo '<li><a href="' . add_query_arg('status', 'cancelled') . '" class="' . ($status == 'cancelled' ? 'active' : '') . '">' . __('Cancelled', 'togo-framework') . '</a></li>';
    echo '</ul>';
}
if (!empty($orders)) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col">' . __('#ID', 'togo-framework') . '</th>';
    echo '<th scope="col">' . __('Title', 'togo-framework') . '</th>';
    echo '<th scope="col">' . __('Order Date', 'togo-framework') . '</th>';
    echo '<th scope="col">' . __('Price', 'togo-framework') . '</th>';
    echo '<th scope="col">' . __('Status', 'togo-framework') . '</th>';
    echo '<th scope="col"></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($orders as $order) {
        // Add query parameter to the URL.
        $view_order_url = add_query_arg('view', $order->get_id(), $current_url);
        $view_invoice_url = add_query_arg('invoice', $order->get_id(), $current_url);
        $item = $order->get_items();
        echo '<tr>';
        echo '<td data-label="' . __('#ID', 'togo-framework') . '" class="order-id">' . $order->get_id() . '</td>';
        echo '<td data-label="' . __('Title', 'togo-framework') . '" class="tour-info">';
        foreach ($item as $key => $value) {
            $product_id = $value->get_product_id();
            $product_image = get_the_post_thumbnail($product_id, 'thumbnail');
            echo '<p>';
            echo $product_image;
            echo '<span class="tour-name">' . $value->get_name() . '</span>';
            echo '</p>';
            break;
        }
        echo '</td>';
        echo '<td data-label="' . __('Order Date', 'togo-framework') . '">' . $order->get_date_created()->date('M d, Y') . '</td>';
        echo '<td data-label="' . __('Price', 'togo-framework') . '">' . wc_price($order->get_total()) . '</td>';
        echo '<td data-label="' . __('Status', 'togo-framework') . '"><span class="' . $order->get_status() . '">' . wc_get_order_status_name($order->get_status()) . '</span></td>';
        echo '<td>
        <a href="' . esc_url($view_order_url) . '" class="togo-button full-filled view-order">' . __('Detail', 'togo-framework') . '</a>
        <a href="' . esc_url($view_invoice_url) . '" class="togo-button line view-invoice">' . __('Invoice', 'togo-framework') . '</a>
        </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    // Pagination
    if ($total_pages > 1) {
        echo '<div class="togo-pagination">';
        echo paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $total_pages,
            'prev_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
            'next_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
            'type'      => 'list',  // Display as a list.
        ));
        echo '</div>';
    }
} else {
    echo '<div class="template-empty">';
    echo '<h5>' . __('No orders found.', 'togo-framework') . '</h5>';
    echo '<p>' . __('You have not made any orders yet.', 'togo-framework') . '</p>';
    echo '<a href="' . esc_url(home_url('/')) . '" class="togo-button full-filled">' . __('Find tours', 'togo-framework') . '</a>';
    echo '</div>';
}

echo '</div>';
