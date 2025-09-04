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
class Widget_Tour_Maps extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-tour-maps';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Tour Maps', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-google-maps';
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
        return array('togo-el-google-maps', 'togo-widget-single-trip-tour-maps');
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
        $this->add_content_map_style_section();
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
                'default' => __('Tour maps', 'togo-framework'),
                'label_block' => true,
            ],
        );

        $this->add_control(
            'enable_link',
            [
                'label' => __('Enable Link', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'togo-framework'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link_title',
            [
                'label' => __('Link Title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link_custom_class',
            [
                'label' => __('Custom Class', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'description' => __('Add custom class for link. Separator is a space.', 'togo-framework'),
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'togo-framework'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __('Icon Position', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => __('Before', 'togo-framework'),
                    'after' => __('After', 'togo-framework'),
                ],
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
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

    protected function add_content_map_style_section()
    {
        $this->start_controls_section(
            'content_map_style',
            [
                'label' => __('Map', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'marker_color',
            [
                'label' => __('Marker Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-marker' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'marker_bg_color',
            [
                'label' => __('Marker Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-marker' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'marker_border_color',
            [
                'label' => __('Marker Border Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-marker' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .custom-marker:after' => 'border-top-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'line_color',
            [
                'label' => __('Line Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FD4621',
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
            ]
        );

        $this->add_control(
            'arrow_speed',
            [
                'label' => __('Arrow Speed', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 200,
                'step' => 1,
                'default' => 50,
            ]
        );

        $this->add_control(
            'map_zoom',
            [
                'label' => __('Map Zoom', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 30,
                'step' => 1,
                'default' => 9,
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
        if (!is_singular('togo_trip')) {
            return;
        }
        $id = get_the_ID();

        $trip_itinerary = get_post_meta($id, 'trip_itinerary', true);

        if (empty($trip_itinerary) || $trip_itinerary[0]['trip_itinerary_title'] == '') {
            return;
        }

        $address = [];

        foreach ($trip_itinerary as $key => $value) {
            $address[] = $value['trip_itinerary_address']['location'];
        }

        $heading = $settings['heading'];
        $enable_link = $settings['enable_link'];
        $link = $settings['link'] ? $settings['link'] : [];
        if (array_key_exists('is_external', $link)) {
            $is_external = $link['is_external'] ? 'target="_blank"' : '';
        } else {
            $is_external = '';
        }
        if (array_key_exists('nofollow', $link)) {
            $nofollow = $link['nofollow'] ? 'rel="nofollow"' : '';
        } else {
            $nofollow = '';
        }
        if (array_key_exists('custom_attributes', $link) && !empty($link['custom_attributes'])) {
            // Split by commas to get each key-value pair
            $pairs = explode(',', $link['custom_attributes']);
            $custom_attributes = '';
            // Loop through each pair
            foreach ($pairs as $pair) {
                list($key, $value) = explode('|', $pair);
                $custom_attributes .= " $key=\"$value\"";
            }
        } else {
            $custom_attributes = '';
        }
        $link_title = $settings['link_title'];
        $icon_position = $settings['icon_position'];
        $link_custom_class = $settings['link_custom_class'];
        $icon = $settings['icon'] ? trim($settings['icon']['value']) : '';
        $icon_name = str_replace('togo-svg ', '', $icon);

        if (!empty($heading)) {
            echo '<div class="togo-st-heading-wrap">';
            echo '<h2 class="togo-st-heading">';
            echo $heading;
            echo '</h2>';
            if ($enable_link == 'yes' && !empty($link['url']) && !empty($link_title)) {
                echo '<a href="' . $link['url'] . '" title="' . $link_title . '" class="togo-st-heading-link ' . $link_custom_class . '" ' . $is_external . ' ' . $nofollow . ' ' . $custom_attributes . '>';
                if ($icon_position == 'before' && !empty($icon_name)) {
                    echo \Togo\Icon::get_svg($icon_name, 'togo-st-heading-icon');
                }
                echo '<span class="togo-st-heading-link-title">' . $link_title . '</span>';
                if ($icon_position == 'after' && !empty($icon_name)) {
                    echo \Togo\Icon::get_svg($icon_name, 'togo-st-heading-icon');
                }
                echo '</a>';
            }
            echo '</div>';
        }
?>
        <div class="togo-st-tour-maps">
            <div
                class="togo-st-tour-maps-map"
                id="togo-st-tour-maps-map"
                data-coordinates='<?php echo esc_attr(json_encode($address)); ?>'
                data-line-color='<?php echo esc_attr($settings['line_color']); ?>'
                data-arrow-color='<?php echo esc_attr($settings['arrow_color']); ?>'
                data-arrow-speed='<?php echo esc_attr($settings['arrow_speed']); ?>'
                data-map-zoom='<?php echo esc_attr($settings['map_zoom']); ?>'></div>
        </div>
<?php
    }
}
