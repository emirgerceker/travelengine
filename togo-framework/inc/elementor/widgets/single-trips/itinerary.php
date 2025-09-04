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
class Widget_Itinerary extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-itinerary';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Itinerary', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-map-pin';
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
        return array('togo-widget-single-trip-itinerary');
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
                'default' => __('Itinerary', 'togo-framework'),
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
        <div class="togo-st-itinerary" id="togo-st-itinerary">
            <?php
            foreach ($trip_itinerary as $key => $value) {
                $title = $value['trip_itinerary_title'];
                $content = $value['trip_itinerary_content'];
                echo '<div class="togo-st-itinerary-item">';
                echo '<h3 class="togo-st-itinerary-item-title">';
                if ($key == 0) {
                    echo \Togo\Icon::get_svg('location', 'togo-st-itinerary-item-icon');
                } else if ($key == count($trip_itinerary) - 1) {
                    echo \Togo\Icon::get_svg('flag-one', 'togo-st-itinerary-item-icon');
                } else {
                    echo '<span class="togo-st-itinerary-item-icon"></span>';
                }
                echo '<span class="togo-st-itinerary-item-text">';
                echo $title;
                echo \Togo\Icon::get_svg('chevron-down');
                echo '</span>';
                echo '</h3>';
                echo '<div class="togo-st-itinerary-item-content">' . $content . '</div>';
                echo '</div>';
            }
            ?>
        </div>

<?php
    }
}
