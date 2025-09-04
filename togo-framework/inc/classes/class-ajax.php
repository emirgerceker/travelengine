<?php

namespace Togo_Framework;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Ajax
 */
class Ajax
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

    public function __construct()
    {
        // Check availability
        add_action('wp_ajax_togo_check_availability', [$this, 'togo_check_availability']);
        add_action('wp_ajax_nopriv_togo_check_availability', [$this, 'togo_check_availability']);

        // Get cancel time
        add_action('wp_ajax_get_cancel_time', [$this, 'get_cancel_time']);
        add_action('wp_ajax_nopriv_get_cancel_time', [$this, 'get_cancel_time']);

        // Add to cart
        add_action('wp_ajax_trip_add_to_cart', [$this, 'trip_add_to_cart']);
        add_action('wp_ajax_nopriv_trip_add_to_cart', [$this, 'trip_add_to_cart']);

        // Book Now
        add_action('wp_ajax_trip_book_now', [$this, 'trip_book_now']);
        add_action('wp_ajax_nopriv_trip_book_now', [$this, 'trip_book_now']);

        // Handle the coupon application via AJAX
        add_action('wp_ajax_apply_custom_coupon', [$this, 'apply_custom_coupon']);
        add_action('wp_ajax_nopriv_apply_custom_coupon', [$this, 'apply_custom_coupon']);

        // Send Enquiry
        add_action('wp_ajax_togo_send_enquiry', [$this, 'togo_send_enquiry']);
        add_action('wp_ajax_nopriv_togo_send_enquiry', [$this, 'togo_send_enquiry']);

        // Add to wishlist
        add_action('wp_ajax_togo_add_to_wishlist', [$this, 'togo_add_to_wishlist']);
        add_action('wp_ajax_nopriv_togo_add_to_wishlist', [$this, 'togo_add_to_wishlist']);

        // Get Itinerary
        add_action('wp_ajax_togo_get_itinerary', [$this, 'event_get_itinerary']);
        add_action('wp_ajax_nopriv_togo_get_itinerary', [$this, 'event_get_itinerary']);

        // Upload images to gallery via AJAX
        add_action('wp_ajax_upload_images_to_gallery', [$this, 'handle_multiple_images_upload']);

        // Remove image from gallery via AJAX
        add_action('wp_ajax_remove_image_from_gallery', [$this, 'handle_remove_image_from_gallery']);

        // Add review
        add_action('wp_ajax_add_review', [$this, 'handle_add_review']);

        // Edit review
        add_action('wp_ajax_edit_review', [$this, 'handle_edit_review']);

        // Delete review
        add_action('wp_ajax_delete_review', [$this, 'handle_delete_review']);

        // Cancel booking
        add_action('wp_ajax_cancel_booking', [$this, 'handle_cancel_booking']);

        // Login
        add_action('wp_ajax_get_login_user', array($this, 'get_login_user'));
        add_action('wp_ajax_nopriv_get_login_user', array($this, 'get_login_user'));

        // Register
        add_action('wp_ajax_get_register_user', array($this, 'get_register_user'));
        add_action('wp_ajax_nopriv_get_register_user', array($this, 'get_register_user'));

        // Forgot password
        add_action('wp_ajax_togo_forgot_password_ajax', array($this, 'forgot_password_ajax'));
        add_action('wp_ajax_nopriv_togo_forgot_password_ajax', array($this, 'forgot_password_ajax'));

        // Reset password
        add_action('wp_ajax_change_password_ajax', array($this, 'change_password_ajax'));
        add_action('wp_ajax_nopriv_change_password_ajax', array($this, 'change_password_ajax'));
    }

    public function togo_check_availability()
    {
        // check nonce
        if (! wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'togo_check_availability')) {
            wp_die();
        }

        $trip_id = isset($_POST['trip_id']) ? sanitize_text_field($_POST['trip_id']) : '';
        $booking_date = isset($_POST['booking_date']) ? sanitize_text_field($_POST['booking_date']) : '';
        $guests = $_POST['guests'];

        $pricing_categories = wp_get_post_terms($trip_id, 'togo_trip_pricing_categories');
        $trip_pricing_type = get_post_meta($trip_id, 'trip_pricing_type', true);
        $trip_time = get_post_meta($trip_id, 'trip_time', true);
        $tour_package = get_post_meta($trip_id, 'tour_package', true);
        $trip_cancellation_time = get_post_meta($trip_id, 'trip_cancellation_time', true);
        $day_booking = \Togo_Framework\Helper::get_day_by_date($booking_date);

        if (empty($tour_package)) {
            return;
        }
        $package_details = [];
        $pricing_categories_slug = [];
        $html = '<div class="list-availability">';
        foreach ($tour_package as $package) {

            $package_detail = [];
            if (!empty($package['schedules'])) {
                foreach ($package['schedules'] as $schedule) {
                    $start_date = $schedule['start_date'];
                    $end_date = $schedule['end_date'];
                    if ($end_date == 'no_end_date') {
                        $end_date = date('Y-m-d', strtotime('+1 year', strtotime($start_date)));
                    }
                    $condition = false;
                    if (strtotime($booking_date) >= strtotime($start_date) && strtotime($booking_date) <= strtotime($end_date) && in_array(lcfirst($day_booking), $schedule['trip_days'])) {
                        $package_detail['package_name'] = $package['package_name'];
                        $package_detail['package_description'] = $package['package_description'];

                        if ($trip_time == 'start_times') {
                            $package_detail['trip_times'] = $schedule['trip_times'];
                        } elseif ($trip_time == 'opening_hours') {
                            $package_detail['opening_hours_days'] = $schedule['opening_hours_days'];
                            foreach ($schedule['opening_hours_days'] as $key => $value) {
                                $package_detail['opening_hours_' . $value . '_start'] = $schedule['opening_hours_' . $value . '_start'];
                                $package_detail['opening_hours_' . $value . '_end'] = $schedule['opening_hours_' . $value . '_end'];
                            }
                        } elseif ($trip_time == 'many_days') {
                            $package_detail['many_days_start_time'] = $schedule['many_days_start_time'];
                        }

                        if (!empty($pricing_categories) && $trip_pricing_type == 'per_person') {
                            $price = array();
                            if ($schedule['tiered_pricing'] == 'on') {
                                foreach ($pricing_categories as $key => $pricing_category) {
                                    $min_guests = $schedule['min_guests[' . $pricing_category->slug . ']'];
                                    $max_guests = $schedule['max_guests[' . $pricing_category->slug . ']'];
                                    $pricing_categories_slug[] = $pricing_category->slug;
                                    for ($i = 0; $i < count($min_guests); $i++) {
                                        if ($guests[$key] >= $min_guests[$i] && $guests[$key] <= $max_guests[$i]) {
                                            $price[] = $schedule['sale_price[' . $pricing_category->slug . ']'][$i] ? $schedule['sale_price[' . $pricing_category->slug . ']'][$i] : $schedule['regular_price[' . $pricing_category->slug . ']'][$i];
                                            $condition = true;
                                        }
                                    }
                                }
                            } else {
                                foreach ($pricing_categories as $key => $pricing_category) {
                                    $pricing_categories_slug[] = $pricing_category->slug;
                                    $price[] = $schedule['sale_price[' . $pricing_category->slug . ']'][0] ? $schedule['sale_price[' . $pricing_category->slug . ']'][0] : $schedule['regular_price[' . $pricing_category->slug . ']'][0];
                                    $condition = true;
                                }
                            }
                            $package_detail['price'] = $price;
                        } else {

                            if ($schedule['tiered_pricing'] == 'on') {
                                $per_group_min_guests = $schedule['per_group_min_guests'];
                                $per_group_max_guests = $schedule['per_group_max_guests'];
                                for ($i = 0; $i < count($per_group_min_guests); $i++) {
                                    if ($guests[0] >= $per_group_min_guests[$i] && $guests[0] <= $per_group_max_guests[$i]) {
                                        $price[] = $schedule['per_group_sale_price'][$i] ? $schedule['per_group_sale_price'][$i] : $schedule['per_group_regular_price'][$i];
                                        $package_detail['max_guest'] = $per_group_max_guests[$i];
                                        $condition = true;
                                    }
                                }
                            } else {
                                $price[] = $schedule['per_group_sale_price'][0] ? $schedule['per_group_sale_price'][0] : $schedule['per_group_regular_price'][0];
                                $condition = true;
                            }

                            $package_detail['price'] = $price;
                        }
                    }
                    if (!empty($package_detail) && $condition) {
                        $package_details[] = $package_detail;
                    }
                }
            }
        }

        if (!empty($package_details)) {
            foreach ($package_details as $key => $value) {
                if ($key == 0) {
                    $item_active = 'is-active';
                    $checked = 'checked';
                } else {
                    $item_active = '';
                    $checked = '';
                }

                $html .= '<div class="item-availability ' . $item_active . '">';
                $html .= '<div class="item-availability__radio">';
                $html .= '<label for="' . $value['package_name'] . '">';
                $html .= '<input type="radio" name="package" value="' . $value['package_name'] . '" id="' . $value['package_name'] . '" class="package" ' . $checked . '>';
                $html .= '<span></span>';
                $html .= '</label>';
                $html .= '</div>';
                $html .= '<div class="item-availability__info">';
                $html .= '<label for="' . $value['package_name'] . '">';
                $html .= $value['package_name'];
                $html .= '</label>';
                if ($value['package_description']) {
                    $html .= '<div class="item-availability__description">' . $value['package_description'] . '</div>';
                }
                $open_time = '0:00';
                $opening_hours = array();

                if (!empty($value['trip_times']) && $trip_time == 'start_times') {

                    $html .= '<div class="trip-times">';
                    foreach ($value['trip_times'] as $k => $v) {
                        if ($k == 0) {
                            $open_time = $v;
                        }

                        if ($v != '') {
                            if ($k == 0 && $item_active == 'is-active') {
                                $checked = 'checked';
                            } else {
                                $checked = '';
                            }
                            $html .= '<label for="' . $v . '-' . $key . '">';
                            $html .= '<input type="radio" name="trip_time" value="' . $v . '" id="' . $v . '-' . $key . '" class="trip-time" ' . $checked . ' data-booking-date="' . $booking_date . '" data-trip-id="' . $trip_id . '">';
                            $html .= '<span>' . \Togo_Framework\Helper::convert24To12($v) . '</span>';
                            $html .= '</label>';
                        }
                    }
                    $html .= '</div>';
                } elseif (!empty($value['opening_hours_days']) && $trip_time == 'opening_hours') {
                    $html .= '<div class="opening-hours">';
                    $day = lcfirst(\Togo_Framework\Helper::get_day_by_date($booking_date));
                    $html .= '<div class="opening-hours-item">';
                    $html .= '<div class="opening-hours__day">' . esc_html__('Opening hours', 'togo') . ':</div>';
                    $html .= '<div class="opening-hours__time-container">';

                    for ($i = 0; $i < count($value['opening_hours_' . $day . '_start']); $i++) {
                        if ($i == 0) {
                            $open_time = $value['opening_hours_' . $day . '_start'][$i];
                        }
                        if ($value['opening_hours_' . $day . '_start'][$i] != '' && $value['opening_hours_' . $day . '_end'][$i] != '') {
                            $html .= '<div class="opening-hours__time">' . \Togo_Framework\Helper::convert24To12($value['opening_hours_' . $day . '_start'][$i]) . ' - ' . \Togo_Framework\Helper::convert24To12($value['opening_hours_' . $day . '_end'][$i]) . '</div>';
                            $opening_hours[] = \Togo_Framework\Helper::convert24To12($value['opening_hours_' . $day . '_start'][$i]) . ' - ' . \Togo_Framework\Helper::convert24To12($value['opening_hours_' . $day . '_end'][$i]);
                        }
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<input type="hidden" name="opening_hours" value="' . implode(', ', $opening_hours) . '">';
                } elseif (!empty($value['many_days_start_time']) && $trip_time == 'many_days') {
                    $open_time = $value['many_days_start_time'];
                    $html .= '<div class="many-days">';
                    $html .= '<span class="many-days__text">' . esc_html__('Departure time', 'togo') . ':</span>';
                    $html .= '<span class="many-days__time">' . \Togo_Framework\Helper::convert24To12($value['many_days_start_time']) . '</span>';
                    $html .= '</div>';
                    $html .= '<input type="hidden" name="many_days_start_time" value="' . \Togo_Framework\Helper::convert24To12($value['many_days_start_time']) . '">';
                }



                $terms = wp_get_post_terms($trip_id, 'togo_trip_services');

                if (!is_wp_error($terms) && !empty($terms)) {
                    $html .= '<div class="trip-services">';
                    $html .= '<h6 class="trip-services__title">' . esc_html__('Extra Services', 'togo') . '</h6>';
                    foreach ($terms as $term) {
                        $term_price        = get_term_meta($term->term_id, 'togo_trip_services_price', true);
                        $term_suffix_price = get_term_meta($term->term_id, 'togo_trip_services_suffix_price', true);
                        $html .= '<div class="trip-service">';
                        $html .= '<div class="trip-service__input">';
                        $html .= '<label for="' . $term->slug . $key . '">';
                        $html .= '<input type="checkbox" name="trip_services[]" value="' . $term->slug . '" id="' . $term->slug . $key . '" class="trip-service__checkbox">';
                        $html .= '<span></span>';
                        $html .= '</label>';
                        $html .= '</div>';
                        $html .= '<div class="trip-service__label">';
                        $html .= '<div class="trip-service__name"><label for="' . $term->slug . $key . '">' . $term->name . '</label></div>';
                        $html .= '<div class="trip-service__description">' . $term->description . '</div>';
                        $html .= '</div>';
                        $html .= '<div class="trip-service__price">';
                        $html .= '<div class="trip-service__price-label">' . \Togo_Framework\Helper::togo_format_price($term_price) . '<span> /' . $term_suffix_price . '</span></div>';
                        $html .= '<div class="quantity">';
                        $html .= '<div class="quantity-input">';
                        $html .= '<span class="minus">-</span>';
                        $html .= '<span class="number"><input type="number" min="0" max="99999" value="0" name="service_quantity[]" data-price=' . $term_price . '></span>';
                        $html .= '<span class="plus">+</span>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }

                if ($trip_cancellation_time) {
                    $datetime = $booking_date . ' ' . $open_time;
                    $date = new \DateTime($datetime);
                    $date->modify('-' . $trip_cancellation_time . ' hours');
                    if ($date > new \DateTime()) {
                        $html .= '<div class="trip-cancellation-time-wrapper">';
                        $html .= '<div class="trip-cancellation-time">';
                        $html .= \Togo\Icon::get_svg('calendar-x');
                        $html .= '<p>' . sprintf(__('Cancel before <span class="time">%s</span> on <span class="date">%s</span> for a full refund', 'togo'), $date->format('g:i A'), $date->format('F j, Y')) . '</p>';
                        $html .= '</div>';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="trip-cancellation-time-wrapper">';
                        $html .= '<div class="trip-cancellation-time">';
                        $html .= \Togo\Icon::get_svg('calendar-x');
                        $html .= '<p>' . esc_html__('The free cancellation window has closed for this date.', 'togo') . '</p>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                }
                $html .= '</div>';

                if (!empty($value['price'])) {
                    $html .= '<div class="item-availability__price">';
                    $html .= '<ul>';
                    $total = 0;
                    if ($trip_pricing_type == 'per_person') {
                        foreach ($value['price'] as $key => $price) {
                            if (empty($price)) {
                                $price = 0;
                            }
                            $total += $price * $guests[$key];
                            $html .= '<li>';
                            $html .= '<span>' . \Togo_Framework\Helper::togo_format_price($price) . ' x ' . sprintf(_n('%s %s', '%s %ss', $guests[$key], 'togo'), $guests[$key], $pricing_categories_slug[$key]) . '</span>';
                            $html .= '</li>';
                        }
                    } else {
                        $total += $value['price'][count($value['price']) - 1];
                        $html .= '<li>';
                        if (array_key_exists('max_guest', $value)) {
                            $html .= '<span>' . sprintf(__('per group (up to %s)', 'togo'), $value['max_guest']) . '</span>';
                        }
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                    $html .= '<div class="total-price">' . \Togo_Framework\Helper::togo_format_price($total) . '</div>';
                    $html .= '<input type="hidden" name="trip_id" value="' . $trip_id . '">';
                    $html .= '<input type="hidden" name="booking_date" value="' . $booking_date . '">';
                    $html .= '<input type="hidden" name="total_price_without_service" value="' . $total . '">';
                    $html .= '<input type="hidden" name="guests_price" value="' . implode(',', $value['price']) . '">';
                    $html .= '<input type="hidden" name="total_price" value="' . $total . '">';
                    $html .= '<input type="hidden" name="pricing_type" value="' . $trip_pricing_type . '">';
                    $html .= '<input type="hidden" name="time_type" value="' . $trip_time . '">';
                    $html .= '<input type="hidden" name="nonce_checkout" value="' . wp_create_nonce('trip_book_now') . '">';
                    $html .= '<input type="hidden" name="nonce_cart" value="' . wp_create_nonce('trip_add_to_cart') . '">';
                    $html .= '<a class="togo-button full-filled book-now" href="#">' . esc_html__('Book Now', 'togo') . '</a>';
                    $html .= '<form action="#" method="post" class="add-to-cart-form">';
                    $html .= '<input type="hidden" name="add-to-cart" value="' . $trip_id . '">';
                    $html .= '<input type="hidden" name="quantity" value="1">';
                    $html .= '<input type="hidden" name="checkout" value="1">';
                    $html .= '</form>';
                    $html .= '<a class="togo-button line add-to-cart" href="#">' . esc_html__('Add to Cart', 'togo') . '</a>';
                    $html .= '<form action="#" method="post" class="add-to-cart-form">';
                    $html .= '<input type="hidden" name="add-to-cart" value="' . $trip_id . '">';
                    $html .= '<input type="hidden" name="quantity" value="1">';
                    $html .= '</form>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
        } else {
            $html .= '<div class="item-availability no-availability">';
            $html .= '<p>' . esc_html__('No availability found.', 'togo') . '</p>';
            $html .= '</div>';
        }

        $html .= '</div>';

        $response = array(
            'success' => true,
            'html' => $html
        );

        echo json_encode($response);

        wp_die();
    }

    public function get_cancel_time()
    {
        $trip_time = sanitize_text_field($_POST['trip_time']);
        $booking_date = sanitize_text_field($_POST['booking_date']);
        $trip_id = absint($_POST['trip_id']);
        $trip_cancellation_time = get_post_meta($trip_id, 'trip_cancellation_time', true);
        $html = '';

        if ($trip_cancellation_time) {
            try {
                $datetime = $booking_date . ' ' . $trip_time;
                $date = new \DateTime($datetime);
                $date->modify('-' . intval($trip_cancellation_time) . ' hours');

                $html = sprintf(
                    __('Cancel before <span class="time">%s</span> on <span class="date">%s</span> for a full refund', 'togo'),
                    $date->format('g:i A'),
                    $date->format('F j, Y')
                );
            } catch (Exception $e) {
                $response = array(
                    'success' => false,
                    'html' => __('Invalid date or time provided.', 'togo')
                );
                echo json_encode($response);
                wp_die();
            }
        }

        $response = array(
            'success' => true,
            'html' => $html
        );

        echo json_encode($response);
        wp_die();
    }

    public function trip_add_to_cart()
    {
        // Verify nonce
        if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'trip_add_to_cart')) {
            wp_die(__('Invalid request.', 'togo'));
        }

        // Ensure WC session
        if (is_null(WC()->session) || !WC()->session->get_customer_unique_id()) {
            WC()->session->init();
            WC()->session->set_customer_session_cookie(true);
        }

        // Sanitize inputs
        $trip_id              = absint($_POST['trip_id']);
        $package_name         = sanitize_text_field($_POST['package_name']);
        $booking_date         = sanitize_text_field($_POST['booking_date']);
        $guests               = sanitize_text_field($_POST['guests']);
        $guests_price         = sanitize_text_field($_POST['guests_price']);
        $total_price          = floatval($_POST['total_price']);
        $pricing_type         = sanitize_text_field($_POST['pricing_type']);
        $time_type            = sanitize_text_field($_POST['time_type']);
        $time                 = sanitize_text_field($_POST['time']);
        $opening_hours        = sanitize_text_field($_POST['opening_hours']);
        $many_days_start_time = sanitize_text_field($_POST['many_days_start_time']);
        $service_quantity     = is_array($_POST['service_quantity']) ? $_POST['service_quantity'] : [];

        // Get trip services
        $services              = \Togo_Framework\Helper::get_terms_by_post_id($trip_id, 'togo_trip_services');
        $services_with_price   = [];
        $services_without_price = [];
        $services_total_price  = [];

        if (!empty($services)) {
            foreach ($services as $key => $service) {
                $quantity = isset($service_quantity[$key]) ? absint($service_quantity[$key]) : 0;

                if ($quantity > 0) {
                    $service_name = $service->name;
                    $term_price   = floatval(get_term_meta($service->term_id, 'togo_trip_services_price', true));

                    $services_with_price[]   = \Togo_Framework\Helper::togo_format_price($term_price) . ' x ' . $quantity . ' ' . $service_name;
                    $services_without_price[] = $quantity . ' ' . $service_name;
                    $services_total_price[]  = \Togo_Framework\Helper::togo_format_price($term_price * $quantity);
                }
            }
        }

        // Set holding time (default 1 hour)
        $enable_holding_time = \Togo\Helper::setting('enable_holding_time');
        $holding_time        = intval(\Togo\Helper::setting('holding_time'));
        $schedule_time       = time() + ($enable_holding_time ? $holding_time : 3600);

        // Create reservation info
        $reservation_info = [
            'package_name'           => $package_name,
            'booking_date'           => $booking_date,
            'trip_id'                => $trip_id,
            'guests_price'           => $guests_price,
            'total_price'            => $total_price,
            'pricing_type'           => $pricing_type,
            'guests'                 => $guests,
            'time_type'              => $time_type,
            'time'                   => $time,
            'opening_hours'          => $opening_hours,
            'many_days_start_time'   => $many_days_start_time,
            'services_with_price'    => $services_with_price,
            'services_without_price' => $services_without_price,
            'services_total_price'   => $services_total_price,
            'schedule_time'          => $schedule_time,
        ];

        // Clear previous reservation and cache
        \Togo_Framework\Helper::delete_transient_woo_booking($trip_id);
        \Togo_Framework\Helper::set_transient_woo_booking($trip_id, $reservation_info);

        if (wp_using_ext_object_cache()) {
            \Togo_Framework\Helper::delete_cache_woo_booking($trip_id);
            \Togo_Framework\Helper::set_cache_woo_booking($trip_id, $reservation_info);
        }

        // Response
        wp_send_json_success(['message' => __('Trip added to cart successfully.', 'togo')]);
    }


    public function trip_book_now()
    {
        // check nonce
        if (! wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'trip_book_now')) {
            wp_die();
        }

        if (is_null(WC()->session) || !WC()->session->get_customer_unique_id()) {
            WC()->session->init();
            WC()->session->set_customer_session_cookie(true);
        }

        $package_name = sanitize_text_field($_POST['package_name']);
        $booking_date = sanitize_text_field($_POST['booking_date']);
        $service_quantity = array_map('intval', $_POST['service_quantity']);
        $guests = $_POST['guests'];
        $trip_id = absint($_POST['trip_id']);
        $guests_price = $_POST['guests_price'];
        $total_price = floatval($_POST['total_price']);
        $pricing_type = sanitize_text_field($_POST['pricing_type']);
        $time_type = sanitize_text_field($_POST['time_type']);
        $time = sanitize_text_field($_POST['time']);
        $opening_hours = sanitize_text_field($_POST['opening_hours']);
        $many_days_start_time = sanitize_text_field($_POST['many_days_start_time']);

        $services = \Togo_Framework\Helper::get_terms_by_post_id($trip_id, 'togo_trip_services');
        $services_with_price = array();
        $services_without_price = array();
        $services_total_price = array();
        if (!empty($services)) {
            foreach ($services as $key => $service) {
                if ($service_quantity[$key] > 0) {
                    $service_name = $service->name;
                    $term_price = get_term_meta($service->term_id, 'togo_trip_services_price', true);
                    $services_with_price[] = \Togo_Framework\Helper::togo_format_price($term_price) . ' x ' . $service_quantity[$key] . ' ' . $service_name;
                    $services_without_price[] = $service_quantity[$key] . ' ' . $service_name;
                    $services_total_price[] = \Togo_Framework\Helper::togo_format_price($term_price * $service_quantity[$key]);
                }
            }
        }

        $enable_holding_time = \Togo\Helper::setting('enable_holding_time');
        $holding_time = \Togo\Helper::setting('holding_time');
        if ($enable_holding_time) {
            $schedule_time = $holding_time;
        } else {
            $schedule_time = 3600;
        }

        $reservation_info = array(
            'package_name' => $package_name,
            'booking_date' => $booking_date,
            'trip_id' => $trip_id,
            'guests_price' => $guests_price,
            'total_price' => $total_price,
            'pricing_type' => $pricing_type,
            'guests' => $guests,
            'time_type' => $time_type,
            'time' => $time,
            'opening_hours' => $opening_hours,
            'many_days_start_time' => $many_days_start_time,
            'services_with_price' => $services_with_price,
            'services_without_price' => $services_without_price,
            'services_total_price' => $services_total_price,
            'schedule_time' => time() + intval($schedule_time)
        );

        // Clear room reservation data if exists
        \Togo_Framework\Helper::delete_transient_woo_booking($trip_id);

        \Togo_Framework\Helper::set_transient_woo_booking($trip_id, $reservation_info);

        if (wp_using_ext_object_cache()) {
            \Togo_Framework\Helper::delete_cache_woo_booking($trip_id);
            \Togo_Framework\Helper::set_cache_woo_booking($trip_id, $reservation_info);
        }

        // Verify data was set
        $verify_data = \Togo_Framework\Helper::get_transient_woo_booking($trip_id);

        $response['success'] = true;

        echo json_encode($response);

        wp_die();
    }

    public function apply_custom_coupon()
    {
        // 1. Verify nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'apply_coupon_nonce')) {
            wp_send_json_error(['title' => 'Error', 'message' => esc_html__('Security check failed.', 'togo')]);
        }

        // 2. Check if coupon code is provided
        if (empty($_POST['coupon_code'])) {
            wp_send_json_error(['title' => 'Error', 'message' => esc_html__('Coupon code is required.', 'togo')]);
        }

        // 3. Sanitize input
        $coupon_code = sanitize_text_field(trim($_POST['coupon_code']));

        // 4. Apply coupon
        WC()->cart->apply_coupon($coupon_code);

        if (WC()->cart->has_discount($coupon_code)) {
            wp_send_json_success([
                'title' => 'Success',
                'message' => esc_html__('Coupon applied successfully.', 'togo'),
            ]);
        } else {
            $errors = wc_get_notices('error');
            $message = !empty($errors) ? strip_tags($errors[0]['notice']) : esc_html__('Coupon code is invalid.', 'togo');
            wp_send_json_error([
                'title' => 'Error',
                'message' => $message,
            ]);
        }

        wp_die(); // Terminate AJAX properly
    }

    public function togo_send_enquiry()
    {
        // 1. Ensure request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            wp_send_json_error(['message' => esc_html__('Invalid request method.', 'togo')]);
        }

        // 2. Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        if (!wp_verify_nonce($nonce, 'togo_send_enquiry')) {
            wp_send_json_error(['message' => esc_html__('Security check failed.', 'togo')]);
        }

        // 3. Collect data
        $trip_id = isset($_POST['trip_id']) ? absint($_POST['trip_id']) : 0;
        $list_name = isset($_POST['list_name']) ? sanitize_text_field($_POST['list_name']) : '';

        if (!$trip_id || get_post_type($trip_id) !== 'togo_trip') {
            wp_send_json_error(['message' => esc_html__('Invalid trip ID.', 'togo')]);
        }

        $subject = esc_html__('New enquiry', 'togo');
        $messages = '<p>' . esc_html__('Dear Admin,', 'togo') . '</p>';
        $messages .= '<p>' . esc_html__('You have received a new enquiry through your website. Here are the details:', 'togo') . '</p>';

        // 4. Dynamically render fields
        if (!empty($list_name)) {
            $fields = explode(',', $list_name);

            foreach ($fields as $field) {
                $field = trim($field);
                $field_label = ucfirst(str_replace('_', ' ', $field));

                if (!isset($_POST[$field])) continue;

                $value = $_POST[$field];

                if (is_array($value)) {
                    $field_value = implode(', ', array_map('sanitize_text_field', $value));
                } else {
                    $field_value = sanitize_text_field($value);
                }

                $messages .= '<p><strong>' . esc_html($field_label) . ':</strong> ' . esc_html($field_value) . '</p>';
            }
        }

        // 5. Trip details
        $trip_title = get_the_title($trip_id);
        $trip_link = get_the_permalink($trip_id);
        $messages .= '<p><strong>' . esc_html__('Trip name', 'togo') . ':</strong> <a href="' . esc_url($trip_link) . '">' . esc_html($trip_title) . '</a></p>';
        $messages .= '<p>' . esc_html__('Please respond to the customer at your earliest convenience.', 'togo') . '</p>';

        // 6. Send email
        $sent = \Togo_Framework\Helper::send_email(null, $subject, $messages);

        // 7. Return response
        if ($sent) {
            wp_send_json_success([
                'message' => esc_html__('Your enquiry has been sent successfully.', 'togo')
            ]);
        } else {
            wp_send_json_error([
                'message' => esc_html__('Failed to send enquiry. Please try again.', 'togo')
            ]);
        }
    }

    public function togo_add_to_wishlist()
    {
        // 1. Ensure POST method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            wp_send_json_error(['message' => esc_html__('Invalid request method.', 'togo')]);
        }

        // 2. Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        if (!wp_verify_nonce($nonce, 'togo_add_to_wishlist')) {
            wp_send_json_error(['message' => esc_html__('Security check failed.', 'togo')]);
        }

        // 3. Get and sanitize trip ID
        $trip_id = isset($_POST['trip_id']) ? absint($_POST['trip_id']) : 0;
        if (!$trip_id || get_post_type($trip_id) !== 'togo_trip') {
            wp_send_json_error(['message' => esc_html__('Invalid trip ID.', 'togo')]);
        }

        // 4. Check user login
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => esc_html__('Please login to add to wishlist.', 'togo')]);
        }

        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'togo_wishlist', true);

        if (!is_array($wishlist)) {
            $wishlist = [];
        }

        if (!in_array($trip_id, $wishlist)) {
            // Add to wishlist
            $wishlist[] = $trip_id;
            update_user_meta($user_id, 'togo_wishlist', $wishlist);
            wp_send_json_success([
                'add' => true,
                'message' => esc_html__('Added to wishlist', 'togo'),
            ]);
        } else {
            // Remove from wishlist
            $key = array_search($trip_id, $wishlist);
            if ($key !== false) {
                unset($wishlist[$key]);
                $wishlist = array_values($wishlist); // Reindex
                update_user_meta($user_id, 'togo_wishlist', $wishlist);
            }

            wp_send_json_success([
                'add' => false,
                'message' => esc_html__('Add to wishlist', 'togo'),
            ]);
        }
    }

    public function event_get_itinerary()
    {
        // 1. Verify nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'get_itinerary_nonce')) {
            wp_send_json_error(['message' => esc_html__('Security check failed.', 'togo')]);
        }

        // 2. Get and validate trip ID
        $trip_id = isset($_POST['trip_id']) ? absint($_POST['trip_id']) : 0;
        if (!$trip_id || get_post_type($trip_id) !== 'togo_trip') {
            wp_send_json_error(['message' => esc_html__('Invalid trip ID.', 'togo')]);
        }

        // 3. Get itinerary
        $trip_itinerary = get_post_meta($trip_id, 'trip_itinerary', true);

        if (empty($trip_itinerary) || !is_array($trip_itinerary) || empty($trip_itinerary[0]['trip_itinerary_title'])) {
            wp_send_json_error(['message' => esc_html__('No itinerary available.', 'togo')]);
        }

        // 4. Prepare addresses for map
        $addresses = array();
        foreach ($trip_itinerary as $item) {
            if (!empty($item['trip_itinerary_address']['location'])) {
                $addresses[] = $item['trip_itinerary_address']['location'];
            }
        }

        ob_start();
?>
        <div class="togo-modal togo-modal-itinerary is-active">
            <div class="togo-modal-overlay"></div>
            <div class="togo-modal-content">
                <div class="togo-st-tour-maps">
                    <div
                        class="togo-st-tour-maps-map"
                        id="togo-st-tour-maps-map"
                        data-coordinates='<?php echo esc_attr(json_encode($addresses)); ?>'
                        data-line-color='rgb(253,70,33)'
                        data-arrow-color='#fff'
                        data-arrow-speed='50'
                        data-map-zoom='9'></div>
                </div>
                <div class="togo-modal-inner">
                    <div class="togo-modal-header">
                        <div class="togo-modal-header-top">
                            <h3 class="togo-modal-title"><?php echo esc_html(get_the_title($trip_id)); ?></h3>
                            <div class="togo-modal-close"><?php echo \Togo\Icon::get_svg('x'); ?></div>
                        </div>
                        <div class="togo-modal-header-bottom">
                            <?php echo \Togo_Framework\Helper::get_price_of_trip($trip_id); ?>
                            <a href="<?php echo esc_url(get_the_permalink($trip_id)); ?>" class="togo-button full-filled">
                                <?php echo esc_html__('View tour', 'togo'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="togo-modal-body">
                        <h2><?php echo esc_html__('Itinerary', 'togo'); ?></h2>
                        <div class="togo-st-itinerary" id="togo-st-itinerary">
                            <?php foreach ($trip_itinerary as $key => $item):
                                $title = isset($item['trip_itinerary_title']) ? esc_html($item['trip_itinerary_title']) : '';
                                $content = isset($item['trip_itinerary_content']) ? wp_kses_post($item['trip_itinerary_content']) : '';
                            ?>
                                <div class="togo-st-itinerary-item">
                                    <h3 class="togo-st-itinerary-item-title">
                                        <?php
                                        if ($key == 0) {
                                            echo \Togo\Icon::get_svg('location', 'togo-st-itinerary-item-icon');
                                        } elseif ($key == count($trip_itinerary) - 1) {
                                            echo \Togo\Icon::get_svg('flag-one', 'togo-st-itinerary-item-icon');
                                        } else {
                                            echo '<span class="togo-st-itinerary-item-icon"></span>';
                                        }
                                        ?>
                                        <span class="togo-st-itinerary-item-text">
                                            <?php echo $title; ?>
                                            <?php echo \Togo\Icon::get_svg('chevron-down'); ?>
                                        </span>
                                    </h3>
                                    <div class="togo-st-itinerary-item-content"><?php echo $content; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php

        $html = ob_get_clean();

        wp_send_json_success(['html' => $html]);
    }

    function handle_multiple_images_upload()
    {
        // 1. Verify nonce for security
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'upload_images_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed.', 'togo-framework')]);
            wp_die();
        }

        // 2. Ensure user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => esc_html__('You must be logged in to upload images.', 'togo-framework')]);
            wp_die();
        }

        // 3. Check if files exist
        if (!isset($_FILES['file']) || empty($_FILES['file']['name'][0])) {
            wp_send_json_error(['message' => esc_html__('No files selected.', 'togo-framework')]);
            wp_die();
        }

        // 4. Allowed file types (optional, but safer)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        $uploaded_urls = [];

        foreach ($_FILES['file']['name'] as $key => $filename) {
            $file = [
                'name'     => sanitize_file_name($_FILES['file']['name'][$key]),
                'type'     => $_FILES['file']['type'][$key],
                'tmp_name' => $_FILES['file']['tmp_name'][$key],
                'error'    => $_FILES['file']['error'][$key],
                'size'     => $_FILES['file']['size'][$key],
            ];

            // 5. Validate file type
            if (!in_array($file['type'], $allowed_types)) {
                continue; // skip disallowed type
            }

            $upload_overrides = ['test_form' => false];

            // 6. Move file to uploads folder
            $movefile = wp_handle_upload($file, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $file_url = $movefile['url'];

                $attachment = [
                    'post_mime_type' => $movefile['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file['name'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                ];

                // 7. Insert attachment into media library
                $attach_id = wp_insert_attachment($attachment, $movefile['file']);
                if ($attach_id) {
                    require_once ABSPATH . 'wp-admin/includes/image.php';

                    $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $uploaded_urls[] = $file_url;
                }
            }
        }

        if (!empty($uploaded_urls)) {
            wp_send_json_success(['urls' => $uploaded_urls]);
        } else {
            wp_send_json_error(['message' => esc_html__('File upload failed.', 'togo-framework')]);
        }

        wp_die();
    }


    function handle_remove_image_from_gallery()
    {
        // 1. Verify nonce for CSRF protection
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'remove_images_nonce')) {
            wp_send_json_error([
                'message' => esc_html__('Nonce verification failed.', 'togo-framework')
            ]);
            wp_die();
        }

        // 2. Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error([
                'message' => esc_html__('You must be logged in to remove images.', 'togo-framework')
            ]);
            wp_die();
        }

        // 3. Sanitize and validate image URL
        $image_url = isset($_POST['image_url']) ? esc_url_raw($_POST['image_url']) : '';
        if (empty($image_url)) {
            wp_send_json_error([
                'message' => esc_html__('No image URL provided.', 'togo-framework')
            ]);
            wp_die();
        }

        // 4. Convert URL to attachment ID
        $attachment_id = attachment_url_to_postid($image_url);
        if (!$attachment_id) {
            wp_send_json_error([
                'message' => esc_html__('Image not found.', 'togo-framework')
            ]);
            wp_die();
        }

        // 5. Delete the attachment
        $deleted = wp_delete_attachment($attachment_id, true);

        if ($deleted) {
            wp_send_json_success([
                'message' => esc_html__('Image removed successfully.', 'togo-framework')
            ]);
        } else {
            wp_send_json_error([
                'message' => esc_html__('Failed to delete image.', 'togo-framework')
            ]);
        }

        wp_die();
    }

    public function handle_add_review()
    {
        // 1. Check nonce for CSRF protection
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'add_review_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed.', 'togo-framework')]);
            wp_die();
        }

        // 2. Check if user is logged in
        $current_user = wp_get_current_user();
        if (!$current_user || $current_user->ID === 0) {
            wp_send_json_error(['message' => esc_html__('User not logged in.', 'togo-framework')]);
            wp_die();
        }

        // 3. Sanitize and validate input
        $trip_reviews = isset($_POST['trip_reviews']) && is_array($_POST['trip_reviews']) ? array_map('intval', $_POST['trip_reviews']) : [];
        $trip_reviews_content = isset($_POST['trip_reviews_content']) ? sanitize_textarea_field($_POST['trip_reviews_content']) : '';
        $trip_id = isset($_POST['trip_id']) ? absint($_POST['trip_id']) : 0;
        $redirect = isset($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : '';
        $review_images = isset($_POST['review_images']) && is_array($_POST['review_images']) ? $_POST['review_images'] : [];

        if (!$trip_id || empty($trip_reviews_content)) {
            wp_send_json_error(['message' => esc_html__('Missing required fields.', 'togo-framework')]);
            wp_die();
        }

        // 4. Prepare title and status
        $user_name = $current_user->display_name;
        $trip_name = get_the_title($trip_id);
        $title = sprintf(esc_html__('Review for %s by %s', 'togo'), $trip_name, $user_name);
        $status = (\Togo\Helper::setting('enable_approve_review') === 'yes') ? 'pending' : 'publish';

        // 5. Insert review post
        $review_id = wp_insert_post([
            'post_title'   => $title,
            'post_content' => $trip_reviews_content,
            'post_status'  => $status,
            'post_type'    => 'togo_review',
            'post_author'  => $current_user->ID,
        ]);

        if ($review_id) {
            // 6. Save ratings
            foreach ($trip_reviews as $key => $value) {
                update_post_meta($review_id, 'trip_reviews_' . sanitize_key($key), $value);
            }

            // 7. Save trip reference
            update_post_meta($review_id, 'review_trip_id', $trip_id);

            // 8. Save image attachments
            if (!empty($review_images)) {
                $gallery_ids = [];

                foreach ($review_images as $img_url) {
                    $attachment_id = attachment_url_to_postid(esc_url_raw($img_url));
                    if ($attachment_id) {
                        $gallery_ids[] = $attachment_id;
                    }
                }

                if (!empty($gallery_ids)) {
                    update_post_meta($review_id, 'trip_reviews_images', implode('|', $gallery_ids));
                }
            }

            wp_send_json_success([
                'message'  => esc_html__('Review added successfully.', 'togo-framework'),
                'redirect' => $redirect
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Failed to add review.', 'togo-framework')]);
        }

        wp_die();
    }

    public function handle_edit_review()
    {
        // Verify the nonce for CSRF protection
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'edit_review_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed.', 'togo-framework')]);
            wp_die();
        }

        // Ensure user is logged in
        $current_user = wp_get_current_user();
        if (!$current_user || !isset($current_user->ID) || $current_user->ID === 0) {
            wp_send_json_error(['message' => esc_html__('User not logged in.', 'togo-framework')]);
            wp_die();
        }

        // Sanitize and validate inputs
        $trip_reviews = isset($_POST['trip_reviews']) ? array_map('intval', $_POST['trip_reviews']) : [];
        $redirect = isset($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : '';
        $trip_reviews_content = isset($_POST['trip_reviews_content']) ? sanitize_textarea_field($_POST['trip_reviews_content']) : '';
        $trip_id = isset($_POST['trip_id']) ? absint($_POST['trip_id']) : 0;
        $review_id = isset($_POST['review_id']) ? absint($_POST['review_id']) : 0;
        $review_images = isset($_POST['review_images']) && is_array($_POST['review_images']) ? $_POST['review_images'] : [];

        // Check post exists and belongs to current user
        $review_post = get_post($review_id);
        if (!$review_post || (int) $review_post->post_author !== $current_user->ID) {
            wp_send_json_error(['message' => esc_html__('You are not allowed to edit this review.', 'togo-framework')]);
            wp_die();
        }

        // Determine post status based on settings
        $enable_approve_review = \Togo\Helper::setting('enable_approve_review');
        $status = ($enable_approve_review === 'yes') ? 'pending' : 'publish';

        // Prepare post title
        $user_name = $current_user->display_name;
        $trip_name = get_the_title($trip_id);
        $title = sprintf(esc_html__('Review for %s by %s', 'togo'), $trip_name, $user_name);

        // Update the review post
        $updated_post_id = wp_update_post([
            'ID'           => $review_id,
            'post_title'   => $title,
            'post_content' => $trip_reviews_content,
            'post_status'  => $status,
        ]);

        if ($updated_post_id) {
            // Save individual ratings as post meta
            foreach ($trip_reviews as $key => $value) {
                update_post_meta($review_id, 'trip_reviews_' . sanitize_key($key), $value);
            }

            // Save associated trip ID
            update_post_meta($review_id, 'review_trip_id', $trip_id);

            // Process and save review image attachments
            if (!empty($review_images)) {
                $gallery_ids = [];

                foreach ($review_images as $image_url) {
                    $attachment_id = attachment_url_to_postid(esc_url_raw($image_url));
                    if ($attachment_id) {
                        $gallery_ids[] = $attachment_id;
                    }
                }

                update_post_meta($review_id, 'trip_reviews_images', implode('|', $gallery_ids));
            }

            wp_send_json_success([
                'message' => esc_html__('Review updated successfully.', 'togo-framework'),
                'redirect' => $redirect
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Failed to update review.', 'togo-framework')]);
        }

        wp_die();
    }

    public function handle_delete_review()
    {
        // Verify nonce for security
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'delete_review_nonce')) {
            wp_send_json_error(array('message' => esc_html__('Nonce verification failed.', 'togo-framework')));
            wp_die();
        }

        // Sanitize and validate review ID
        $review_id = isset($_POST['review_id']) ? absint($_POST['review_id']) : 0;

        if (!$review_id || get_post_type($review_id) !== 'togo_review') {
            wp_send_json_error(array('message' => esc_html__('Invalid review ID.', 'togo-framework')));
            wp_die();
        }

        // Get the review post object
        $review = get_post($review_id);

        if (!$review) {
            wp_send_json_error(array('message' => esc_html__('Review not found.', 'togo-framework')));
            wp_die();
        }

        // Optional: Check if current user is the author or has permission
        if ((int) $review->post_author !== get_current_user_id() && !current_user_can('moderate_comments')) {
            wp_send_json_error(array('message' => esc_html__('You do not have permission to delete this review.', 'togo-framework')));
            wp_die();
        }

        // Try deleting the review
        $deleted = wp_delete_post($review_id, true);

        if ($deleted) {
            wp_send_json_success(array('message' => esc_html__('Review deleted successfully.', 'togo-framework')));
        } else {
            wp_send_json_error(array('message' => esc_html__('Failed to delete review.', 'togo-framework')));
        }

        wp_die();
    }

    public function handle_cancel_booking()
    {
        // Verify nonce for security
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'cancel_booking_nonce')) {
            wp_send_json_error(array('message' => esc_html__('Nonce verification failed.', 'togo-framework')));
            wp_die();
        }

        // Sanitize booking ID
        $booking_id = isset($_POST['booking_id']) ? absint($_POST['booking_id']) : 0;

        if (!$booking_id) {
            wp_send_json_error(array('message' => esc_html__('Invalid booking ID.', 'togo-framework')));
            wp_die();
        }

        // Load the booking/order object
        $booking = wc_get_order($booking_id);

        // Validate that the booking exists and is an order
        if (!$booking || !is_a($booking, 'WC_Order')) {
            wp_send_json_error(array('message' => esc_html__('Booking not found.', 'togo-framework')));
            wp_die();
        }

        // Optional: Check if the current user has permission to cancel this booking
        if (get_current_user_id() !== $booking->get_user_id() && !current_user_can('manage_woocommerce')) {
            wp_send_json_error(array('message' => esc_html__('You are not allowed to cancel this booking.', 'togo-framework')));
            wp_die();
        }

        // Update booking status to 'cancelled'
        try {
            $booking->update_status('cancelled', __('Booking cancelled by user.', 'togo-framework'));

            wp_send_json_success(array('message' => esc_html__('Booking cancelled successfully.', 'togo-framework')));
        } catch (Exception $e) {
            wp_send_json_error(array('message' => esc_html__('Failed to cancel booking.', 'togo-framework')));
        }

        wp_die();
    }

    //////////////////////////////////////////////////////////////////
    // Ajax Login
    //////////////////////////////////////////////////////////////////
    public function get_login_user()
    {
        // Verify security nonce
        check_ajax_referer('login_nonce', 'security');

        // Sanitize input fields
        $email      = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $password   = isset($_POST['password']) ? $_POST['password'] : '';
        $rememberme = isset($_POST['rememberme']) ? sanitize_text_field($_POST['rememberme']) : 'no';

        // Validate input
        if (empty($email) || empty($password)) {
            echo json_encode(array(
                'success' => false,
                'messages' => esc_html__('Email and password are required.', 'togo'),
                'class' => 'text-warning'
            ));
            wp_die();
        }

        $user_login = $email;

        // If input is email, get corresponding username
        if (is_email($email)) {
            $current_user = get_user_by('email', $email);
            if ($current_user && !is_wp_error($current_user)) {
                $user_login = $current_user->user_login;
            } else {
                echo json_encode(array(
                    'success' => false,
                    'messages' => esc_html__('User not found.', 'togo'),
                    'class' => 'text-error'
                ));
                wp_die();
            }
        }

        // Prepare credentials array
        $credentials = array(
            'user_login'    => $user_login,
            'user_password' => $password,
            'remember'      => ($rememberme === 'yes')
        );

        // Attempt to sign in
        $user = wp_signon($credentials, false);

        if (!is_wp_error($user)) {
            // Success login response
            echo json_encode(array(
                'success'  => true,
                'messages' => esc_html__('Login successful.', 'togo'),
                'class'    => 'text-success',
                'redirect' => wc_get_page_permalink('myaccount')
            ));
        } else {
            // Login failed
            echo json_encode(array(
                'success'  => false,
                'messages' => esc_html__('Incorrect email or password. Please try again.', 'togo'),
                'class'    => 'text-error',
                'redirect' => ''
            ));
        }

        wp_die();
    }

    //////////////////////////////////////////////////////////////////
    // Ajax Register
    //////////////////////////////////////////////////////////////////
    public function get_register_user()
    {
        // Verify the security nonce
        check_ajax_referer('register_nonce', 'security');

        // Sanitize and get input values
        $firstname  = isset($_POST['firstname']) ? sanitize_text_field($_POST['firstname']) : '';
        $lastname   = isset($_POST['lastname']) ? sanitize_text_field($_POST['lastname']) : '';
        $email      = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $password   = isset($_POST['password']) ? $_POST['password'] : '';

        // Validate required fields
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            echo json_encode(array(
                'success' => false,
                'messages' => esc_html__('Please fill in all required fields.', 'togo'),
                'class' => 'text-warning'
            ));
            wp_die();
        }

        // Generate initial username from first and last name
        $user_login = sanitize_user($firstname . $lastname, true);

        // Prepare user data array
        $userdata = array(
            'user_login' => $user_login,
            'first_name' => $firstname,
            'last_name'  => $lastname,
            'user_email' => $email,
            'user_pass'  => $password
        );

        // Attempt to create user
        $user_id = wp_insert_user($userdata);

        // If username exists, fallback to using email prefix
        if (is_wp_error($user_id)) {
            $user_login = sanitize_user(substr($email, 0, strpos($email, '@')), true);
            $userdata['user_login'] = $user_login;
            $user_id = wp_insert_user($userdata);
        }

        // Final check for user creation
        if (!is_wp_error($user_id)) {
            // Auto login the new user
            $creds = array(
                'user_login'    => $user_login,
                'user_password' => $password,
                'remember'      => true
            );
            $user = wp_signon($creds, false);

            // Success response
            echo json_encode(array(
                'success' => true,
                'messages' => esc_html__('Registration successful.', 'togo'),
                'class' => 'text-success',
                'redirect' => wc_get_page_permalink('myaccount')
            ));
        } else {
            // Handle WP error messages gracefully
            $error_message = $user_id->get_error_message();
            echo json_encode(array(
                'success' => false,
                'messages' => esc_html__('Username or email already exists.', 'togo') . ' ' . esc_html($error_message),
                'class' => 'text-error'
            ));
        }

        wp_die();
    }

    //////////////////////////////////////////////////////////////////
    // Ajax forgot password
    //////////////////////////////////////////////////////////////////
    public function forgot_password_ajax()
    {
        // Verify the security nonce
        check_ajax_referer('togo_forgot_password_ajax_nonce', 'togo_security_forgot_password');

        $allowed_html = array();
        $user_login = wp_kses($_POST['user_login'] ?? '', $allowed_html);

        // Check if username or email is empty
        if (empty($user_login)) {
            echo json_encode(array(
                'success' => false,
                'class' => 'text-warning',
                'message' => esc_html__('Enter a username or email address.', 'togo')
            ));
            wp_die();
        }

        // Get user data by email or username
        if (strpos($user_login, '@') !== false) {
            $user_data = get_user_by('email', trim($user_login));
            if (empty($user_data)) {
                echo json_encode(array(
                    'success' => false,
                    'class' => 'text-error',
                    'message' => esc_html__('There is no user registered with that email address.', 'togo')
                ));
                wp_die();
            }
        } else {
            $login = trim($user_login);
            $user_data = get_user_by('login', $login);
            if (!$user_data) {
                echo json_encode(array(
                    'success' => false,
                    'class' => 'text-error',
                    'message' => esc_html__('Invalid username.', 'togo')
                ));
                wp_die();
            }
        }

        // Prepare reset key and email data
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key = get_password_reset_key($user_data);

        // Check if reset key generation failed
        if (is_wp_error($key)) {
            echo json_encode(array(
                'success' => false,
                'message' => $key->get_error_message()
            ));
            wp_die();
        }

        // Construct the password reset email message
        $message  = esc_html__('Someone has requested a password reset for the following account:', 'togo') . "\r\n\r\n";
        $message .= network_home_url('/') . "\r\n\r\n";
        $message .= sprintf(esc_html__('Username: %s', 'togo'), $user_login) . "\r\n\r\n";
        $message .= esc_html__('If this was a mistake, just ignore this email and nothing will happen.', 'togo') . "\r\n\r\n";
        $message .= esc_html__('To reset your password, visit the following address:', 'togo') . "\r\n\r\n";
        $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

        // Get site/blog name for email subject
        $blogname = is_multisite() ? $GLOBALS['current_site']->site_name : wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        // Filter email subject and content
        $title = sprintf(esc_html__('[%s] Password Reset', 'togo'), $blogname);
        $title = apply_filters('retrieve_password_title', $title, $user_login, $user_data);
        $message = apply_filters('retrieve_password_message', $message, $key, $user_login, $user_data);

        // Attempt to send the email
        if ($message && !wp_mail($user_email, wp_specialchars_decode($title), $message)) {
            echo json_encode(array(
                'success' => false,
                'class' => 'text-error',
                'message' => esc_html__('The email could not be sent.', 'togo') . "\r\n" . esc_html__('Possible reason: your host may have disabled the mail() function.', 'togo')
            ));
            wp_die();
        } else {
            echo json_encode(array(
                'success' => true,
                'class' => 'text-success',
                'message' => esc_html__('Please check your email to reset your password.', 'togo')
            ));
            wp_die();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Ajax reset password
    //////////////////////////////////////////////////////////////////
    public function change_password_ajax()
    {
        // Verify security nonce
        check_ajax_referer('reset_password_nonce', 'security');

        // Check if the user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(array(
                'class' => 'text-error',
                'message' => esc_html__('You must be logged in to change your password.', 'togo')
            ));
        }

        // Get the currently logged-in user
        $current_user = wp_get_current_user();
        $login = sanitize_user($_POST['login'] ?? '');
        $new_password = sanitize_text_field($_POST['new_password'] ?? '');

        // Validate input
        if (empty($login) || empty($new_password)) {
            wp_send_json_error(array(
                'class' => 'text-error',
                'message' => esc_html__('Invalid request. Missing fields.', 'togo')
            ));
        }

        // Check if the user exists
        $user_data = get_user_by('login', $login);
        if (!$user_data) {
            wp_send_json_error(array(
                'class' => 'text-error',
                'message' => esc_html__('User not found.', 'togo')
            ));
        }

        // Prevent users from changing other users' passwords
        if ($current_user->ID !== $user_data->ID && !current_user_can('edit_users')) {
            wp_send_json_error(array(
                'class' => 'text-error',
                'message' => esc_html__('You do not have permission to change this password.', 'togo')
            ));
        }

        // Proceed with password change
        wp_set_password($new_password, $user_data->ID);

        // Return success response
        wp_send_json_success(array(
            'class' => 'text-success',
            'message' => esc_html__('Password changed successfully. Please re-login.', 'togo'),
            'redirect' => wp_logout_url(home_url())
        ));
    }
}
