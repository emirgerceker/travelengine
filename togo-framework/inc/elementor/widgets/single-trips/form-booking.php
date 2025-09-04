<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Single_Trips;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Widget_Form_Booking extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-form-booking';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Form Booking', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-form-horizontal';
    }

    public function get_categories()
    {
        return ['single-trips'];
    }

    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-single-trip-form-booking');
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_enquiry_section();
    }

    /**
     * Add content section.
     *
     * @return void
     */
    protected function add_content_section()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => '01',
                'options' => [
                    '01' => __('01', 'togo-framework'),
                    '02' => __('02', 'togo-framework'),
                    '03' => __('03', 'togo-framework'),
                    '04' => __('04', 'togo-framework'),
                ],
            ]
        );

        $this->add_control(
            'affiliate_link',
            [
                'label' => __('Affiliate Link', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'default' => ['url' => 'https://your-link.com'],
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add enquiry section.
     *
     * @return void
     */
    protected function add_content_enquiry_section()
    {
        $this->start_controls_section(
            'content_enquiry_section',
            [
                'label' => __('Enquiry', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'short_description',
            [
                'label' => __('Short Description', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Have a question before booking? Message us to learn more.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'enquiry_form_fields',
            [
                'label' => __('Enquiry', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'text',
                        'label' => __('Placeholder', 'togo-framework'),
                        'default' => __('First name', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],
                    [
                        'name' => 'type',
                        'label' => __('Type', 'togo-framework'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'text' => __('Text', 'togo-framework'),
                            'email' => __('Email', 'togo-framework'),
                            'tel' => __('Tel', 'togo-framework'),
                            'checkbox' => __('Checkbox', 'togo-framework'),
                            'radio' => __('Radio', 'togo-framework'),
                            'select' => __('Select', 'togo-framework'),
                            'textarea' => __('Textarea', 'togo-framework'),
                            'number' => __('Number', 'togo-framework'),
                            'date' => __('Date', 'togo-framework'),
                        ],
                    ],
                    [
                        'name' => 'options',
                        'label' => __('Options', 'togo-framework'),
                        'type' => Controls_Manager::TEXTAREA,
                        'description' => __('Enter options separated by a comma.', 'togo-framework'),
                        'condition' => [
                            'type' => ['select', 'checkbox', 'radio'],
                        ],
                    ],
                    [
                        'name' => 'required',
                        'label' => __('Required', 'togo-framework'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                    ],
                    [
                        'name' => 'half_row_width',
                        'label' => __('Half-row Width', 'togo-framework'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                        'condition' => [
                            'type!' => ['textarea'],
                        ],
                    ],
                ],
                'default' => [
                    [
                        'text' => __('First name', 'togo-framework'),
                        'type' => 'text',
                        'required' => 'yes',
                        'half_row_width' => 'yes',
                    ],
                    [
                        'text' => __('Last name', 'togo-framework'),
                        'type' => 'text',
                        'required' => 'yes',
                        'half_row_width' => 'yes',
                    ],
                    [
                        'text' => __('Email', 'togo-framework'),
                        'type' => 'email',
                        'required' => 'yes',
                    ],
                    [
                        'text' => __('Phone', 'togo-framework'),
                        'type' => 'tel',
                        'required' => 'yes',
                    ],
                    [
                        'text' => __('Your question', 'togo-framework'),
                        'type' => 'textarea',
                        'required' => 'yes',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output.
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = get_the_ID();
        if (!is_singular('togo_trip')) {
            return;
        }
        $tour_package = \Togo_Framework\Helper::get_price_of_trip($id);
        $percentage_discount_price = \Togo_Framework\Helper::get_percentage_discount_price($id);
        $check_price_on_calendar = \Togo_Framework\Helper::check_price_on_calendar($id);
        $trip_pricing_type = get_post_meta($id, 'trip_pricing_type', true);
        $terms = wp_get_post_terms($id, 'togo_trip_pricing_categories');
        $layout = $settings['layout'];
        $short_description = $settings['short_description'];
        $enquiry_form_fields = $settings['enquiry_form_fields'];
?>
        <div class="togo-st-form-booking">
            <div class="togo-st-form-booking__top">
                <?php echo $tour_package; ?>
                <?php echo $percentage_discount_price; ?>
            </div>
            <div class="togo-st-form-booking__main">
                <?php
                if ($layout == '01') {
                    $this->layout_01($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings);
                } elseif ($layout == '02') {
                    $this->layout_02($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings);
                } elseif ($layout == '03') {
                    $this->layout_03($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings);
                } elseif ($layout == '04') {
                    $this->layout_04($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings);
                }
                ?>
            </div>
        </div>
        <?php
        if ($layout == '01' || $layout == '04') {
        ?>
            <div id="modal-enquiry" class="togo-modal">
                <div class="togo-modal-overlay"></div>
                <div class="togo-modal-content">
                    <div class="togo-modal-header">
                        <h3 class="togo-modal-title">
                            <?php echo esc_html__('Make enquiry', 'togo'); ?>
                        </h3>
                        <div class="togo-modal-close">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6.00005L6 18M5.99995 6L17.9999 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="togo-modal-body">
                        <p><?php echo esc_html($short_description); ?></p>
                        <?php
                        if (!empty($enquiry_form_fields) && !is_wp_error($enquiry_form_fields)) {
                            $list_name = [];
                            echo '<form action="#" method="post" class="enquiry-form">';
                            foreach ($enquiry_form_fields as $field) {
                                $half_row_width = $field['half_row_width'] == 'yes' ? 'half-row-width' : '';
                                $required = $field['required'] == 'yes' ? 'required' : '';
                                $required_icon = $field['required'] == 'yes' ? '<span class="required">*</span>' : '';
                                $name = str_replace("-", "_", sanitize_title($field['text']));
                                array_push($list_name, $name);
                                if ($field['type'] == 'text') {
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<input type="text" name="' . $name . '" id="field-' . sanitize_title($field['text']) . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                    echo '</div>';
                                } elseif ($field['type'] == 'email') {
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<input type="email" id="field-' . $name . '" name="' . $name . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                    echo '</div>';
                                } elseif ($field['type'] == 'tel') {
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<input type="tel" id="field-' . $name . '" name="' . $name . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                    echo '</div>';
                                } elseif ($field['type'] == 'select') {
                                    $options = $field['options'] ? explode(',', $field['options']) : [];
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                                    echo '<select id="field-' . $field['text'] . '" name="' . $name . '" ' . $required . '>';
                                    foreach ($options as $option) {
                                        echo '<option value="' . sanitize_title($option) . '">' . esc_html($option) . '</option>';
                                    }
                                    echo '</select>';
                                    echo '</div>';
                                } elseif ($field['type'] == 'checkbox') {
                                    $options = $field['options'] ? explode(',', $field['options']) : [];
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                                    foreach ($options as $option) {
                                        echo '<div class="checkbox-item">';
                                        echo '<input type="checkbox" id="field-' . sanitize_title($option) . '" name="' . $name . '[]" value="' . sanitize_title($option) . '">';
                                        echo '<label for="field-' . sanitize_title($option) . '">' . esc_html($option) . '</label>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                } elseif ($field['type'] == 'radio') {
                                    $options = $field['options'] ? explode(',', $field['options']) : [];
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                                    foreach ($options as $option) {
                                        echo '<div class="radio-item">';
                                        echo '<input type="radio" id="field-' . sanitize_title($option) . '" name="' . $name . '" value="' . sanitize_title($option) . '" ' . $required . '>';
                                        echo '<label for="field-' . sanitize_title($option) . '">' . esc_html($option) . '</label>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                } elseif ($field['type'] == 'textarea') {
                                    echo '<div class="form-group full-row-width">';
                                    echo '<textarea id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '></textarea>';
                                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                    echo '</div>';
                                } elseif ($field['type'] == 'number') {
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<input type="number" id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                    echo '</div>';
                                } elseif ($field['type'] == 'date') {
                                    echo '<div class="form-group ' . $half_row_width . '">';
                                    echo '<input type="date" id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                    echo '</div>';
                                }
                            }
                            echo '<div class="form-submit">';
                            echo '<p class="notice"></p>';
                            echo '<input type="hidden" name="trip_id" value="' . esc_attr($id) . '">';
                            echo '<input type="hidden" name="action" value="togo_send_enquiry">';
                            echo '<input type="hidden" name="list_name" value="' . implode(',', $list_name) . '">';
                            echo '<input type="hidden" name="nonce" value="' . wp_create_nonce('togo_send_enquiry') . '">';
                            echo '<button type="submit" class="togo-button full-filled">' . esc_html__('Send enquiry', 'togo') . '</button>';
                            echo '</div>';
                            echo '</form>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }
    }

    protected function layout_01($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings)
    {
        $trip_minimum_guests = get_post_meta($id, 'trip_minimum_guests', true) ? get_post_meta($id, 'trip_minimum_guests', true) : 0;
        $trip_maximum_guests = get_post_meta($id, 'trip_maximum_guests', true) ? get_post_meta($id, 'trip_maximum_guests', true) : 9999;
        ?>
        <form action="#" method="post" class="booking-form" data-layout="01">
            <div class="form-group">
                <div class="form-field">
                    <div class="label">
                        <?php echo \Togo\Icon::get_svg('calendar'); ?>
                        <span class="name"><?php echo esc_html__('Date', 'togo'); ?></span>
                    </div>
                    <?php echo \Togo_Framework\Template::render_calendar($check_price_on_calendar); ?>
                    <input type="hidden" name="booking_date">
                </div>
                <div class="form-field">
                    <div class="label">
                        <?php echo \Togo\Icon::get_svg('users-group'); ?>
                        <span class="name"><?php echo esc_html__('Guest', 'togo'); ?></span>
                    </div>
                    <?php
                    if ($trip_pricing_type == 'per_person') {
                        $trip_enable_min_max_person = get_post_meta($id, 'trip_enable_min_max_person', true);
                    ?>
                        <div class="guest-box">
                            <?php
                            if (!empty($trip_minimum_guests) && $trip_minimum_guests > 0) {
                                echo '<p class="notice">' . sprintf(esc_html__('You can select between %s to %s travelers in total.', 'togo_framework'), $trip_minimum_guests, $trip_maximum_guests) . '</p>';
                            } else {
                                echo '<p class="notice">' . sprintf(esc_html__('You can select up to %s travelers in total.', 'togo_framework'), $trip_maximum_guests) . '</p>';
                            }
                            ?>
                            <div class="guest-box__items">
                                <?php
                                if (!empty($terms) && !is_wp_error($terms)) {
                                    foreach ($terms as $term) {
                                        $togo_trip_pricing_categories_min_age = get_term_meta($term->term_id, 'togo_trip_pricing_categories_min_age', true);
                                        $togo_trip_pricing_categories_max_age = get_term_meta($term->term_id, 'togo_trip_pricing_categories_max_age', true);
                                        $trip_min_guests = get_post_meta($id, 'trip_min_guests_' . $term->slug, true) ? get_post_meta($id, 'trip_min_guests_' . $term->slug, true) : 0;
                                        $trip_max_guests = get_post_meta($id, 'trip_max_guests_' . $term->slug, true) ? get_post_meta($id, 'trip_max_guests_' . $term->slug, true) : 9999;
                                        echo '<div class="guest-box__item">';
                                        echo '<div class="name">';
                                        echo '<span class="text">' . $term->name . '</span>';
                                        if ($togo_trip_pricing_categories_max_age == '') {
                                            echo '<span class="number">' . esc_html__('Age ', 'togo') . $togo_trip_pricing_categories_min_age . '+</span>';
                                        } else {
                                            echo '<span class="number">' . esc_html__('Age ', 'togo') . $togo_trip_pricing_categories_min_age . ' - ' . $togo_trip_pricing_categories_max_age . '</span>';
                                        }
                                        if ($trip_enable_min_max_person && $trip_min_guests != '' && $trip_max_guests) {
                                            echo '<span class="number">' . esc_html__('Min. ', 'togo') . $trip_min_guests . ', ' . esc_html__('Max. ', 'togo') . $trip_max_guests . '</span>';
                                        }
                                        echo '</div>';
                                        echo '<div class="quantity">';
                                        echo '<div class="quantity-input">';
                                        echo '<span class="minus disabled">-</span>';
                                        echo '<span class="number"><input type="number" min="' . $trip_min_guests . '" max="' . $trip_max_guests . '" value="' . $trip_min_guests . '" name="guests[]"></span>';
                                        echo '<span class="plus">+</span>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="quantity-button">
                                <p class="notice error"><?php echo esc_html__('Please select guests', 'togo'); ?></p>
                                <a href="#" class="apply-guest <?php if (!empty($trip_minimum_guests)) echo 'disabled'; ?>"><?php echo esc_html__('Apply', 'togo'); ?></a>
                            </div>
                        </div>
                    <?php
                    } else {
                        $trip_enable_min_max_group = get_post_meta($id, 'trip_enable_min_max_group', true);
                    ?>
                        <div class="guest-box">
                            <div class="guest-box__items">
                                <?php
                                echo '<div class="guest-box__item">';
                                echo '<div class="name">';
                                echo '<span class="text">' . esc_html__('Traveler', 'togo') . '</span>';
                                if ($trip_enable_min_max_group) {
                                    echo '<span class="number">' . esc_html__('Min. ', 'togo') . $trip_minimum_guests . ', ' . esc_html__('Max. ', 'togo') . $trip_maximum_guests . '</span>';
                                }
                                echo '</div>';
                                echo '<div class="quantity">';
                                echo '<div class="quantity-input">';
                                echo '<span class="minus disabled">-</span>';
                                echo '<span class="number"><input type="number" min="' . $trip_minimum_guests . '" max="' . $trip_maximum_guests . '" value="' . $trip_minimum_guests . '" name="guests[]"></span>';
                                echo '<span class="plus">+</span>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                ?>
                            </div>
                            <div class="quantity-button">
                                <p class="notice error"><?php echo esc_html__('Please select guests', 'togo'); ?></p>
                                <a href="#" class="apply-guest <?php if (!empty($trip_minimum_guests)) echo 'disabled'; ?>"><?php echo esc_html__('Apply', 'togo'); ?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-submit">
                <input type="hidden" name="maximum_guests" value="<?php echo esc_attr($trip_maximum_guests); ?>">
                <input type="hidden" name="minimum_guests" value="<?php echo esc_attr($trip_minimum_guests); ?>">
                <input type="hidden" name="trip_id" value="<?php echo esc_attr($id); ?>">
                <input type="hidden" name="action" value="togo_check_availability">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('togo_check_availability'); ?>">
                <button type="submit" <?php if (!empty($trip_minimum_guests)) echo 'disabled'; ?> class="togo-button full-filled"><?php echo esc_html__('Check availability', 'togo'); ?></button>
                <a href="#modal-enquiry" class="togo-button line togo-open-modal"><?php echo esc_html__('Make enquiry', 'togo'); ?></a>
            </div>
        </form>
    <?php
    }

    protected function layout_02($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings)
    {
        $short_description = $settings['short_description'];
        $enquiry_form_fields = $settings['enquiry_form_fields'];
    ?>
        <p><?php echo esc_html($short_description); ?></p>
        <?php
        if (!empty($enquiry_form_fields) && !is_wp_error($enquiry_form_fields)) {
            $list_name = [];
            echo '<form action="#" method="post" class="enquiry-form">';
            foreach ($enquiry_form_fields as $field) {
                $half_row_width = $field['half_row_width'] == 'yes' ? 'half-row-width' : '';
                $required = $field['required'] == 'yes' ? 'required' : '';
                $required_icon = $field['required'] == 'yes' ? '<span class="required">*</span>' : '';
                $options = $field['options'] ? explode(',', $field['options']) : [];
                $name = str_replace("-", "_", sanitize_title($field['text']));
                array_push($list_name, $name);
                if ($field['type'] == 'text') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<input type="text" name="' . $name . '" id="field-' . sanitize_title($field['text']) . '" ' . $required . '>';
                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                    echo '</div>';
                } elseif ($field['type'] == 'email') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<input type="email" id="field-' . $name . '" name="' . sanitize_title($field['text']) . '" ' . $required . '>';
                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                    echo '</div>';
                } elseif ($field['type'] == 'tel') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<input type="tel" id="field-' . $name . '" name="' . sanitize_title($field['text']) . '" ' . $required . '>';
                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                    echo '</div>';
                } elseif ($field['type'] == 'select') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                    echo '<select id="field-' . $field['text'] . '" name="' . $name . '" ' . $required . '>';
                    foreach ($options as $option) {
                        echo '<option value="' . sanitize_title($option) . '">' . esc_html($option) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                } elseif ($field['type'] == 'checkbox') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                    foreach ($options as $option) {
                        echo '<div class="checkbox-item">';
                        echo '<input type="checkbox" id="field-' . sanitize_title($option) . '" name="' . $name . '" value="' . sanitize_title($option) . '" ' . $required . '>';
                        echo '<label for="field-' . sanitize_title($option) . '">' . esc_html($option) . '</label>';
                        echo '</div>';
                    }
                    echo '</div>';
                } elseif ($field['type'] == 'radio') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                    foreach ($options as $option) {
                        echo '<div class="radio-item">';
                        echo '<input type="radio" id="field-' . sanitize_title($option) . '" name="' . $name . '" value="' . sanitize_title($option) . '" ' . $required . '>';
                        echo '<label for="field-' . sanitize_title($option) . '">' . esc_html($option) . '</label>';
                        echo '</div>';
                    }
                    echo '</div>';
                } elseif ($field['type'] == 'textarea') {
                    echo '<div class="form-group full-row-width">';
                    echo '<textarea id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '></textarea>';
                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                    echo '</div>';
                } elseif ($field['type'] == 'number') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<input type="number" id="field-' . $name . '" name="' . sanitize_title($field['text']) . '" ' . $required . '>';
                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                    echo '</div>';
                } elseif ($field['type'] == 'date') {
                    echo '<div class="form-group ' . $half_row_width . '">';
                    echo '<input type="date" id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '>';
                    echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                    echo '</div>';
                }
            }
            echo '<div class="form-submit">';
            echo '<p class="notice"></p>';
            echo '<input type="hidden" name="trip_id" value="' . esc_attr($id) . '">';
            echo '<input type="hidden" name="action" value="togo_send_enquiry">';
            echo '<input type="hidden" name="list_name" value="' . implode(',', $list_name) . '">';
            echo '<input type="hidden" name="nonce" value="' . wp_create_nonce('togo_send_enquiry') . '">';
            echo '<button type="submit" class="togo-button full-filled">' . esc_html__('Send enquiry', 'togo') . '</button>';
            echo '</div>';
            echo '</form>';
        }
        ?>
    <?php
    }

    protected function layout_03($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings)
    {
        $short_description = $settings['short_description'];
        $enquiry_form_fields = $settings['enquiry_form_fields'];
    ?>
        <div class="booking-tabs">
            <nav>
                <ul>
                    <li class="is-active"><a href="#book"><?php echo esc_html__('Book', 'togo'); ?></a></li>
                    <li><a href="#enquiry"><?php echo esc_html__('Enquiry', 'togo'); ?></a></li>
                </ul>
            </nav>
            <div class="booking-tab-content">
                <div class="booking-tab-content-item is-active" id="book">
                    <form action="#" method="post" class="booking-form" data-layout="03">
                        <div class="form-group">
                            <div class="form-field">
                                <div class="label date">
                                    <span class="name"><?php echo esc_html__('Date', 'togo'); ?></span>
                                    <span class="choose-date"><?php echo date_i18n(get_option('date_format')); ?></span>
                                </div>
                                <?php echo \Togo_Framework\Template::render_calendar($check_price_on_calendar); ?>
                                <input type="hidden" name="booking_date">
                            </div>
                            <div class="form-field">
                                <?php
                                if ($trip_pricing_type == 'per_person') {
                                    $trip_enable_min_max_person = get_post_meta($id, 'trip_enable_min_max_person', true);
                                ?>
                                    <div class="guest-box">
                                        <div class="guest-box__items">
                                            <?php
                                            if (!empty($terms) && !is_wp_error($terms)) {
                                                foreach ($terms as $term) {
                                                    $togo_trip_pricing_categories_min_age = get_term_meta($term->term_id, 'togo_trip_pricing_categories_min_age', true);
                                                    $togo_trip_pricing_categories_max_age = get_term_meta($term->term_id, 'togo_trip_pricing_categories_max_age', true);
                                                    $trip_min_guests = get_post_meta($id, 'trip_min_guests_' . $term->slug, true) ? get_post_meta($id, 'trip_min_guests_' . $term->slug, true) : 0;
                                                    $trip_max_guests = get_post_meta($id, 'trip_max_guests_' . $term->slug, true) ? get_post_meta($id, 'trip_max_guests_' . $term->slug, true) : 9999;
                                                    echo '<div class="guest-box__item">';
                                                    echo '<div class="name">';
                                                    echo '<span class="text">';
                                                    echo $term->name;
                                                    if ($togo_trip_pricing_categories_max_age == '') {
                                                        echo '<span class="number">(' . esc_html__('Age ', 'togo') . $togo_trip_pricing_categories_min_age . '+)</span>';
                                                    } else {
                                                        echo '<span class="number">(' . esc_html__('Age ', 'togo') . $togo_trip_pricing_categories_min_age . ' - ' . $togo_trip_pricing_categories_max_age . ')</span>';
                                                    }
                                                    echo '</span>';
                                                    if ($trip_enable_min_max_person && $trip_min_guests != '' && $trip_max_guests) {
                                                        echo '<span class="number">' . esc_html__('Min. ', 'togo') . $trip_min_guests . ', ' . esc_html__('Max. ', 'togo') . $trip_max_guests . '</span>';
                                                    }
                                                    echo '</div>';
                                                    echo '<div class="quantity">';
                                                    echo '<div class="quantity-input">';
                                                    echo '<span class="minus disabled">-</span>';
                                                    echo '<span class="number"><input type="number" min="' . $trip_min_guests . '" max="' . $trip_max_guests . '" value="' . $trip_min_guests . '" name="guests[]"></span>';
                                                    echo '<span class="plus">+</span>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    $trip_enable_min_max_group = get_post_meta($id, 'trip_enable_min_max_group', true);
                                    $trip_minimum_guests = get_post_meta($id, 'trip_minimum_guests', true) ? get_post_meta($id, 'trip_minimum_guests', true) : 0;
                                    $trip_maximum_guests = get_post_meta($id, 'trip_maximum_guests', true) ? get_post_meta($id, 'trip_maximum_guests', true) : 9999;
                                ?>
                                    <div class="guest-box">
                                        <div class="guest-box__items">
                                            <?php
                                            echo '<div class="guest-box__item">';
                                            echo '<div class="name">';
                                            echo '<span class="text">' . esc_html__('Traveler', 'togo') . '</span>';
                                            if ($trip_enable_min_max_group) {
                                                echo '<span class="number">' . esc_html__('Min. ', 'togo') . $trip_minimum_guests . ', ' . esc_html__('Max. ', 'togo') . $trip_maximum_guests . '</span>';
                                            }
                                            echo '</div>';
                                            echo '<div class="quantity">';
                                            echo '<div class="quantity-input">';
                                            echo '<span class="minus disabled">-</span>';
                                            echo '<span class="number"><input type="number" min="' . $trip_minimum_guests . '" max="' . $trip_maximum_guests . '" value="' . $trip_minimum_guests . '" name="guests[]"></span>';
                                            echo '<span class="plus">+</span>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            ?>
                                        </div>
                                        <div class="quantity-button">
                                            <p class="notice error"><?php echo esc_html__('Please select guests', 'togo'); ?></p>
                                            <a href="#" class="apply-guest"><?php echo esc_html__('Apply', 'togo'); ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-submit">
                            <input type="hidden" name="trip_id" value="<?php echo esc_attr($id); ?>">
                            <input type="hidden" name="action" value="togo_check_availability">
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('togo_check_availability'); ?>">
                            <button type="submit" class="togo-button full-filled"><?php echo esc_html__('Check availability', 'togo'); ?></button>
                        </div>
                    </form>
                </div>
                <div class="booking-tab-content-item" id="enquiry">
                    <p><?php echo esc_html($short_description); ?></p>
                    <?php
                    if (!empty($enquiry_form_fields) && !is_wp_error($enquiry_form_fields)) {
                        $list_name = [];
                        echo '<form action="#" method="post" class="enquiry-form">';
                        foreach ($enquiry_form_fields as $field) {
                            $half_row_width = $field['half_row_width'] == 'yes' ? 'half-row-width' : '';
                            $required = $field['required'] == 'yes' ? 'required' : '';
                            $required_icon = $field['required'] == 'yes' ? '<span class="required">*</span>' : '';
                            $options = $field['options'] ? explode(',', $field['options']) : [];
                            $name = str_replace("-", "_", sanitize_title($field['text']));
                            array_push($list_name, $name);
                            if ($field['type'] == 'text') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<input type="text" name="' . $name . '" id="field-' . sanitize_title($field['text']) . '" ' . $required . '>';
                                echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                echo '</div>';
                            } elseif ($field['type'] == 'email') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<input type="email" id="field-' . $name . '" name="' . sanitize_title($field['text']) . '" ' . $required . '>';
                                echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                echo '</div>';
                            } elseif ($field['type'] == 'tel') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<input type="tel" id="field-' . $name . '" name="' . sanitize_title($field['text']) . '" ' . $required . '>';
                                echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                echo '</div>';
                            } elseif ($field['type'] == 'select') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                                echo '<select id="field-' . $field['text'] . '" name="' . $name . '" ' . $required . '>';
                                foreach ($options as $option) {
                                    echo '<option value="' . sanitize_title($option) . '">' . esc_html($option) . '</option>';
                                }
                                echo '</select>';
                                echo '</div>';
                            } elseif ($field['type'] == 'checkbox') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                                foreach ($options as $option) {
                                    echo '<div class="checkbox-item">';
                                    echo '<input type="checkbox" id="field-' . sanitize_title($option) . '" name="' . $name . '[]" value="' . sanitize_title($option) . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($option) . '">' . esc_html($option) . '</label>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } elseif ($field['type'] == 'radio') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<h3 class="checkbox-title">' . esc_html($field['text']) . $required_icon . '</h3>';
                                foreach ($options as $option) {
                                    echo '<div class="radio-item">';
                                    echo '<input type="radio" id="field-' . sanitize_title($option) . '" name="' . $name . '" value="' . sanitize_title($option) . '" ' . $required . '>';
                                    echo '<label for="field-' . sanitize_title($option) . '">' . esc_html($option) . '</label>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } elseif ($field['type'] == 'textarea') {
                                echo '<div class="form-group full-row-width">';
                                echo '<textarea id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '></textarea>';
                                echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                echo '</div>';
                            } elseif ($field['type'] == 'number') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<input type="number" id="field-' . $name . '" name="' . sanitize_title($field['text']) . '" ' . $required . '>';
                                echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                echo '</div>';
                            } elseif ($field['type'] == 'date') {
                                echo '<div class="form-group ' . $half_row_width . '">';
                                echo '<input type="date" id="field-' . sanitize_title($field['text']) . '" name="' . $name . '" ' . $required . '>';
                                echo '<label for="field-' . sanitize_title($field['text']) . '" class="custom-placeholder">' . esc_html($field['text']) . $required_icon . '</label>';
                                echo '</div>';
                            }
                        }
                        echo '<div class="form-submit">';
                        echo '<p class="notice"></p>';
                        echo '<input type="hidden" name="trip_id" value="' . esc_attr($id) . '">';
                        echo '<input type="hidden" name="action" value="togo_send_enquiry">';
                        echo '<input type="hidden" name="list_name" value="' . implode(',', $list_name) . '">';
                        echo '<input type="hidden" name="nonce" value="' . wp_create_nonce('togo_send_enquiry') . '">';
                        echo '<button type="submit" class="togo-button full-filled">' . esc_html__('Send enquiry', 'togo') . '</button>';
                        echo '</div>';
                        echo '</form>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
    }

    protected function layout_04($id, $trip_pricing_type, $terms, $check_price_on_calendar, $settings)
    {
        $affiliate_link = $settings['affiliate_link'];
        $short_description = $settings['short_description'];
        $enquiry_form_fields = $settings['enquiry_form_fields'];
    ?>
        <div class="booking-affiliate">
            <a href="<?php echo $affiliate_link['url']; ?>" target="_blank" class="togo-button full-filled"><?php echo esc_html__('Book now', 'togo-framework'); ?><?php echo \Togo\Icon::get_svg('external-link'); ?></a>
            <a href="#modal-enquiry" class="togo-button line togo-open-modal"><?php echo esc_html__('Make enquiry', 'togo-framework'); ?></a>
        </div>
<?php
    }
}
