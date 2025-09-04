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
class Widget_Availability extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-st-availability';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip - Availability', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-calendar';
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
        return array('togo-widget-single-trip-availability');
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
            'loading_title',
            [
                'label' => __('Loading title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Loading available options...', 'togo-framework'),
            ]
        );

        $this->end_controls_section();
    }

    public function add_content_style_section()
    {
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => __('Content Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'togo-framework'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .list-availability' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $loading_title = $settings['loading_title'];
        if (!is_singular('togo_trip')) {
            return;
        }
?>
        <div class="availability-wrapper">
            <?php echo \Togo_Framework\Template::render_skeleton($loading_title); ?>
            <div class="availability-content"></div>
        </div>
<?php
    }
}
