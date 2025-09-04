<?php

/**
 * My Setttings widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class My Setttings Widget.
 *
 * @package Togo_Elementor
 */
class Togo_My_Settings_Widget extends Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-my-settings';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('My Settings', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-settings';
    }

    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-my-account-settings');
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

    /**
     * Add the content section for the widget.
     *
     * @return void
     */
    protected function add_content_section()
    {
        /**
         * Content section.
         *
         * @var array The content section.
         */
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Title
        $this->add_control(
            'title',
            [
                'label' => __('Title', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Profile details', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-my-settings-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .togo-my-settings-title' => 'color: {{VALUE}};',
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
        if (isset($_GET['tab']) && $_GET['tab'] == 'password') {
            \Togo_Framework\Helper::togo_get_template('my-account/settings/password.php', $settings);
        } else {
            \Togo_Framework\Helper::togo_get_template('my-account/settings/settings.php', $settings);
        }
    }
}
