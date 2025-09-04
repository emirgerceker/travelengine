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
class Widget_Overview extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-overview';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Overview', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-radio';
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
        return array('togo-widget-single-trip-overview');
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_heading_section();
        $this->add_content_heading_style_section();
        $this->add_content_list_style_section();
        $this->add_content_description_style_section();
    }

    protected function add_content_heading_section()
    {
        $this->start_controls_section(
            'content_heading',
            [
                'label' => __('Heading', 'togo-framework'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Overview', 'togo-framework'),
                'label_block' => true,
            ],
        );

        $this->end_controls_section();
    }

    protected function add_content_heading_style_section()
    {
        $this->start_controls_section(
            'content_heading_style',
            [
                'label' => __('Heading', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-heading' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-st-heading',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_list_style_section()
    {
        $this->start_controls_section(
            'content_list_style',
            [
                'label' => __('List', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_list',
            [
                'label' => __('Enable List', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ],
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label' => __('Icon Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon:before' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-overview .name' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => __('Value Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-st-overview .value' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Label Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-st-overview .name',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'label' => __('Value Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-st-overview .value',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_description_style_section()
    {
        $this->start_controls_section(
            'content_description_style',
            [
                'label' => __('Description', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_description',
            [
                'label' => __('Enable Description', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ],
        );

        $this->add_control(
            'enable_readmore',
            [
                'label' => __('Enable Read More', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ],
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
        $trip_overview_repeater = get_post_meta($id, 'trip_overview_repeater', true);
        $trip_overview_icon = get_post_meta($id, 'trip_overview_icon', true);
        $trip_overview_name = get_post_meta($id, 'trip_overview_name', true);
        $trip_overview_value = get_post_meta($id, 'trip_overview_value', true);
        $trip_overview_description = get_post_meta($id, 'trip_overview_description', true);
        $trip_time = get_post_meta($id, 'trip_time', true);
        $trip_duration_hours = get_post_meta($id, 'trip_duration_hours', true);
        $trip_duration_minutes = get_post_meta($id, 'trip_duration_minutes', true);
        $trip_duration_days = get_post_meta($id, 'trip_duration_days', true);
        $trip_duration_nights = get_post_meta($id, 'trip_duration_nights', true);
        $trip_minimum_guests = get_post_meta($id, 'trip_minimum_guests', true);
        $trip_maximum_guests = get_post_meta($id, 'trip_maximum_guests', true);
        $trip_types = get_the_terms($id, 'togo_trip_types');
        $trip_languages = get_the_terms($id, 'togo_trip_languages');
        $enable_readmore = $settings['enable_readmore'];
        $enable_description = $settings['enable_description'];
        $enable_list = $settings['enable_list'];
        $heading = $settings['heading'];
        if (!is_singular('togo_trip') || (empty($trip_overview_name) && empty($trip_overview_description))) {
            return;
        }
?>
        <?php
        if (!empty($heading)) {
            echo '<div class="togo-st-heading-wrap">';
            echo '<h2 class="togo-st-heading">';
            echo $heading;
            echo '</h2>';
            echo '</div>';
        }
        ?>
        <div class="togo-st-overview">
            <?php
            echo '<ul>';
            if (!empty($trip_duration_hours) || !empty($trip_duration_minutes) || !empty($trip_duration_days) || !empty($trip_duration_nights)) {
                echo '<li>';
                echo \TOGO\Icon::get_svg('clock-eight');
                echo '<span class="name">';
                echo esc_html__('Duration', 'togo-framework') . ':';
                echo '</span>';
                echo '<span class="value">';
                if (!empty($trip_time) && ($trip_time == 'start_times' || $trip_time == 'opening_hours')) {
                    echo sprintf(_n('%d hour', '%d hours', $trip_duration_hours, 'togo-framework'), $trip_duration_hours);
                    echo ' ' . sprintf(_n('%d minute', '%d minutes', $trip_duration_minutes, 'togo-framework'), $trip_duration_minutes);
                } else {
                    echo sprintf(_n('%d day', '%d days', $trip_duration_days, 'togo-framework'), $trip_duration_days);
                    echo ' ' . sprintf(_n('%d night', '%d nights', $trip_duration_nights, 'togo-framework'), $trip_duration_nights);
                }
                echo '</span>';
                echo '</li>';
            }
            if (!empty($trip_maximum_guests)) {
                echo '<li>';
                echo \TOGO\Icon::get_svg('users-group');
                echo '<span class="name">';
                echo esc_html__('Travelers', 'togo-framework') . ':';
                echo '</span>';
                echo '<span class="value">';
                if (!empty($trip_minimum_guests)) {
                    echo $trip_minimum_guests . ' - ';
                }
                echo sprintf(_n('%d guest', '%d guests', $trip_maximum_guests, 'togo-framework'), $trip_maximum_guests);
                echo '</span>';
                echo '</li>';
            }
            if ($trip_types) {
                echo '<li>';
                echo \TOGO\Icon::get_svg('package');
                echo '<span class="name">';
                echo esc_html__('Tour Type', 'togo-framework') . ':';
                echo '</span>';
                echo '<span class="value">';
                foreach ($trip_types as $key => $trip_type) {
                    echo $trip_type->name;
                    if ($key < count($trip_types) - 1) {
                        echo ', ';
                    }
                }
                echo '</span>';
                echo '</li>';
            }
            if ($trip_languages) {
                echo '<li>';
                echo \TOGO\Icon::get_svg('globe');
                echo '<span class="name">';
                echo esc_html__('Language', 'togo-framework') . ':';
                echo '</span>';
                echo '<span class="value">';
                foreach ($trip_languages as $key => $trip_language) {
                    echo $trip_language->name;
                    if ($key < count($trip_languages) - 1) {
                        echo ', ';
                    }
                }
                echo '</span>';
                echo '</li>';
            }
            if (intval($trip_overview_repeater) > 0 && $enable_list == 'yes' && !empty($trip_overview_name[0])) {
                for ($i = 0; $i < $trip_overview_repeater; $i++) {
                    if (!empty($trip_overview_icon[$i])) {
                        echo '<li>';
                        echo \TOGO\Icon::get_svg($trip_overview_icon[$i]);
                        echo '<span class="name">';
                        echo $trip_overview_name[$i] . ':';
                        echo '</span>';
                        echo '<span class="value">';
                        echo $trip_overview_value[$i];
                        echo '</span>';
                        echo '</li>';
                    }
                }
            }
            echo '</ul>';

            if (!empty($trip_overview_description) && $enable_description == 'yes') {
                if ($enable_readmore == 'yes') {
                    echo '<div class="description enable-readmore">';
                } else {
                    echo '<div class="description">';
                }
                echo nl2br($trip_overview_description);
                echo '</div>';
            }
            ?>
        </div>
<?php
        if ($enable_readmore == 'yes') {
            echo '<a href="#" class="togo-st-overview-read-more"><span>' . esc_html__('Read more', 'togo-framework') . '</span></a>';
        }
    }
}
