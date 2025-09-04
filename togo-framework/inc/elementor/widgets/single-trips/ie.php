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
class Widget_IE extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-ie';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Includes/Excludes', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-check-circle';
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
                'default' => __('Includes/Excludes', 'togo-framework'),
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
            'icon_includes',
            [
                'label' => __('Icon Includes', 'togo-framework'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'icon_excludes',
            [
                'label' => __('Icon Excludes', 'togo-framework'),
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
            'icon_includes_color',
            [
                'label' => __('Icon Includes Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .includes .togo-svg-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_excludes_color',
            [
                'label' => __('Icon Excludes Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .excludes .togo-svg-icon' => 'color: {{VALUE}}',
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
        $id = get_the_ID();
        $trip_includes = get_post_meta($id, 'trip_includes', true);
        $trip_excludes = get_post_meta($id, 'trip_excludes', true);
        $includes = preg_split('/\r\n|\r|\n/', $trip_includes);
        $excludes = preg_split('/\r\n|\r|\n/', $trip_excludes);
        $icon_includes = $settings['icon_includes'] ? trim($settings['icon_includes']['value']) : '';
        $icon_includes_name = str_replace('togo-svg ', '', $icon_includes);
        $icon_includes_name = $icon_includes_name ? $icon_includes_name : 'check';
        $icon_excludes = $settings['icon_excludes'] ? trim($settings['icon_excludes']['value']) : '';
        $icon_excludes_name = str_replace('togo-svg ', '', $icon_excludes);
        $icon_excludes_name = $icon_excludes_name ? $icon_excludes_name : 'x';
        $heading = $settings['heading'];
        if (!is_singular('togo_trip') || (empty($trip_includes) && empty($trip_excludes))) {
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
        <div class="togo-st-ie">
            <?php
            if ($includes && $includes[0] != '') {
                echo '<ul class="items includes">';
                foreach ($includes as $include) {
                    echo '<li>';
                    echo \Togo\Icon::get_svg($icon_includes_name);
                    echo $include;
                    echo '</li>';
                }
                echo '</ul>';
            }
            if ($excludes && $excludes[0] != '') {
                echo '<ul class="items excludes">';
                foreach ($excludes as $exclude) {
                    echo '<li>';
                    echo \Togo\Icon::get_svg($icon_excludes_name);
                    echo $exclude;
                    echo '</li>';
                }
                echo '</ul>';
            }
            ?>
        </div>
<?php
    }
}
