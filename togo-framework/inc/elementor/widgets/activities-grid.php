<?php

/**
 * Elementor widget for displaying activities.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Activities_Grid_Widget
 *
 * Elementor widget for displaying activities.
 *
 * @since 1.0.0
 */
class Togo_Activities_Grid_Widget extends Base
{

    /**
     * Get the widget name.
     *
     * @since 1.0.0
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-activities-grid';
    }

    /**
     * Get the widget title.
     *
     * @since 1.0.0
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Activities Grid', 'togo-framework');
    }

    /**
     * Get the widget icon.
     *
     * @since 1.0.0
     *
     * @return string The widget icon.
     */
    public function get_icon_part()
    {
        return 'eicon-shape';
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_grid_section();
    }

    /**
     * Add the content section controls.
     *
     * @since 1.0.0
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
            'layout_content',
            [
                'label' => __('Layout', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'layout-1' => __('Layout 1', 'togo-framework'),
                    'layout-2' => __('Layout 2', 'togo-framework'),
                    'layout-3' => __('Layout 3', 'togo-framework'),
                ],
                'default' => 'layout-2',
            ]
        );

        // Repeater for activities
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'activity',
            [
                'label' => __('Activity', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => \Togo_Framework\Helper::get_all_terms_by_taxonomy('togo_trip_activities', true),
                'default' => '',
            ]
        );
        $repeater->add_control(
            'thumbnail',
            [
                'label' => __('Thumbnail', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'activities_list',
            [
                'label' => __('Activities', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ activity }}}',
            ]
        );
        $this->add_control(
            'show_count',
            [
                'label' => __('Show Count', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content grid section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_grid_section()
    {
        $this->start_controls_section(
            'content_grid_section',
            [
                'label' => __('Grid', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => __('1 Column', 'togo-framework'),
                    '2' => __('2 Columns', 'togo-framework'),
                    '3' => __('3 Columns', 'togo-framework'),
                    '4' => __('4 Columns', 'togo-framework'),
                    '5' => __('5 Columns', 'togo-framework'),
                    '6' => __('6 Columns', 'togo-framework'),
                ],
                'default' => '6',
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __('Row Gap', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label' => __('Column Gap', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output.
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $activities = $settings['activities_list'];
        $columns = $settings['columns'] ? $settings['columns'] : 6;
        $columns_tablet = isset($settings['columns_tablet']) && !empty($settings['columns_tablet']) ? $settings['columns_tablet'] : 3;
        $columns_mobile = isset($settings['columns_mobile']) && !empty($settings['columns_mobile']) ? $settings['columns_mobile'] : 2;
        $row_gap = ($settings['row_gap'] || $settings['row_gap'] == '0') ? $settings['row_gap'] : 20;
        $column_gap = ($settings['column_gap'] || $settings['row_gap'] == '0') ? $settings['column_gap'] : 20;
        $show_count = $settings['show_count'] === 'yes' ? true : false;
        $layout_content = $settings['layout_content'] ? $settings['layout_content'] : 'layout-1';
        if (empty($activities)) {
            return;
        }
        $this->add_render_attribute('wrapper', 'class', 'togo-activities-grid-wrapper');
        $this->add_render_attribute('grid', 'class', 'togo-activities-grid');
        $this->add_render_attribute('grid', 'class', 'togo-trip-grid togo-row togo-row-cols-xl-' . $columns . ' togo-row-cols-lg-' . $columns . ' togo-row-cols-md-' . $columns . ' togo-row-cols-sm-' . $columns_tablet . ' togo-row-cols-xs-' . $columns_mobile);
        $this->add_render_attribute('wrapper', 'style', '--row-gap: ' . $row_gap . 'px; --column-gap:' . $column_gap / 2 . 'px; --margin-gap: ' . ($column_gap / 2) * -1 . 'px;');

        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        echo '<div ' . $this->get_render_attribute_string('grid') . '>';
        foreach ($activities as $activity) {
            \Togo_Framework\Helper::togo_get_template('loop/widgets/activities/' . $layout_content . '.php', array(
                'activity' => $activity,
                'show_count' => $show_count,
            ));
        }
        echo '</div>';
        echo '</div>';
    }
}
