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
class Widget_Services extends \Togo_Framework\Elementor\Carousel_Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-services';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Services', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-user-preferences';
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
        return array('togo-widget-carousel', 'togo-widget-single-trip-services');
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
        parent::register_controls();
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
                'default' => __('Extra Services', 'togo-framework'),
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

        $services = \Togo_Framework\Helper::get_terms_by_post_id($id, 'togo_trip_services');

        if (empty($services)) {
            return;
        }

        $heading = $settings['heading'];

        if (!empty($heading)) {
            echo '<div class="togo-st-heading-wrap">';
            echo '<h2 class="togo-st-heading">';
            echo $heading;
            echo '</h2>';
            echo '</div>';
        }

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

?>
        <div <?php $this->print_attributes_string('slider'); ?>>
            <div class="swiper-wrapper">
                <?php
                // Loop through each item in the slider.
                // Display each item's content within a slider slide.
                foreach ($services as $key => $service) {
                    $term_icon         = get_term_meta($service->term_id, 'togo_trip_services_icon', true);
                    $term_price        = get_term_meta($service->term_id, 'togo_trip_services_price', true);
                    $term_suffix_price = get_term_meta($service->term_id, 'togo_trip_services_suffix_price', true);
                    $icon_src = '';
                    if ($term_icon && !empty($term_icon['url'])) {
                        $icon_src = $term_icon['url'];
                    }
                    echo '<div class="swiper-slide">';
                    echo '<div class="togo-st-service">';
                    if (!empty($icon_src)) {
                        echo '<div class="togo-st-service-icon">';
                        echo '<img src="' . $icon_src . '" alt="' . $service->name . '">';
                        echo '</div>';
                    }
                    echo '<div class="togo-st-service-content">';
                    echo '<h3>' . $service->name . '</h3>';
                    echo '<p>' . $service->description . '</p>';
                    echo '</div>';
                    if (!empty($term_price)) {
                        echo '<div class="togo-st-service-price">';
                        echo '<span>' . \Togo_Framework\Helper::togo_format_price($term_price) . '</span>';
                        if (!empty($term_suffix_price)) {
                            echo '<span>/' . $term_suffix_price . '</span>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
<?php
    }
}
