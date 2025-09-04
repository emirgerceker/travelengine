<?php

namespace Togo_Framework\Post_Type\Trips;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Trips
 */
class Metabox
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
        add_action('admin_enqueue_scripts', array($this, 'add_trips_metabox_styles'));
        add_action('admin_enqueue_scripts', array($this, 'add_trips_metabox_scripts'));
        add_filter('uxper_meta_box_config', array($this, 'register_meta_boxes'));
        add_action('togo_trip_schedule_tabs', array($this, 'display_trip_metabox'));
        add_filter('trip_min_max_guests', array($this, 'add_min_max_guests'));
        add_filter('trip_reviews_field_status', array($this, 'add_trip_reviews_field_status'));
        add_filter('trip_reviews_fields', array($this, 'add_trip_reviews_fields'));
        add_action('wp_ajax_save_trip_schedule', array($this, 'save_trip_schedule'));
        add_action('wp_ajax_nopriv_save_trip_schedule', array($this, 'save_trip_schedule'));
        add_action('wp_ajax_create_package', array($this, 'create_package'));
        add_action('wp_ajax_nopriv_create_package', array($this, 'create_package'));
        add_action('wp_ajax_edit_package', array($this, 'edit_package'));
        add_action('wp_ajax_nopriv_edit_package', array($this, 'edit_package'));
        add_action('wp_ajax_delete_package', array($this, 'delete_package'));
        add_action('wp_ajax_nopriv_delete_package', array($this, 'delete_package'));
        add_action('wp_ajax_delete_schedule', array($this, 'delete_schedule'));
        add_action('wp_ajax_nopriv_delete_schedule', array($this, 'delete_schedule'));
        add_action('wp_ajax_edit_schedule', array($this, 'edit_schedule'));
        add_action('wp_ajax_nopriv_edit_schedule', array($this, 'edit_schedule'));
    }

    public function add_trips_metabox_styles()
    {
        wp_enqueue_style('trip-backend-metabox', TOGO_FRAMEWORK_DIR . 'inc/backend/assets/css/trip-metabox.css');
    }

    public function add_trips_metabox_scripts()
    {
        wp_enqueue_script('trip-backend-metabox', TOGO_FRAMEWORK_DIR . 'inc/backend/assets/js/trip-metabox.js', array('jquery'), null, true);
        wp_localize_script('trip-backend-metabox', 'togo_trip_metabox', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'validation_package_name' => __('Please enter package name.', 'togo'),
            'validation_start_date' => __('Please enter start date.', 'togo'),
            'validation_regular_price' => __('Please enter price for adults.', 'togo'),
            'validation_trip_days' => __('Please select at least 1 date.', 'togo'),
            'validation_trip_times' => __('Please select at least 1 time.', 'togo'),
            'validation_min_max_guests' => __('Please enter a value greater than', 'togo'),
            'validation_first_min_guests' => __('Please enter a value of 0.', 'togo'),
            'validation_min_guests' => __('Please enter min and max values ​​for the above row.', 'togo'),
            'modal_schedule_title' => __('Create a pricing schedule', 'togo'),
            'modal_schedule_button' => __('Create a schedule', 'togo'),
            'validation_empty_start_date' => __('Please enter start date.', 'togo'),
            'validation_end_date' => __('Please enter end date greater than start date.', 'togo'),
            'validation_delete' => __('Are you sure you want to delete this item?', 'togo'),
        ]);
    }

    /**
     * Register Metabox
     *
     * @param $meta_boxes
     *
     * @return array
     */
    public function register_meta_boxes($configs)
    {
        $format_number = '^[0-9]+([.][0-9]+)?$';
        $configs['trips_meta_boxes'] = apply_filters('trips_meta_boxes', array(
            'id'        => 'togo_trips_options',
            'name'      => esc_html__('Trip Settings', 'togo-framework'),
            'post_type' => array('togo_trip'),
            'section'   => array_merge(
                apply_filters('trips_meta_boxes_top', array()),
                apply_filters(
                    'trips_meta_boxes_main',
                    array_merge(
                        array(
                            array(
                                'id'     => "trip_general_tabs",
                                'title'  => esc_html__('General', 'togo-framework'),
                                'icon'   => 'dashicons-admin-settings',
                                'fields' => array(
                                    array(
                                        'id' => "trip_pricing_type",
                                        'title' => esc_html__('Pricing Type', 'togo-framework'),
                                        'type' => 'button_set',
                                        'options' => array(
                                            'per_person' => esc_html__('Per Person', 'togo-framework'),
                                            'per_group' => esc_html__('Per Group', 'togo-framework'),
                                        ),
                                        'default' => 'per_person',
                                    ),

                                    array(
                                        'type' => 'row',
                                        'col' => '12',
                                        'fields' => array(
                                            array(
                                                'id' => "trip_time",
                                                'title' => esc_html__('Trip Time', 'togo-framework'),
                                                'type' => 'select',
                                                'options' => array(
                                                    'start_times' => esc_html__('Start times', 'togo-framework'),
                                                    'opening_hours' => esc_html__('Opening hours', 'togo-framework'),
                                                    'many_days' => esc_html__('Many days', 'togo-framework'),
                                                ),
                                                'col' => '4',
                                                'default' => 'start_times',
                                            ),
                                            array(
                                                'id'      => "trip_duration_hours",
                                                'title'   => esc_html__('Duration (Hours)', 'togo-framework'),
                                                'type'    => 'text',
                                                'input_type' => 'number',
                                                'default' => '',
                                                'col' => '4',
                                                'required' => array('trip_time', 'in', array('start_times', 'opening_hours')),
                                            ),
                                            array(
                                                'id'      => "trip_duration_minutes",
                                                'title'   => esc_html__('Duration (Minutes)', 'togo-framework'),
                                                'type'    => 'text',
                                                'input_type' => 'number',
                                                'default' => '',
                                                'col' => '4',
                                                'required' => array('trip_time', 'in', array('start_times', 'opening_hours')),
                                            ),
                                            array(
                                                'id'      => "trip_duration_days",
                                                'title'   => esc_html__('Duration (Days)', 'togo-framework'),
                                                'type'    => 'text',
                                                'input_type' => 'number',
                                                'default' => '',
                                                'col' => '4',
                                                'required' => array('trip_time', 'in', array('many_days')),
                                            ),
                                            array(
                                                'id'      => "trip_duration_nights",
                                                'title'   => esc_html__('Duration (Nights)', 'togo-framework'),
                                                'type'    => 'text',
                                                'input_type' => 'number',
                                                'default' => '',
                                                'col' => '4',
                                                'required' => array('trip_time', 'in', array('many_days')),
                                            ),
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '12',
                                        'fields' => array(
                                            array(
                                                'id'      => "trip_minimum_guests",
                                                'title'   => esc_html__('Minimum Travelers', 'togo-framework'),
                                                'type'    => 'text',
                                                'pattern' => "{$format_number}",
                                                'default' => '',
                                                'col' => '6',
                                                'desc' => esc_html__('Minimum number of people allowed on the trip', 'togo-framework'),
                                            ),
                                            array(
                                                'id'      => "trip_maximum_guests",
                                                'title'   => esc_html__('Maximum Travelers', 'togo-framework'),
                                                'type'    => 'text',
                                                'pattern' => "{$format_number}",
                                                'default' => '',
                                                'col' => '6',
                                                'desc' => esc_html__('Maximum number of people allowed on the trip', 'togo-framework'),
                                            ),
                                        )
                                    ),

                                    array(
                                        'type' => 'row',
                                        'col' => '12',
                                        'fields' => array_merge(
                                            apply_filters('trip_min_max_guests', array()),
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '12',
                                        'fields' => array(
                                            array(
                                                'id' => "trip_enable_cancellation_time",
                                                'title' => esc_html__('Enable Cancellation Time', 'togo-framework'),
                                                'desc' => esc_html__('Free cancellation available until the tour start date.', 'togo-framework'),
                                                'type' => 'button_set',
                                                'options' => array(
                                                    '0' => esc_html__('No', 'togo-framework'),
                                                    '1' => esc_html__('Yes', 'togo-framework'),
                                                ),
                                                'default' => '0',
                                                'col' => '6',
                                            ),
                                            array(
                                                'id'      => "trip_cancellation_time",
                                                'title'   => esc_html__('Cancellation Time', 'togo-framework'),
                                                'desc'    => esc_html__('Hours', 'togo-framework'),
                                                'type'    => 'text',
                                                'pattern' => "{$format_number}",
                                                'default' => '1',
                                                'col' => '6',
                                                'required' => array('trip_enable_cancellation_time', '=', '1'),
                                            ),
                                        )
                                    ),
                                )
                            ),
                            array(
                                'id'     => "trip_schedule_tabs",
                                'title'  => esc_html__('Schedule & Pricing', 'togo-framework'),
                                'icon'   => 'dashicons-feedback',
                                'fields' => array()
                            ),
                            array(
                                'id'     => "trip_gallery_tabs",
                                'title'  => esc_html__('Gallery', 'togo-framework'),
                                'icon'   => 'dashicons-format-gallery',
                                'fields' => array(
                                    array(
                                        'id'    => "trip_video_url",
                                        'title' => esc_html__('Video URL', 'togo-framework'),
                                        'desc'  => esc_html__('Input only URL (YouTube, Vimeo, MP4)', 'togo-framework'),
                                        'type'  => 'text',
                                    ),
                                    array(
                                        'id'    => "trip_video_image",
                                        'title' => esc_html__('Video Image', 'togo-framework'),
                                        'type'  => 'image',
                                    ),
                                    array(
                                        'id'    => "trip_gallery_images",
                                        'title' => esc_html__('Gallery Images', 'togo-framework'),
                                        'type'  => 'gallery',
                                    ),
                                )
                            ),
                            array(
                                'id'     => "trip_overview_tabs",
                                'title'  => esc_html__('Overview', 'togo-framework'),
                                'icon'   => 'dashicons-art',
                                'fields' => array(
                                    array(
                                        'id'     => "trip_overview_repeater",
                                        'type'   => 'repeater',
                                        'title'  => esc_html__('Overview', 'togo-framework'),
                                        'sort'   => true,
                                        'fields' => array(
                                            array(
                                                'id'      => "trip_overview_icon",
                                                'title'   => esc_html__('Icon', 'togo-framework'),
                                                'type'    => 'radio',
                                                'default' => '',
                                                'show_svg' => true,
                                                'options' => \Togo\Icon::get_array_icons(),
                                                'col' => '2',
                                            ),
                                            array(
                                                'id'      => "trip_overview_name",
                                                'title'   => esc_html__('Title', 'togo-framework'),
                                                'type'    => 'text',
                                                'default' => '',
                                                'col' => '5',
                                            ),
                                            array(
                                                'id'      => "trip_overview_value",
                                                'title'   => esc_html__('Value', 'togo-framework'),
                                                'type'    => 'text',
                                                'default' => '',
                                                'col' => '5',
                                            ),
                                        )
                                    ),
                                    array(
                                        'id'    => "trip_overview_description",
                                        'title' => esc_html__('Description', 'togo-framework'),
                                        'type'  => 'textarea',
                                    )
                                )
                            ),
                            array(
                                'id'     => "trip_highlights_tabs",
                                'title'  => esc_html__('Highlights', 'togo-framework'),
                                'icon'   => 'dashicons-image-filter',
                                'fields' => array(
                                    array(
                                        'id'    => "trip_highlights",
                                        'title' => esc_html__('Highlights', 'togo-framework'),
                                        'type'  => 'textarea',
                                        'desc'  => esc_html__('Each highlight on a new line.', 'togo-framework'),
                                    )
                                )
                            ),
                            array(
                                'id'     => "trip_ie_tabs",
                                'title'  => esc_html__('Includes/Excludes', 'togo-framework'),
                                'icon'   => 'dashicons-saved',
                                'fields' => array(
                                    array(
                                        'id'    => "trip_includes",
                                        'title' => esc_html__('Includes', 'togo-framework'),
                                        'type'  => 'textarea',
                                        'desc'  => esc_html__('Each include on a new line.', 'togo-framework'),
                                    ),
                                    array(
                                        'id'    => "trip_excludes",
                                        'title' => esc_html__('Excludes', 'togo-framework'),
                                        'type'  => 'textarea',
                                        'desc'  => esc_html__('Each exclude on a new line.', 'togo-framework'),
                                    ),
                                )
                            ),
                            array(
                                'id'     => "trip_itinerary_tabs",
                                'title'  => esc_html__('Itinerary', 'togo-framework'),
                                'icon'   => 'dashicons-location-alt',
                                'fields' => array(
                                    array(
                                        'id' => "trip_itinerary",
                                        'type' => 'panel',
                                        'title' => esc_html__('Itinerary', 'togo-framework'),
                                        'sort' => true,
                                        'toggle_default' => false,
                                        'fields' => array(
                                            array(
                                                'title' => __('Title', 'togo-framework'),
                                                'id'    => 'trip_itinerary_title',
                                                'type'  => 'text',
                                                'panel_title' => true,
                                            ),
                                            array(
                                                'id' => "trip_itinerary_content",
                                                'title' => esc_html__('Content', 'togo-framework'),
                                                'type' => 'editor',
                                            ),
                                            array(
                                                'id' => "trip_itinerary_image",
                                                'title' => esc_html__('Image', 'togo-framework'),
                                                'type' => 'image',
                                            ),
                                            array(
                                                'id' => "trip_itinerary_address",
                                                'title' => esc_html__('Address', 'togo-framework'),
                                                'type' => 'map',
                                            ),
                                        )
                                    ),
                                )
                            ),
                            array(
                                'id'     => "trip_maps_tabs",
                                'title'  => esc_html__('Maps', 'togo-framework'),
                                'icon'   => 'dashicons-location',
                                'fields' => array(
                                    array(
                                        'id' => "trip_maps_address",
                                        'title' => esc_html__('Pickup point (or departure point).', 'togo-framework'),
                                        'type' => 'map',
                                    ),
                                )
                            ),
                            array(
                                'id'     => "trip_faqs_tabs",
                                'title'  => esc_html__('FAQs', 'togo-framework'),
                                'icon'   => 'dashicons-editor-help',
                                'fields' => array(
                                    array(
                                        'id' => "trip_faqs",
                                        'type' => 'panel',
                                        'title' => esc_html__('FAQs', 'togo-framework'),
                                        'sort' => true,
                                        'toggle_default' => false,
                                        'fields' => array(
                                            array(
                                                'title' => __('Question', 'togo-framework'),
                                                'id'    => 'trip_faqs_question',
                                                'type'  => 'text',
                                                'panel_title' => true,
                                            ),
                                            array(
                                                'id' => "trip_faqs_answer",
                                                'title' => esc_html__('Answer', 'togo-framework'),
                                                'type' => 'textarea',
                                            ),
                                        )
                                    ),
                                )
                            ),
                        )
                    )
                ),
                apply_filters('trips_meta_boxes_bottom', array())
            ),
        ));

        return apply_filters('trips_register_meta_boxes', $configs);
    }

    public function add_min_max_guests($fields)
    {
        $pricing_category = \Togo_Framework\Helper::get_pricing_categories();
        if ($pricing_category) {
            $fields[] = array(
                'id' => "trip_enable_min_max_person",
                'title' => esc_html__('Enable Min and Max Guests', 'togo-framework'),
                'type' => 'button_set',
                'options' => array(
                    '0' => esc_html__('No', 'togo-framework'),
                    '1' => esc_html__('Yes', 'togo-framework'),
                ),
                'default' => '0',
                'col' => '12',
                'required' => array('trip_pricing_type', '=', 'per_person'),
            );
            foreach ($pricing_category as $key => $category) {
                $fields[] = array(
                    'id' => "trip_min_guests_" . $key,
                    'title' => 'Min. ' . $category,
                    'type' => 'text',
                    'input_type' => 'number',
                    'default' => '',
                    'col' => '6',
                    'required' => array('trip_enable_min_max_person', '=', '1'),
                );
                $fields[] = array(
                    'id' => "trip_max_guests_" . $key,
                    'title' => 'Max. ' . $category,
                    'type' => 'text',
                    'input_type' => 'number',
                    'default' => '',
                    'col' => '6',
                    'required' => array('trip_enable_min_max_person', '=', '1'),
                );
            }
        }

        return $fields;
    }

    /**
     * Add field status to trip review meta box.
     *
     * @param array $fields
     *
     * @return array
     */
    public function add_trip_reviews_field_status($fields)
    {
        $enable_approve_review = \Togo\Helper::setting('enable_approve_review');
        if ($enable_approve_review == 'yes') {
            $fields[]   = array(
                'id' => "trip_reviews_status",
                'title' => esc_html__('Status', 'togo-framework'),
                'type' => 'button_set',
                'options' => array(
                    'wait' => esc_html__('Waiting for approval', 'togo-framework'),
                    'publish' => esc_html__('Published', 'togo-framework'),
                ),
                'default' => 'wait',
                'col' => '12',
            );
        }

        return $fields;
    }

    public function add_trip_reviews_fields($fields)
    {
        $single_trip_max_star = \Togo\Helper::setting('single_trip_max_star') ? \Togo\Helper::setting('single_trip_max_star') : 5;
        $single_trip_reviews = \Togo\Helper::setting('single_trip_reviews');
        $review_items = [];
        foreach ($single_trip_reviews as $key => $value) {
            $review_items[] = array(
                'id' => "trip_reviews_" . $key,
                'title' => $value['text'],
                'type' => 'text',
                'input_type' => 'number',
                'col' => '3',
                'args' => array(
                    'min' => 0,
                    'max' => $single_trip_max_star,
                )
            );
        }
        $fields[] = array(
            'type' => 'row',
            'col' => '12',
            'fields' => $review_items
        );
        return $fields;
    }

    public function display_trip_metabox()
    {
        $tour_package = get_post_meta(get_the_ID(), 'tour_package', true);
        $trip_time = get_post_meta(get_the_ID(), 'trip_time', true);
        $trip_pricing_type = get_post_meta(get_the_ID(), 'trip_pricing_type', true);
        $pricing_category = \Togo_Framework\Helper::get_pricing_categories();
        $days = \Togo_Framework\Helper::get_all_days();

        // Output fields
?>
        <div class="trip-metaboxs">
            <div class="trip-section-item">
                <?php
                if ($tour_package) {
                    foreach ($tour_package as $package) {
                        $package_name = $package['package_name'];
                        $package_description = $package['package_description'];
                        $schedules = $package['schedules'];
                ?>
                        <div class="package-panel">
                            <div class="package-header">
                                <h3 class="package-title">
                                    <?php echo esc_html($package_name); ?>
                                </h3>
                                <div class="package-actions">
                                    <a href="#modal-package" class="open-modal action-edit" data-package="<?php echo esc_attr($package_name); ?>" data-postid="<?php echo esc_attr(get_the_ID()); ?>"><?php echo esc_html__('Edit', 'togo'); ?></a>
                                    <a href="#" class="action-delete-package" data-package="<?php echo esc_attr($package_name); ?>" data-postid="<?php echo esc_attr(get_the_ID()); ?>"><?php echo esc_html__('Delete', 'togo'); ?></a>
                                </div>
                            </div>
                            <div class=" package-body">
                                <?php
                                if ($package_description) {
                                    echo '<div class="package-description">';
                                    echo $package_description;
                                    echo '</div>';
                                }

                                if ($schedules) {

                                    foreach ($schedules as $key => $schedule) {
                                        echo '<div class="package-schedule">';
                                        if (array_key_exists('start_date', $schedule)) {
                                            $tiered_pricing = $schedule['tiered_pricing'];
                                            echo '<div class="top">';
                                            echo '<h4>';
                                            $date_format = get_option('date_format');
                                            echo '<span>' . date_i18n($date_format, strtotime($schedule['start_date'])) . '</span>';
                                            if (array_key_exists('end_date', $schedule) && $schedule['end_date'] == 'no_end_date') {
                                                echo ' - ' . esc_html__('no end date', 'togo');
                                            } elseif (array_key_exists('end_date', $schedule) && $schedule['end_date'] != 'no_end_date') {
                                                echo ' - ' . date_i18n($date_format, strtotime($schedule['end_date']));
                                            }
                                            echo '</h4>';
                                            echo '<div class="actions">';
                                            echo '<a href="#modal-schedule-price" class="open-modal action-edit-schedule" data-start-date="' . $schedule['start_date'] . '" data-end-date="' . $schedule['end_date'] . '" data-package="' . $package_name . '" data-postid="' . get_the_ID() . '">' . esc_html__('Edit', 'togo') . '</a>';
                                            echo '<a href="#" class="action-delete-schedule" data-start-date="' . $schedule['start_date'] . '" data-end-date="' . $schedule['end_date'] . '" data-package="' . $package_name . '" data-postid="' . get_the_ID() . '">' . esc_html__('Delete', 'togo') . '</a>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '<div class="center">';
                                            if ($pricing_category && $trip_pricing_type == 'per_person') {
                                                foreach ($pricing_category as $key => $category) {
                                                    echo '<div class="price">';
                                                    echo '<span class="name">' . esc_html($category) . ': </span>';
                                                    if (array_key_exists('regular_price[' . $key . ']', $schedule)) {
                                                        $count = count($schedule['regular_price[' . $key . ']']);
                                                        for ($i = 0; $i < $count - 1; $i++) {
                                                            if (!empty($schedule['sale_price[' . $key . ']'][$i])) {
                                                                $real_price = $schedule['sale_price[' . $key . ']'][$i];
                                                            } else if (!empty($schedule['regular_price[' . $key . ']'][$i])) {
                                                                $real_price = $schedule['regular_price[' . $key . ']'][$i];
                                                            } else {
                                                                $real_price = 0;
                                                            }
                                                            if (!empty($tiered_pricing) && $schedule['min_guests[' . $key . ']'][$i] != '' && $schedule['max_guests[' . $key . ']'][$i] != '') {
                                                                echo '<span>' . $schedule['min_guests[' . $key . ']'][$i] . '</span>';
                                                                echo '-';
                                                                echo '<span>' . $schedule['max_guests[' . $key . ']'][$i] . '</span>, ';
                                                            }
                                                            echo '<span class="price">' . \Togo_Framework\Helper::togo_format_price($real_price) . '</span>';
                                                            if ($i < $count - 2) {
                                                                echo ' | ';
                                                            }
                                                        }
                                                    }
                                                    echo '</div>';
                                                }
                                            } elseif (array_key_exists('per_group_sale_price', $schedule) && $trip_pricing_type == 'per_group') {

                                                echo '<div class="price">';
                                                echo '<span class="name">' . esc_html__('Price', 'togo') . ': </span>';
                                                if ($schedule['tiered_pricing'] != 'on') {
                                                    if (is_array($schedule['per_group_sale_price']) && !empty($schedule['per_group_sale_price'][0])) {
                                                        $real_price = $schedule['per_group_sale_price'][0];
                                                    } else if (is_array($schedule['per_group_regular_price']) && !empty($schedule['per_group_regular_price'][0])) {
                                                        $real_price = $schedule['per_group_regular_price'][0];
                                                    } else {
                                                        $real_price = 0;
                                                    }

                                                    echo \Togo_Framework\Helper::togo_format_price($real_price);
                                                } else {
                                                    $count = count($schedule['per_group_regular_price']);
                                                    for ($i = 0; $i < $count - 1; $i++) {
                                                        if (!empty($schedule['per_group_sale_price'][$i])) {
                                                            $real_price = $schedule['per_group_sale_price'][$i];
                                                        } else if (!empty($schedule['per_group_regular_price'][$i])) {
                                                            $real_price = $schedule['per_group_regular_price'][$i];
                                                        } else {
                                                            $real_price = 0;
                                                        }
                                                        if ($schedule['per_group_min_guests'][$i] != '' && $schedule['per_group_max_guests'][$i] != '') {
                                                            echo '<span>' . $schedule['per_group_min_guests'][$i] . '</span>';
                                                            echo '-';
                                                            echo '<span>' . $schedule['per_group_max_guests'][$i] . '</span>, ';
                                                        }
                                                        echo '<span class="price">' . \Togo_Framework\Helper::togo_format_price($real_price) . '</span>';
                                                        if ($i < $count - 2) {
                                                            echo ' | ';
                                                        }
                                                    }
                                                }

                                                echo '</div>';
                                            }
                                            if ($schedule['trip_days']) {
                                                echo '<div class="days">';
                                                echo '<span class="name">' . esc_html__('Days', 'togo') . ': </span>';
                                                foreach ($schedule['trip_days'] as $key => $day) {
                                                    echo '<span>' . ucfirst($day) . '</span>';
                                                }
                                                echo '</div>';
                                            }
                                            if (array_key_exists('trip_times', $schedule) && $trip_time == 'start_times') {
                                                echo '<div class="days">';
                                                echo '<span class="name">' . esc_html__('Times', 'togo') . ': </span>';
                                                foreach ($schedule['trip_times'] as $key => $time) {
                                                    if (!empty($time)) {
                                                        echo '<span>' . $time . '</span>';
                                                    }
                                                }
                                                echo '</div>';
                                            } elseif (array_key_exists('opening_hours_days', $schedule) && $trip_time == 'opening_hours') {
                                                echo '<div class="days">';
                                                echo '<div class="name">' . esc_html__('Opening Hours', 'togo') . ': </div>';
                                                foreach ($days as $key => $day) {
                                                    if (in_array($key, $schedule['opening_hours_days'])) {
                                                        echo '<div class="line">';
                                                        echo '<b>' . ucfirst($day) . ':</b>';
                                                        if (!empty($schedule['opening_hours_' . $key . '_start'])) {
                                                            foreach ($schedule['opening_hours_' . $key . '_start'] as $k => $v) {
                                                                if (!empty($v)) {
                                                                    echo '<span>' . $v . ' - ' . $schedule['opening_hours_' . $key . '_end'][$k] . '</span>';
                                                                }
                                                            }
                                                        }
                                                        echo '</div>';
                                                    }
                                                }
                                                echo '</div>';
                                            } elseif (array_key_exists('many_days_start_time', $schedule) && $trip_time == 'many_days') {
                                                echo '<div class="days">';
                                                echo '<span class="name">' . esc_html__('Times', 'togo') . ': </span>';
                                                if (array_key_exists('many_days_start_time', $schedule)) {
                                                    echo '<span>' . $schedule['many_days_start_time'] . '</span>';
                                                }
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                }
                                ?>
                                <a href="#modal-schedule-price" class="open-modal" data-package="<?php echo esc_attr($package_name); ?>"><?php echo esc_html__('Add a pricing schedule', 'togo'); ?></a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
                <a href="#modal-package" class="btn open-modal" data-modal-title="<?php echo esc_html__('Create a package', 'togo'); ?>" data-modal-button="<?php echo esc_html__('Create', 'togo'); ?>"><?php echo esc_html__('Add a package', 'togo'); ?></a>
            </div>
        </div>

        <div id="modal-package" class="modal">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <?php echo esc_html__('Create a package', 'togo'); ?>
                    </h3>
                    <div class="close-modal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6.00005L6 18M5.99995 6L17.9999 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="package-name">
                            <?php echo esc_html__('Name', 'togo'); ?><span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="package_name" id="package-name">
                    </div>
                    <div class="form-group">
                        <label for="package-description">
                            <?php echo esc_html__('Description', 'togo'); ?>
                        </label>
                        <?php
                        $settings = array(
                            'textarea_name' => 'package_description',
                            'editor_class' => 'form-control',
                            'media_buttons' => true,
                            'teeny' => true,
                            'quicktags' => false,
                            'textarea_rows' => 10,
                        );
                        wp_editor('', 'package_description', $settings);
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-notice"></div>
                    <input type="hidden" name="package_action" value="create">
                    <input type="hidden" name="package_old_name" value="">
                    <button type="button" class="button button-primary create-package"><?php echo esc_html__('Create', 'togo'); ?></button>
                </div>
            </div>
        </div>

        <div id="modal-schedule-price" class="modal">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <?php echo esc_html__('Create a pricing schedule', 'togo'); ?>
                    </h3>
                    <div class="close-modal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6.00005L6 18M5.99995 6L17.9999 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-head">
                            <h4><?php echo esc_html__('When does your schedule start?', 'togo'); ?></h4>
                            <i><?php echo esc_html__('Creating a schedule with no end date will set your prices in effect indefinitely.', 'togo'); ?></i>
                        </div>
                        <div class="form-flex flex-col-2">
                            <div class="form-field">
                                <label for="start_date"><?php echo esc_html__('Start Date', 'togo'); ?><span class="required">*</span></label>
                                <input type="date" name="start_date" id="start_date" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-field">
                                <label for="end_date"><?php echo esc_html__('End Date', 'togo'); ?></label>
                                <input type="date" name="end_date" id="end_date" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <?php
                    $pricing_category = \Togo_Framework\Helper::get_pricing_categories();

                    if (!empty($pricing_category)) {
                    ?>
                        <div class="form-group">
                            <h4><?php echo esc_html__('Pricing', 'togo'); ?></h4>
                            <div class="tiered-pricing">
                                <label><input type="checkbox" name="tiered_pricing" id="tiered_pricing"><span><?php echo esc_html__('Enable tiered pricing', 'togo'); ?></span></label>
                                <div class="tooltip">
                                    <i class="fas fa-question-circle"></i>
                                    <div class="tooltip-content">
                                        <h6><?php echo esc_html__('Example tiered pricing structure:', 'togo'); ?></h6>
                                        <ul>
                                            <li>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 13.6261L7.60619 15.3478C8.49194 16.2972 8.93481 16.772 9.43113 16.9218C9.86704 17.0534 10.3305 17.0181 10.7459 16.8217C11.2189 16.598 11.5985 16.0606 12.3579 14.9859L18 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <?php echo esc_html__('0-5 adult tickets: $10/person', 'togo'); ?>
                                            </li>
                                            <li>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 13.6261L7.60619 15.3478C8.49194 16.2972 8.93481 16.772 9.43113 16.9218C9.86704 17.0534 10.3305 17.0181 10.7459 16.8217C11.2189 16.598 11.5985 16.0606 12.3579 14.9859L18 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <?php echo esc_html__('6-12 adult tickets: $7/person', 'togo'); ?>
                                            </li>
                                            <li>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 13.6261L7.60619 15.3478C8.49194 16.2972 8.93481 16.772 9.43113 16.9218C9.86704 17.0534 10.3305 17.0181 10.7459 16.8217C11.2189 16.598 11.5985 16.0606 12.3579 14.9859L18 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <?php echo esc_html__('13-15 adult tickets: $4/person', 'togo'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <table class="group-price">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><?php echo esc_html__('Minimum Guests', 'togo'); ?></th>
                                        <th><?php echo esc_html__('Maximum Guests', 'togo'); ?></th>
                                        <th><?php echo esc_html__('Regular Price', 'togo'); ?><span class="required">*</span></th>
                                        <th><?php echo esc_html__('Sale Price', 'togo'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <?php
                                if ($trip_pricing_type == 'per_person') {
                                ?>
                                    <tbody class="price-per_person">
                                        <?php
                                        foreach ($pricing_category as $key => $value) {
                                        ?>
                                            <tr class="origin">
                                                <td class="group-price-title"><?php echo esc_html($value); ?></td>
                                                <td><input type="number" name="min_guests[<?php echo esc_attr($key); ?>][]" value="" min="0"></td>
                                                <td><input type="number" name="max_guests[<?php echo esc_attr($key); ?>][]" value="" min="0"></td>
                                                <td><input type="number" name="regular_price[<?php echo esc_attr($key); ?>][]" value="" min="0"></td>
                                                <td><input type="number" name="sale_price[<?php echo esc_attr($key); ?>][]" value="" min="0"></td>
                                                <td>
                                                    <a href="#" class="add_group_price" data-key="<?php echo esc_attr($key) . '_clone'; ?>">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M3 9V19.4C3 19.9601 3 20.2399 3.10899 20.4538C3.20487 20.642 3.35774 20.7952 3.5459 20.8911C3.7596 21 4.0395 21 4.59846 21H15.0001M14 13V10M14 10V7M14 10H11M14 10H17M7 13.8002V6.2002C7 5.08009 7 4.51962 7.21799 4.0918C7.40973 3.71547 7.71547 3.40973 8.0918 3.21799C8.51962 3 9.08009 3 10.2002 3H17.8002C18.9203 3 19.4801 3 19.9079 3.21799C20.2842 3.40973 20.5905 3.71547 20.7822 4.0918C21.0002 4.51962 21.0002 5.07969 21.0002 6.19978L21.0002 13.7998C21.0002 14.9199 21.0002 15.48 20.7822 15.9078C20.5905 16.2841 20.2842 16.5905 19.9079 16.7822C19.4805 17 18.9215 17 17.8036 17H10.1969C9.07899 17 8.5192 17 8.0918 16.7822C7.71547 16.5905 7.40973 16.2842 7.21799 15.9079C7 15.4801 7 14.9203 7 13.8002Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr id="<?php echo esc_attr($key) . '_clone'; ?>" class="clone">
                                                <td></td>
                                                <td><input type="number" name="min_guests[<?php echo esc_attr($key); ?>][]" value=""></td>
                                                <td><input type="number" name="max_guests[<?php echo esc_attr($key); ?>][]" value=""></td>
                                                <td><input type="number" name="regular_price[<?php echo esc_attr($key); ?>][]" value=""></td>
                                                <td><input type="number" name="sale_price[<?php echo esc_attr($key); ?>][]" value=""></td>
                                                <td>
                                                    <a href="#" class="remove_group_price" data-key="<?php echo esc_attr($key) . '_clone'; ?>">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.28613 8.57153L7.42899 20.0001H16.5718L17.7147 8.57153" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M13.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M10.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M4.57129 6.28571H9.14272M9.14272 6.28571L9.52478 4.75746C9.63607 4.3123 10.0361 4 10.4949 4H13.5048C13.9637 4 14.3636 4.3123 14.4749 4.75746L14.857 6.28571M9.14272 6.28571H14.857M14.857 6.28571H19.4284" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                <?php } ?>
                                <?php
                                if ($trip_pricing_type == 'per_group') {
                                ?>
                                    <tbody class="price-per_group">
                                        <tr class="origin">
                                            <td class="group-price-title"></td>
                                            <td><input type="number" name="per_group_min_guests[]" value="" min="0"></td>
                                            <td><input type="number" name="per_group_max_guests[]" value="" min="0"></td>
                                            <td><input type="number" name="per_group_regular_price[]" value="" min="0"></td>
                                            <td><input type="number" name="per_group_sale_price[]" value="" min="0"></td>
                                            <td>
                                                <a href="#" class="add_group_price" data-key="per-group-clone">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3 9V19.4C3 19.9601 3 20.2399 3.10899 20.4538C3.20487 20.642 3.35774 20.7952 3.5459 20.8911C3.7596 21 4.0395 21 4.59846 21H15.0001M14 13V10M14 10V7M14 10H11M14 10H17M7 13.8002V6.2002C7 5.08009 7 4.51962 7.21799 4.0918C7.40973 3.71547 7.71547 3.40973 8.0918 3.21799C8.51962 3 9.08009 3 10.2002 3H17.8002C18.9203 3 19.4801 3 19.9079 3.21799C20.2842 3.40973 20.5905 3.71547 20.7822 4.0918C21.0002 4.51962 21.0002 5.07969 21.0002 6.19978L21.0002 13.7998C21.0002 14.9199 21.0002 15.48 20.7822 15.9078C20.5905 16.2841 20.2842 16.5905 19.9079 16.7822C19.4805 17 18.9215 17 17.8036 17H10.1969C9.07899 17 8.5192 17 8.0918 16.7822C7.71547 16.5905 7.40973 16.2842 7.21799 15.9079C7 15.4801 7 14.9203 7 13.8002Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr id="per-group-clone" class="clone">
                                            <td></td>
                                            <td><input type="number" name="per_group_min_guests[]" value=""></td>
                                            <td><input type="number" name="per_group_max_guests[]" value=""></td>
                                            <td><input type="number" name="per_group_regular_price[]" value=""></td>
                                            <td><input type="number" name="per_group_sale_price[]" value=""></td>
                                            <td>
                                                <a href="#" class="remove_group_price" data-key="per-group-clone">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.28613 8.57153L7.42899 20.0001H16.5718L17.7147 8.57153" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M13.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M4.57129 6.28571H9.14272M9.14272 6.28571L9.52478 4.75746C9.63607 4.3123 10.0361 4 10.4949 4H13.5048C13.9637 4 14.3636 4.3123 14.4749 4.75746L14.857 6.28571M9.14272 6.28571H14.857M14.857 6.28571H19.4284" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="form-group form-group-days">
                        <h4><?php echo esc_html__('Select the days these prices apply', 'togo'); ?><span class="required">*</span></h4>
                        <div class="form-flex flex-inline">
                            <?php
                            foreach ($days as $key => $day) {
                            ?>
                                <div class="form-checkbox">
                                    <input type="checkbox" name="trip_days[]" id="trip_<?php echo esc_attr($key); ?>_days" value="<?php echo esc_attr($key); ?>">
                                    <label for="trip_<?php echo esc_attr($key); ?>_days"><?php echo esc_html($day); ?></label>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <h4><?php echo esc_html__('Add the times when these prices apply', 'togo'); ?><span class="required">*</span></h4>
                        <?php
                        if ($trip_time == 'start_times') {
                        ?>
                            <div class="start-times">
                                <div class="field-time"></div>
                                <a href="#" class="add-time">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 12L12 12M12 12L6 12.0001M12 12L12 6M12 12L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <?php echo esc_html__('Add Time', 'togo'); ?>
                                </a>
                                <div class="field-time-clone">
                                    <div class="time-wrapper">
                                        <input type="time" name="trip_times[]">
                                        <a href="#" class="remove-time">
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.5 5.50004L5.5 16.5M5.49995 5.5L16.4999 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } elseif ($trip_time == 'opening_hours') {
                        ?>
                            <div class="opening-hours">
                                <?php
                                foreach ($days as $key => $day) {
                                ?>
                                    <div class="opening-hours-item">
                                        <div class="day-name">
                                            <input type="checkbox" name="opening_hours_days[]" value="<?php echo esc_attr($key); ?>">
                                            <span><?php echo esc_html($day); ?></span>
                                        </div>
                                        <div class="times">
                                            <div class="time">
                                                <input type="time" name="opening_hours_<?php echo esc_attr($key); ?>_start[]" disabled>
                                                <span>-</span>
                                                <input type="time" name="opening_hours_<?php echo esc_attr($key); ?>_end[]" disabled>
                                                <a href="#" class="add-opening-hours-time">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3 9V19.4C3 19.9601 3 20.2399 3.10899 20.4538C3.20487 20.642 3.35774 20.7952 3.5459 20.8911C3.7596 21 4.0395 21 4.59846 21H15.0001M14 13V10M14 10V7M14 10H11M14 10H17M7 13.8002V6.2002C7 5.08009 7 4.51962 7.21799 4.0918C7.40973 3.71547 7.71547 3.40973 8.0918 3.21799C8.51962 3 9.08009 3 10.2002 3H17.8002C18.9203 3 19.4801 3 19.9079 3.21799C20.2842 3.40973 20.5905 3.71547 20.7822 4.0918C21.0002 4.51962 21.0002 5.07969 21.0002 6.19978L21.0002 13.7998C21.0002 14.9199 21.0002 15.48 20.7822 15.9078C20.5905 16.2841 20.2842 16.5905 19.9079 16.7822C19.4805 17 18.9215 17 17.8036 17H10.1969C9.07899 17 8.5192 17 8.0918 16.7822C7.71547 16.5905 7.40973 16.2842 7.21799 15.9079C7 15.4801 7 14.9203 7 13.8002Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="field-time-clone">
                                            <div class="time">
                                                <input type="time" name="opening_hours_<?php echo esc_attr($key); ?>_start[]">
                                                <span>-</span>
                                                <input type="time" name="opening_hours_<?php echo esc_attr($key); ?>_end[]">
                                                <a href="#" class="remove_opening-hours-time">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.28613 8.57153L7.42899 20.0001H16.5718L17.7147 8.57153" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M13.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M4.57129 6.28571H9.14272M9.14272 6.28571L9.52478 4.75746C9.63607 4.3123 10.0361 4 10.4949 4H13.5048C13.9637 4 14.3636 4.3123 14.4749 4.75746L14.857 6.28571M9.14272 6.28571H14.857M14.857 6.28571H19.4284" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php
                        } elseif ($trip_time == 'many_days') {
                        ?>
                            <div class="many-days">
                                <div class="time-wrapper">
                                    <input type="time" name="many_days_start_time">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-notice"></div>
                    <input type="hidden" name="trip_metabox_nonce" value="<?php echo wp_create_nonce('save_trip_metabox'); ?>">
                    <input type="hidden" name="schedule_old_start_date" value="">
                    <input type="hidden" name="schedule_old_end_date" value="">
                    <input type="hidden" name="schedule_action" value="">
                    <input type="hidden" name="schedule_package_name" value="<?php echo esc_attr($package_name); ?>">
                    <button type="button" class="button button-primary save-schedule"><?php echo esc_html__('Create a schedule', 'togo'); ?></button>
                </div>
            </div>
        </div>
<?php
    }

    public function save_trip_schedule()
    {
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $tiered_pricing = isset($_POST["tiered_pricing"]) ? sanitize_text_field($_POST["tiered_pricing"]) : '';
        $trip_days = isset($_POST['trip_days']) ? $_POST['trip_days'] : [];
        $tour_id = isset($_POST['post_ID']) ? $_POST['post_ID'] : '';
        $time_unit = get_post_meta($tour_id, 'trip_time', true);
        $price_type = get_post_meta($tour_id, 'trip_pricing_type', true);
        $schedule_old_start_date = isset($_POST['schedule_old_start_date']) ? $_POST['schedule_old_start_date'] : '';
        $schedule_old_end_date = isset($_POST['schedule_old_end_date']) ? $_POST['schedule_old_end_date'] : '';
        $schedule_action = isset($_POST['schedule_action']) ? $_POST['schedule_action'] : '';
        $schedule_package_name = isset($_POST['schedule_package_name']) ? sanitize_textarea_field($_POST['schedule_package_name']) : '';

        $tour_package = get_post_meta($tour_id, 'tour_package', true);
        $days = \Togo_Framework\Helper::get_all_days();

        if (!$tour_package) {
            $tour_package = [];
        }

        $schedule = [];
        $schedule['start_date'] = $start_date;
        $schedule['end_date'] = $end_date ? $end_date : 'no_end_date';
        $schedule['time_unit'] = $time_unit ? $time_unit : '';
        $schedule['price_type'] = $price_type;
        if ($time_unit == 'start_times') {
            $schedule['trip_times'] = isset($_POST['trip_times']) ? $_POST['trip_times'] : [];
        } elseif ($time_unit == 'opening_hours') {
            $schedule['opening_hours_days'] = isset($_POST['opening_hours_days']) ? $_POST['opening_hours_days'] : [];
            foreach ($days as $key => $value) {
                $schedule['opening_hours_' . $key . '_start'] = isset($_POST['opening_hours_' . $key . '_start']) ? $_POST['opening_hours_' . $key . '_start'] : [];
                $schedule['opening_hours_' . $key . '_end'] = isset($_POST['opening_hours_' . $key . '_end']) ? $_POST['opening_hours_' . $key . '_end'] : [];
            }
        } elseif ($time_unit == 'many_days') {
            $schedule['many_days_start_time'] = isset($_POST['many_days_start_time']) ? $_POST['many_days_start_time'] : '';
        }

        $schedule['tiered_pricing'] = $tiered_pricing;
        if ($price_type == 'per_person') {
            $min_guests = isset($_POST['min_guests']) ? $_POST['min_guests'] : [];
            $max_guests = isset($_POST['max_guests']) ? $_POST['max_guests'] : [];
            $regular_price = isset($_POST['regular_price']) ? $_POST['regular_price'] : [];
            $sale_price = isset($_POST['sale_price']) ? $_POST['sale_price'] : [];
            $pricing_category = \Togo_Framework\Helper::get_pricing_categories();
            if ($pricing_category) {
                foreach ($pricing_category as $key => $value) {
                    if (!empty($min_guests)) {
                        $schedule['min_guests[' . $key . ']'] = $min_guests[$key];
                    }

                    if (!empty($max_guests)) {
                        $schedule['max_guests[' . $key . ']'] = $max_guests[$key];
                    }

                    if (!empty($regular_price)) {
                        $schedule['regular_price[' . $key . ']'] = $regular_price[$key];
                    }

                    if (!empty($sale_price)) {
                        $schedule['sale_price[' . $key . ']'] = $sale_price[$key];
                    }
                }
            }
        } else {
            $per_group_min_guests = isset($_POST['per_group_min_guests']) ? $_POST['per_group_min_guests'] : [];
            $per_group_max_guests = isset($_POST['per_group_max_guests']) ? $_POST['per_group_max_guests'] : [];
            $per_group_regular_price = isset($_POST['per_group_regular_price']) ? $_POST['per_group_regular_price'] : [];
            $per_group_sale_price = isset($_POST['per_group_sale_price']) ? $_POST['per_group_sale_price'] : [];
            $schedule['per_group_min_guests'] = $per_group_min_guests;
            $schedule['per_group_max_guests'] = $per_group_max_guests;
            $schedule['per_group_regular_price'] = $per_group_regular_price;
            $schedule['per_group_sale_price'] = $per_group_sale_price;
        }

        $schedule['trip_days'] = $trip_days ? $trip_days : [];

        if ($schedule_action == 'update') {
            foreach ($tour_package as $key => $package) {
                if ($package['package_name'] == $schedule_package_name) {
                    $schedules = $tour_package[$key]['schedules'];
                    foreach ($schedules as $key_schedule => $value_schedule) {
                        if (strtotime($value_schedule['start_date']) <= strtotime($start_date) && $value_schedule['end_date'] == 'no_end_date' && ($value_schedule['start_date'] != $schedule_old_start_date && $value_schedule['end_date'] != $schedule_old_end_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if (strtotime($value_schedule['start_date']) <= strtotime($start_date) && strtotime($value_schedule['end_date']) >= strtotime($start_date) && ($value_schedule['start_date'] != $schedule_old_start_date && $value_schedule['end_date'] != $schedule_old_end_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if ($end_date == 'no_end_date' && $value_schedule['end_date'] == 'no_end_date' && ($value_schedule['start_date'] != $schedule_old_start_date && $value_schedule['end_date'] != $schedule_old_end_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if (strtotime($value_schedule['start_date']) <= strtotime($end_date) && $value_schedule['end_date'] == 'no_end_date' && ($value_schedule['start_date'] != $schedule_old_start_date && $value_schedule['end_date'] != $schedule_old_end_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if (strtotime($value_schedule['start_date']) <= strtotime($end_date) && strtotime($value_schedule['end_date']) >= strtotime($end_date) && ($value_schedule['start_date'] != $schedule_old_start_date && $value_schedule['end_date'] != $schedule_old_end_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        }
                        if ($value_schedule['start_date'] == $schedule_old_start_date && $value_schedule['end_date'] == $schedule_old_end_date) {
                            $tour_package[$key]['schedules'][$key_schedule] = $schedule;
                        }
                    }
                }
            }

            update_post_meta($tour_id, 'tour_package', $tour_package);

            $response = [
                'success' => true,
                'class' => 'updated',
                'message' => esc_html__('Schedule has been updated', 'togo')
            ];
        } else {

            foreach ($tour_package as $key => $package) {
                if ($package['package_name'] == $schedule_package_name) {
                    $schedules = $tour_package[$key]['schedules'];
                    foreach ($schedules as $key_schedule => $value_schedule) {
                        if (strtotime($value_schedule['start_date']) <= strtotime($start_date) && $value_schedule['end_date'] == 'no_end_date') {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if (strtotime($value_schedule['start_date']) <= strtotime($start_date) && strtotime($value_schedule['end_date']) >= strtotime($start_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if ($end_date == 'no_end_date' && $value_schedule['end_date'] == 'no_end_date') {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if (strtotime($value_schedule['start_date']) <= strtotime($end_date) && $value_schedule['end_date'] == 'no_end_date') {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        } else if (strtotime($value_schedule['start_date']) <= strtotime($end_date) && strtotime($value_schedule['end_date']) >= strtotime($end_date)) {
                            $response = [
                                'success' => false,
                                'class' => 'error',
                                'message' => esc_html__('The selected date already exists in another schedule.', 'togo')
                            ];
                            echo json_encode($response);
                            wp_die();
                        }
                    }
                    $tour_package[$key]['schedules'][] = $schedule;
                }
            }

            update_post_meta($tour_id, 'tour_package', $tour_package);

            $response = [
                'success' => true,
                'class' => 'updated',
                'message' => esc_html__('Schedule has been created', 'togo')
            ];
        }

        // Return the response
        echo json_encode($response);

        wp_die();
    }
    public function create_package()
    {
        $package_name = isset($_POST['package_name']) ? sanitize_text_field($_POST['package_name']) : '';
        $package_old_name = isset($_POST['package_old_name']) ? sanitize_text_field($_POST['package_old_name']) : '';
        $package_description = isset($_POST['package_description']) ? $_POST['package_description'] : [];
        $package_action = isset($_POST['package_action']) ? $_POST['package_action'] : [];
        $tour_id = isset($_POST['post_ID']) ? $_POST['post_ID'] : '';

        $tour_package = get_post_meta($tour_id, 'tour_package', true);

        if (!$tour_package) {
            $tour_package = [];
        }

        if ($package_action == 'update') {

            foreach ($tour_package as $key => $package) {
                if ($package['package_name'] == $package_name && $package['package_name'] != $package_old_name) {
                    $response = [
                        'success' => false,
                        'class' => 'error',
                        'message' => esc_html__('Package name already exists', 'togo')
                    ];

                    echo json_encode($response);
                    wp_die();
                }
            }

            foreach ($tour_package as $key => $package) {
                if ($package['package_name'] == $package_old_name) {
                    $tour_package[$key]['package_name'] = $package_name;
                    $tour_package[$key]['package_description'] = $package_description;
                    break;
                }
            }

            update_post_meta($tour_id, 'tour_package', $tour_package);

            $response = [
                'success' => true,
                'class' => 'updated',
                'message' => esc_html__('Package updated successfully', 'togo')
            ];
        } else {

            foreach ($tour_package as $key => $package) {
                if ($package['package_name'] == $package_name) {
                    $response = [
                        'success' => false,
                        'class' => 'error',
                        'message' => esc_html__('Package name already exists', 'togo')
                    ];

                    echo json_encode($response);
                    wp_die();
                }
            }


            $tour_package[] = [
                'package_name' => $package_name,
                'package_description' => $package_description,
                'schedules' => array()
            ];

            update_post_meta($tour_id, 'tour_package', $tour_package);

            $response = [
                'success' => true,
                'class' => 'updated',
                'message' => esc_html__('Package created successfully', 'togo')
            ];
        }

        echo json_encode($response);

        wp_die();
    }

    public function edit_package()
    {
        $package_name = isset($_POST['package_name']) ? sanitize_text_field($_POST['package_name']) : '';
        $tour_id = isset($_POST['postid']) ? $_POST['postid'] : '';
        $tour_package = get_post_meta($tour_id, 'tour_package', true);

        if (!$tour_package) {
            $tour_package = [];
        }

        foreach ($tour_package as $key => $package) {
            if ($package['package_name'] == $package_name) {
                $response = [
                    'success' => true,
                    'package_name' => $package_name,
                    'package_description' => $package['package_description'],
                    'modal_title' => esc_html__('Edit Package: ', 'togo') . $package_name,
                    'modal_button' => esc_html__('Update Package', 'togo')
                ];

                echo json_encode($response);

                wp_die();
            }
        }

        wp_die();
    }

    public function delete_package()
    {
        $package_name = isset($_POST['package_name']) ? stripslashes(sanitize_text_field($_POST['package_name'])) : '';
        $tour_id = isset($_POST['postid']) ? $_POST['postid'] : '';

        $tour_package = get_post_meta($tour_id, 'tour_package', true);

        if (!$tour_package) {
            $tour_package = [];
        }

        foreach ($tour_package as $key => $package) {
            if ($package['package_name'] == $package_name) {
                unset($tour_package[$key]);
            }
        }

        update_post_meta($tour_id, 'tour_package', $tour_package);

        $response = [
            'success' => true,
            'class' => 'updated',          // success or error
            'message' => esc_html__('Package deleted successfully', 'togo')
        ];

        echo json_encode($response);

        wp_die();
    }

    public function delete_schedule()
    {
        $package_name = isset($_POST['package_name']) ? sanitize_text_field($_POST['package_name']) : '';
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $tour_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';

        $tour_package = get_post_meta($tour_id, 'tour_package', true);

        if (!$tour_package) {
            $tour_package = [];
        }

        foreach ($tour_package as $key => $package) {
            if ($package['package_name'] == $package_name) {
                $schedule = $tour_package[$key]['schedules'];
                foreach ($schedule as $k => $v) {
                    if ($v['start_date'] == $start_date && $v['end_date'] == $end_date) {
                        unset($tour_package[$key]['schedules'][$k]);
                    }
                }
            }
        }

        update_post_meta($tour_id, 'tour_package', $tour_package);

        $response = [
            'success' => true,
            'class' => 'updated',          // success or error
            'message' => esc_html__('Package deleted successfully', 'togo')
        ];

        echo json_encode($response);

        wp_die();
    }

    public function edit_schedule()
    {
        $package_name = isset($_POST['package_name']) ? sanitize_text_field($_POST['package_name']) : '';
        $tour_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $tour_package = get_post_meta($tour_id, 'tour_package', true);
        $time_unit = get_post_meta($tour_id, 'trip_time', true);
        $trip_pricing_type = get_post_meta($tour_id, 'trip_pricing_type', true);
        $days = \Togo_Framework\Helper::get_all_days();

        if (!$tour_package) {
            $tour_package = [];
        }

        foreach ($tour_package as $key => $package) {
            if ($package['package_name'] == $package_name) {
                $schedule = $tour_package[$key]['schedules'];
                foreach ($schedule as $k => $v) {

                    if ($v['start_date'] == $start_date && $v['end_date'] == $end_date) {
                        if ($end_date == 'no_end_date') {
                            $text_end_date = esc_html__('no end date', 'togo');
                        } else {
                            $text_end_date = $end_date;
                        }
                        $pricing = array();
                        $pricing_clone = array();
                        if ($trip_pricing_type == 'per_person') {
                            $pricing_category = \Togo_Framework\Helper::get_pricing_categories();

                            if ($pricing_category) {
                                foreach ($pricing_category as $pc_key => $pc_value) {
                                    $count = 0;
                                    if ($v['min_guests[' . $pc_key . ']']) {
                                        $count = count($v['min_guests[' . $pc_key . ']']);
                                    }
                                    
                                    $clone_key_name = $pc_key . '_clone';
                                    $pricing_clone[$clone_key_name] = $count - 2; 
                                    if ($count - 2 < 0) {
                                        $pricing_clone[$clone_key_name] = 0;
                                    }
                                    for ($i = 0; $i < $count; $i++) {
                                        $pricing['min_guests[' . $pc_key . ']'][] = $v['min_guests[' . $pc_key . ']'][$i];
                                        $pricing['max_guests[' . $pc_key . ']'][] = $v['max_guests[' . $pc_key . ']'][$i];
                                        $pricing['regular_price[' . $pc_key . ']'][] = $v['regular_price[' . $pc_key . ']'][$i];
                                        $pricing['sale_price[' . $pc_key . ']'][] = $v['sale_price[' . $pc_key . ']'][$i];
                                    }
                                }
                            }
                        } elseif (array_key_exists('per_group_regular_price', $v) && $trip_pricing_type == 'per_group') {
                            $count = count($v['per_group_regular_price']);

                            $pricing_clone[] = $count - 2;
                            for ($i = 0; $i < $count; $i++) {
                                $pricing['per_group_min_guests'][] = $v['per_group_min_guests'][$i];
                                $pricing['per_group_max_guests'][] = $v['per_group_max_guests'][$i];
                                $pricing['per_group_regular_price'][] = $v['per_group_regular_price'][$i];
                                $pricing['per_group_sale_price'][] = $v['per_group_sale_price'][$i];
                            }
                        }



                        $response = [
                            'success' => true,
                            'package_name' => $package_name,
                            'start_date' => $v['start_date'],
                            'end_date' => $v['end_date'],
                            'price_type' => $trip_pricing_type,
                            'tiered_pricing' => $v['tiered_pricing'],
                            'pricing' => $pricing,
                            'pricing_clone' => $pricing_clone,
                            'trip_days' => $v['trip_days'],
                            'modal_title' => esc_html__('Edit Schedule: ', 'togo') . $start_date . ' - ' . $text_end_date,
                            'modal_button' => esc_html__('Update Schedule', 'togo')
                        ];

                        if (array_key_exists('trip_times', $v) && $time_unit == 'start_times') {
                            $response['time_unit'] = $time_unit;
                            $response['trip_times'] = $v['trip_times'];
                        } elseif (array_key_exists('opening_hours_days', $v) && $time_unit == 'opening_hours') {
                            $response['time_unit'] = $time_unit;
                            $opening_hours_days = $v['opening_hours_days'];
                            $html = '';
                            foreach ($days as $d_key => $d_value) {
                                $opening_hours_start = array_filter($v['opening_hours_' . $d_key . '_start']);
                                $opening_hours_end = array_filter($v['opening_hours_' . $d_key . '_end']);
                                if (in_array($d_key, $opening_hours_days)) {
                                    $checked = 'checked';
                                    $disabled = '';
                                } else {
                                    $checked = '';
                                    $disabled = 'disabled';
                                }
                                $html .= '<div class="opening-hours-item">';
                                $html .= '<div class="day-name"><input type="checkbox" name="opening_hours_days[]" value="' . $d_key . '" ' . $checked . '><span>' . $d_value . '</span></div>';
                                $html .= '<div class="times">';
                                if (empty($opening_hours_start)) {
                                    $html .= '<div class="time">';
                                    $html .= '<input type="time" name="opening_hours_' . $d_key . '_start[]" ' . $disabled . '>';
                                    $html .= '<span>-</span>';
                                    $html .= '<input type="time" name="opening_hours_' . $d_key . '_end[]" ' . $disabled . '>';
                                    $html .= '<a href="#" class="add-opening-hours-time">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 9V19.4C3 19.9601 3 20.2399 3.10899 20.4538C3.20487 20.642 3.35774 20.7952 3.5459 20.8911C3.7596 21 4.0395 21 4.59846 21H15.0001M14 13V10M14 10V7M14 10H11M14 10H17M7 13.8002V6.2002C7 5.08009 7 4.51962 7.21799 4.0918C7.40973 3.71547 7.71547 3.40973 8.0918 3.21799C8.51962 3 9.08009 3 10.2002 3H17.8002C18.9203 3 19.4801 3 19.9079 3.21799C20.2842 3.40973 20.5905 3.71547 20.7822 4.0918C21.0002 4.51962 21.0002 5.07969 21.0002 6.19978L21.0002 13.7998C21.0002 14.9199 21.0002 15.48 20.7822 15.9078C20.5905 16.2841 20.2842 16.5905 19.9079 16.7822C19.4805 17 18.9215 17 17.8036 17H10.1969C9.07899 17 8.5192 17 8.0918 16.7822C7.71547 16.5905 7.40973 16.2842 7.21799 15.9079C7 15.4801 7 14.9203 7 13.8002Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>';
                                    $html .= '</div>';
                                } else {
                                    foreach ($opening_hours_start as $oh_key => $oh_value) {
                                        $html .= '<div class="time">';
                                        $html .= '<input type="time" name="opening_hours_' . $d_key . '_start[]" value="' . $oh_value . '" ' . $disabled . '>';
                                        $html .= '<span>-</span>';
                                        $html .= '<input type="time" name="opening_hours_' . $d_key . '_end[]" value="' . $opening_hours_end[$oh_key] . '" ' . $disabled . '>';
                                        if ($oh_key == 0) {
                                            $html .= '<a href="#" class="add-opening-hours-time">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 9V19.4C3 19.9601 3 20.2399 3.10899 20.4538C3.20487 20.642 3.35774 20.7952 3.5459 20.8911C3.7596 21 4.0395 21 4.59846 21H15.0001M14 13V10M14 10V7M14 10H11M14 10H17M7 13.8002V6.2002C7 5.08009 7 4.51962 7.21799 4.0918C7.40973 3.71547 7.71547 3.40973 8.0918 3.21799C8.51962 3 9.08009 3 10.2002 3H17.8002C18.9203 3 19.4801 3 19.9079 3.21799C20.2842 3.40973 20.5905 3.71547 20.7822 4.0918C21.0002 4.51962 21.0002 5.07969 21.0002 6.19978L21.0002 13.7998C21.0002 14.9199 21.0002 15.48 20.7822 15.9078C20.5905 16.2841 20.2842 16.5905 19.9079 16.7822C19.4805 17 18.9215 17 17.8036 17H10.1969C9.07899 17 8.5192 17 8.0918 16.7822C7.71547 16.5905 7.40973 16.2842 7.21799 15.9079C7 15.4801 7 14.9203 7 13.8002Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>';
                                        } else {
                                            $html .= '<a href="#" class="remove_opening-hours-time">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.28613 8.57153L7.42899 20.0001H16.5718L17.7147 8.57153" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M13.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M4.57129 6.28571H9.14272M9.14272 6.28571L9.52478 4.75746C9.63607 4.3123 10.0361 4 10.4949 4H13.5048C13.9637 4 14.3636 4.3123 14.4749 4.75746L14.857 6.28571M9.14272 6.28571H14.857M14.857 6.28571H19.4284" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>';
                                        }

                                        $html .= '</div>';
                                    }
                                }
                                $html .= '</div>';
                                $html .= '<div class="field-time-clone">';
                                $html .= '<div class="time">';
                                $html .= '<input type="time" name="opening_hours_' . $d_key . '_start[]" disabled>';
                                $html .= '<span>-</span>';
                                $html .= '<input type="time" name="opening_hours_' . $d_key . '_end[]" disabled>';
                                $html .= '<a href="#" class="remove_opening-hours-time">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.28613 8.57153L7.42899 20.0001H16.5718L17.7147 8.57153" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M13.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10.5 15.5V10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M4.57129 6.28571H9.14272M9.14272 6.28571L9.52478 4.75746C9.63607 4.3123 10.0361 4 10.4949 4H13.5048C13.9637 4 14.3636 4.3123 14.4749 4.75746L14.857 6.28571M9.14272 6.28571H14.857M14.857 6.28571H19.4284" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>';
                                $html .= '</div>';
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            $response['opening_hours'] = $html;
                        } elseif (array_key_exists('many_days_start_time', $v) && $time_unit == 'many_days') {
                            $response['time_unit'] = $time_unit;
                            $response['many_days_start_time'] = $v['many_days_start_time'];
                        }

                        echo json_encode($response);

                        wp_die();
                    }
                }
            }
        }

        wp_die();
    }
}
