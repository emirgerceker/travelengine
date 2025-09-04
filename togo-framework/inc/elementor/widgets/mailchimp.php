<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Togo_Mailchimp_Widget
 *
 * Elementor widget for displaying a Mailchimp form.
 *
 * @package Togo_Elementor
 */
class Togo_Mailchimp_Widget extends Base
{
    /**
     * Get the widget name.
     *
     * Retrieve the widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'togo-mailchimp';
    }

    /**
     * Get the widget title.
     *
     * Retrieve the widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Mailchimp Widget', 'togo-framework');
    }

    /**
     * Get the widget icon.
     *
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon_part()
    {
        return 'eicon-mailchimp';
    }

    /**
     * Register the widget controls.
     *
     * Adds the necessary controls to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        // Start the content section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Add the Mailchimp shortcode control
        $this->add_control(
            'mailchimp_shortcode',
            [
                'label' => __('Mailchimp Shortcode', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        // End the content section
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Writes the widget output to the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $mailchimp_shortcode = $settings['mailchimp_shortcode'];

        // Check if the Mailchimp shortcode is set
        if ($mailchimp_shortcode) {
            echo do_shortcode($mailchimp_shortcode);
        }
    }
}
