<?php

/**
 * Breadcrumb widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Breadcrumb_Widget.
 *
 * A widget for displaying breadcrumbs.
 *
 * @package Togo_Elementor
 */
class Trip_Destinations_Rates_Carousel extends Carousel_Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-trip-destinations-rates-carousel';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip Destinations - Rates', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-review';
    }

    public function get_categories()
    {
        return ['trip-destinations'];
    }

    /**
     * Retrieves the script dependencies for the widget.
     *
     * The script dependencies are the JavaScript files that need to be loaded
     * for the widget to function properly.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array The script dependencies for the widget.
     */
    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-carousel', 'togo-widget-trip-destinations-rates-carousel');
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
        parent::register_controls();
    }

    public function add_content_section()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_heading',
            [
                'label' => esc_html__('Enable Heading', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Regards from travelers', 'togo-framework'),
                'description' => __('{term_name} will be replaced with the term name', 'togo-framework'),
                'label_block' => true,
                'condition' => [
                    'enable_heading' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => __('Padding', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates__title' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates__item__content__text, {{WRAPPER}} .togo-td-rates__item__content__name, {{WRAPPER}} .togo-td-rates__item__content__location' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __('Item Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates__item__content' => 'border-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'item_background_color',
            [
                'label' => __('Item Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates__item__content' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'icon_star_color',
            [
                'label' => __('Icon Star Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-td-rates__item__content__stars svg path' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'progress_bar_first_color',
            [
                'label' => __('Progress Bar First Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'progress_bar_second_color',
            [
                'label' => __('Progress Bar Second Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
                ]
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
        if (!is_tax('togo_trip_destinations')) {
            return;
        }

        // Get the current taxonomy term
        $term = get_queried_object();

        // Get the term name
        $term_id = $term->term_id;

        $rates = get_term_meta($term_id, 'togo_trip_destinations_rates', true);
        if (empty($rates[0]['togo_trip_destinations_rates_star']) && empty($rates[0]['togo_trip_destinations_rates_name'])) {
            return;
        }

        $enable_heading = $settings['enable_heading'];
        $heading = $settings['heading'] ?? '';
        $heading = str_replace('{term_name}', $term->name, $heading);

        // Get the slider settings.
        // These settings are used to customize the slider behavior.
        $slider_settings = $this->get_slider_settings($settings);

        // Add the slider settings as attributes.
        $this->add_render_attribute('slider', $slider_settings);

        echo '<div class="togo-td-rates">';

        if ($enable_heading === 'yes') {
            echo '<h4 class="togo-td-rates__title">' . esc_html($heading) . '</h4>';
        }
?>
        <div <?php $this->print_attributes_string('slider'); ?>>
            <div class="swiper-wrapper">
        <?php

        foreach ($rates as $rate) {
            echo '<div class="togo-td-rates__item swiper-slide">';
            echo '<div class="togo-td-rates__item__img">';
            echo '<img src="' . esc_url($rate['togo_trip_destinations_rates_image']['url']) . '" alt="' . esc_attr($rate['togo_trip_destinations_rates_name']) . '">';
            echo '</div>';
            echo '<div class="togo-td-rates__item__content">';
            echo '<p class="togo-td-rates__item__content__stars">';
            for ($i = 1; $i <= intval($rate['togo_trip_destinations_rates_star']); $i++) {
                echo \Togo\Icon::get_svg('star');
            }
            echo '</p>';
            echo '<p class="togo-td-rates__item__content__text">' . esc_html($rate['togo_trip_destinations_rates_content']) . '</p>';
            echo '<p class="togo-td-rates__item__content__name">' . esc_html($rate['togo_trip_destinations_rates_name']) . '</p>';
            echo '<p class="togo-td-rates__item__content__location">' . esc_html($rate['togo_trip_destinations_rates_location']) . '</p>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
