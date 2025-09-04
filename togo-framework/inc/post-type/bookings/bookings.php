<?php

namespace Togo_Framework\Post_Type;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Bookings init
 *
 * @since 1.0.0
 */
class Bookings
{

    /**
     * Instance
     *
     * @var $instance
     */
    private static $instance;

    /**
     * Initiator
     *
     * @since 1.0.0
     * @return object
     */
    public static function instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Hook the post type and taxonomy registration to the 'init' action
        add_filter('uxper_register_post_type', array($this, 'register_post_type'));
        add_action('admin_menu', array($this, 'disable_add_new'), 999);
        add_action('admin_head', array($this, 'remove_add_new_button'));
        // Hook into WordPress actions and filters
        add_filter('manage_togo_booking_posts_columns', [$this, 'reorder_columns']);
        add_filter('manage_togo_booking_posts_columns', [$this, 'add_custom_column']);
        add_action('manage_togo_booking_posts_custom_column', [$this, 'populate_custom_column'], 10, 2);
        add_filter('manage_edit-togo_booking_sortable_columns', [$this, 'make_custom_column_sortable']);

        add_action('load-post.php', array($this, 'meta_boxes_setup'));
        add_action('load-post-new.php', array($this, 'meta_boxes_setup'));
    }

    /**
     * Register Post Type
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_type($post_types)
    {
        $post_types['togo_booking'] = array(
            'label'           => esc_html__('Bookings', 'togo-framework'),
            'singular_name'   => esc_html__('Bookings', 'togo-framework'),
            'supports'        => array('title'),
            'menu_icon'       => 'dashicons-calendar-alt',
            'can_export'      => true,
            'show_in_rest'    => false,
            'capability_type' => 'booking',
            'map_meta_cap'    => true,
            'menu_position'   => 4,
            'rewrite'         => array(
                'slug' => apply_filters('togo_booking_slug', 'togo_booking'),
            ),
        );

        return $post_types;
    }

    /**
     * Disable the 'Add New' link for the custom post type 'togo_booking'
     *
     * @since 1.0.0
     * @return void
     */
    public function disable_add_new()
    {
        // Replace 'togo_booking' with your custom post type slug
        global $submenu;
        unset($submenu['edit.php?post_type=togo_booking'][10]); // This removes the "Add New" link
    }

    public function remove_add_new_button()
    {
        $screen = get_current_screen();

        if (isset($screen->post_type) && $screen->post_type === 'togo_booking') {
?>
            <style>
                .page-title-action {
                    display: none !important;
                }
            </style>
        <?php
        }
    }

    public function reorder_columns($columns)
    {
        $new_columns = [
            'cb'            => $columns['cb'],
            'title'         => $columns['title'],
            'booking_date' => __('Booking Date', 'togo-framework'),
            'booking_time' => __('Booking Time', 'togo-framework'),
            'guests' => __('Guests', 'togo-framework'),
            'services' => __('Services', 'togo-framework'),
            'price' => __('Price', 'togo-framework'),
            'payment_method' => __('Payment Method', 'togo-framework'),
            'status' => __('Status', 'togo-framework'),
            'date'          => $columns['date'],
        ];

        return $new_columns;
    }

    /**
     * Add a custom column to the admin table
     */
    public function add_custom_column($columns)
    {
        // Insert the custom column after the title
        $columns['booking_date'] = __('Booking Date', 'togo-framework');
        $columns['booking_time'] = __('Booking Time', 'togo-framework');
        $columns['guests'] = __('Guests', 'togo-framework');
        $columns['services'] = __('Services', 'togo-framework');
        $columns['price'] = __('Price', 'togo-framework');
        $columns['payment_method'] = __('Payment Method', 'togo-framework');
        $columns['status'] = __('Status', 'togo-framework');
        return $columns;
    }

    /**
     * Populate the custom column with data
     */
    public function populate_custom_column($column, $post_id)
    {
        $order_id = get_post_meta($post_id, 'order_id', true);

        $order = wc_get_order($order_id);

        $product_id = 0;

        // Loop through order items
        if ($order && $order->get_items()) {
            foreach ($order->get_items() as $item_id => $item) {
                $product = $item->get_product(); // Get the product object
                if (!$product) {
                    continue; // Skip if product is not found
                }
                $product_id = $product->get_id(); // Get the product ID
                break;
            }
        }

        $reservation_data = get_post_meta($order_id, 'trip_order_reservation_data_' . $product_id, true);
        if (!empty($reservation_data) && $product_id != 0) {
            $booking_date = $reservation_data['booking_date'];
            $trip_id = $reservation_data['trip_id'];
            $pricing_type = $reservation_data['pricing_type'];
            $guests = $reservation_data['guests'];
            $time_type = $reservation_data['time_type'];
            $time = $reservation_data['time'];
            $opening_hours = $reservation_data['opening_hours'];
            $many_days_start_time = $reservation_data['many_days_start_time'];
            $services_without_price = $reservation_data['services_without_price'];
            $date_format = get_option('date_format');
            $pricing_categories = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');

            switch ($column) {
                case 'booking_date':
                    echo date($date_format, strtotime($booking_date));
                    break;
                case 'booking_time':
                    if ($time_type == 'start_times') {
                        echo \Togo_Framework\Helper::convert24To12($time);
                    } elseif ($time_type == 'opening_hours') {
                        echo esc_html__('Open at', 'togo-framework') . ' ' . $opening_hours;
                    } elseif ($time_type == 'many_days') {
                        echo esc_html__('Departure at', 'togo-framework') . ' ' . $many_days_start_time;
                    }
                    break;
                case 'guests':
                    if (!empty($pricing_categories) && $pricing_type == 'per_person') {
                        foreach ($pricing_categories as $key => $category) {
                            echo '<span class="value" style="display: block;">' . $guests[$key] . ' ' . esc_html($category->name) . '</span>';
                        }
                    } elseif (!empty($pricing_categories) && $pricing_type == 'per_group') {
                        echo '<span class="value" style="display: block;">' . sprintf(_n('%d guest', '%d guests', $guests[0], 'togo-framework'), $guests[0]) . '</span>';
                    }
                    break;
                case 'services':
                    if (!empty($services_without_price)) {
                        foreach ($services_without_price as $service) {
                            echo '<span class="value" style="display: block;">' . esc_html($service) . '</span>';
                        }
                    }
                    break;
                case 'price':
                    echo \Togo_Framework\Helper::togo_format_price($order->get_total());
                    break;
                case 'payment_method':
                    echo $order->get_payment_method_title();
                    break;
                case 'status':
                    echo '<span class="order-status status-' . $order->get_status() . '">' . wc_get_order_status_name($order->get_status()) . '</span>';
                    break;
            }
        }
    }

    /**
     * Make the custom column sortable
     */
    public function make_custom_column_sortable($columns)
    {
        $columns['booking_date'] = 'booking_date';
        $columns['booking_time'] = 'booking_time';
        $columns['guests'] = 'guests';
        $columns['services'] = 'services';
        $columns['price'] = 'price';
        $columns['payment_method'] = 'payment_method';
        $columns['status'] = 'status';
        return $columns;
    }

    /**
     * Meta boxes setup
     */
    public function meta_boxes_setup()
    {
        global $typenow;

        if ($typenow == 'togo_booking') {
            add_action('add_meta_boxes', array($this, 'render_booking_meta_boxes'));
            add_action('save_post', array($this, 'save_booking_metaboxes'), 10, 2);
        }
    }

    /**
     * Render booking meta boxes
     */
    public function render_booking_meta_boxes()
    {
        add_meta_box(
            'booking_trip_details',
            esc_html__('Booking Details', 'togo-framework'),
            array($this, 'booking_meta'),
            array('togo_booking'),
            'normal',
            'default'
        );
    }

    /**
     * Booking meta
     * @param $object
     */
    public function booking_meta($object)
    {
        $order_id = get_post_meta($object->ID, 'order_id', true);

        $order = wc_get_order($order_id);

        $product_id = 0;

        if ($order) {
            // Loop through order items
            foreach ($order->get_items() as $item_id => $item) {
                $product = $item->get_product(); // Get the product object
                if (!$product) {
                    continue; // Skip if product is not found
                }
                $product_id = $product->get_id(); // Get the product ID
                break;
            }
        }

        $reservation_data = get_post_meta($order_id, 'trip_order_reservation_data_' . $product_id, true);
        if (!empty($reservation_data) && $product_id != 0) {
            $booking_date = $reservation_data['booking_date'];
            $trip_id = $reservation_data['trip_id'];
            $pricing_type = $reservation_data['pricing_type'];
            $guests = $reservation_data['guests'];
            $time_type = $reservation_data['time_type'];
            $time = $reservation_data['time'];
            $opening_hours = $reservation_data['opening_hours'];
            $many_days_start_time = $reservation_data['many_days_start_time'];
            $services_without_price = $reservation_data['services_without_price'];
            $date_format = get_option('date_format');
            $pricing_categories = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
        ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php esc_html_e('Booking Time', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo date($date_format . ' H:i:s', strtotime($order->get_date_created())); ?></strong></td>
                        <th scope="row"><?php esc_html_e('Client Name', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Date of Trip', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo date($date_format, strtotime($booking_date)); ?></strong></td>
                        <th scope="row"><?php esc_html_e('Client Phone', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo $order->get_billing_phone(); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Time of Trip', 'togo-framework'); ?>:</th>
                        <td><strong>
                                <?php
                                if ($time_type == 'start_times') {
                                    \Togo_Framework\Helper::convert24To12($time);
                                } elseif ($time_type == 'opening_hours') {
                                    echo esc_html__('Open at', 'togo-framework') . ' ' . $opening_hours;
                                } elseif ($time_type == 'many_days') {
                                    echo esc_html__('Departure at', 'togo-framework') . ' ' . $many_days_start_time;
                                }
                                ?>
                            </strong></td>
                        <th scope="row"><?php esc_html_e('Client Email', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo $order->get_billing_email(); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Guests', 'togo-framework'); ?>:</th>
                        <td><strong>
                                <?php
                                if (!empty($pricing_categories) && $pricing_type == 'per_person') {
                                    foreach ($pricing_categories as $key => $category) {
                                        echo '<span class="value" style="display: block;">' . $guests[$key] . ' ' . esc_html($category->name) . '</span>';
                                    }
                                } elseif (!empty($pricing_categories) && $pricing_type == 'per_group') {
                                    echo sprintf(_n('%d guest', '%d guests', $guests[0], 'togo-framework'), $guests[0]);
                                }
                                ?>
                            </strong></td>
                    <tr>
                        <th scope="row"><?php esc_html_e('Services', 'togo-framework'); ?>:</th>
                        <td><strong>
                                <?php
                                if (!empty($services_without_price)) {
                                    foreach ($services_without_price as $service) {
                                        echo '<span class="value" style="display: block;">' . esc_html($service) . '</span>';
                                    }
                                }
                                ?>
                            </strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Price', 'togo-framework'); ?>:</th>
                        <td><strong>
                                <?php
                                echo \Togo_Framework\Helper::togo_format_price($order->get_total());
                                ?>
                            </strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Payment Method', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo $order->get_payment_method_title(); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Status', 'togo-framework'); ?>:</th>
                        <td><strong><?php echo '<span class="order-status status-' . $order->get_status() . '">' . wc_get_order_status_name($order->get_status()) . '</span>'; ?></strong></td>
                    </tr>
                </tbody>
            </table>
<?php
        }
    }
}
