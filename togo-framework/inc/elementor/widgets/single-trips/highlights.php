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
class Widget_Highlights extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-highlights';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Highlights', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-post';
    }

    public function get_categories()
    {
        return ['single-trips'];
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
        $this->add_content_section();
        $this->add_content_style_section();
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
                'default' => __('Highlights', 'togo-framework'),
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

    public function add_content_section()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'togo-framework'),
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'togo-framework'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->end_controls_section();
    }

    public function add_content_style_section()
    {
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon' => 'color: {{VALUE}}',
                ],
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
        $trip_highlights = get_post_meta($id, 'trip_highlights', true);
        $highlights = preg_split('/\r\n|\r|\n/', $trip_highlights);
        if (empty($trip_highlights) || empty($highlights)) {
            return;
        }
        $icon = $settings['icon'] ? trim($settings['icon']['value']) : '';
        $icon_name = str_replace('togo-svg ', '', $icon);
        $icon_name = $icon_name ? $icon_name : 'check-waves';
        $heading = $settings['heading'];
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
        <div class="togo-st-highlights">
            <ul>
                <?php
                foreach ($highlights as $highlight) {
                    echo '<li>';
                    echo \Togo\Icon::get_svg($icon_name);
                    echo $highlight;
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
<?php
    }
}
