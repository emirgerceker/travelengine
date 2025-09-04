<?php

/**
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Widget.
 *
 * @package Togo_Elementor
 */
class Togo_My_Bookings_Widget extends Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-my-bookings';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('My Bookings', 'togo-framework');
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

    public function get_script_depends()
    {
        // The script dependencies for the widget.
        // In this case, we are returning an array with a single element, the name
        // of the script dependency.
        return array('togo-widget-my-bookings');
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_invoice_section();
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

        // Show filter
        $this->add_control(
            'show_filter',
            [
                'label' => __('Show Filter', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'togo-framework'),
                'label_off' => __('Hide', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Number of posts
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts per page', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Add the invoice section for the widget.
     *
     * @return void
     */
    protected function add_invoice_section()
    {
        /**
         * Invoice section.
         *
         * @var array The invoice section.
         */
        $this->start_controls_section(
            'invoice_section',
            [
                'label' => __('Invoice', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Show filter
        $this->add_control(
            'company_name',
            [
                'label' => __('Company Name', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => get_bloginfo('name'),
                'label_block' => true,
            ]
        );

        // Company address
        $this->add_control(
            'company_address',
            [
                'label' => __('Company Address', 'togo-framework'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
            ]
        );

        // Company phone
        $this->add_control(
            'company_phone',
            [
                'label' => __('Company Phone', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        // Company email
        $this->add_control(
            'company_email',
            [
                'label' => __('Company Email', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
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
        if (isset($_GET['view']) && $_GET['view'] != '') {
            \Togo_Framework\Helper::togo_get_template('my-account/bookings/booking-detail.php', $settings);
        } elseif (isset($_GET['invoice']) && $_GET['invoice'] != '') {
            \Togo_Framework\Helper::togo_get_template('my-account/bookings/invoice.php', $settings);
        } else {
            \Togo_Framework\Helper::togo_get_template('my-account/bookings/bookings.php', $settings);
        }
    }
}
