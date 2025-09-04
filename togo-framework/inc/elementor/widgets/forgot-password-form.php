<?php

/**
 * Elementor widget for displaying the site logo.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Class Togo_Logo_Widget
 *
 * Elementor widget for displaying the site logo.
 *
 * @since 1.0.0
 */
class Togo_Forgot_Password_Form_Widget extends Base
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
        return 'togo-forgot-password-form';
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
        return __('Forgot Password Form', 'togo-framework');
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
        return 'eicon-form-horizontal';
    }

    public function get_script_depends()
    {
        return array('togo-widget-forgot-password-form');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_labels_section();
        $this->add_messages_section();
        $this->add_email_template_section();
    }

    /**
     * Add the content section controls.
     *
     * @since 1.0.0
     */
    protected function add_labels_section()
    {
        $this->start_controls_section(
            'labels_section',
            [
                'label' => __('Labels', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'user_label',
            [
                'label' => __('User Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Username or Email', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'submit_label',
            [
                'label' => __('Submit Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Reset Password', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function add_messages_section()
    {
        $this->start_controls_section(
            'messages_section',
            [
                'label' => __('Messages', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'user_empty_error',
            [
                'label' => __('User Empty Error', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Please enter a username or email address.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'user_invalid_error',
            [
                'label' => __('User Invalid Error', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('No user found with that email or username.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'reset_link_error',
            [
                'label' => __('Reset Link Error', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('There was an error generating the password reset link.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'send_password_error',
            [
                'label' => __('Send Password Error', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Failed to send password reset email.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'send_password_success',
            [
                'label' => __('Send Password Success', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Check your email for the confirmation link.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function add_email_template_section()
    {
        $this->start_controls_section(
            'email_template_section',
            [
                'label' => __('Email Template', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'email_subject',
            [
                'label' => __('Email Subject', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password Reset Request', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'email_template',
            [
                'label' => __('Email Template', 'togo-framework'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => $this->get_default_email_template(),
                'description' => __('HTML is supported. {{user_login}} will be replaced with the user login. {{user_email}} will be replaced with the user email. {{reset_link}} will be replaced with the password reset link.', 'togo-framework'),
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
        if (is_user_logged_in() && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }
?>
        <div class="togo-forgot-password-form">
            <form class="ajax-forgot-password-form" action="<?php echo home_url(); ?>" method="post">
                <p class="user-login">
                    <label for="user_login"><?php echo $settings['user_label']; ?><span class="required">*</span></label>
                    <input type="text" name="user_login" class="user-login" id="user_login">
                </p>
                <div class="message"></div>
                <p class="submit">
                    <input type="hidden" class="action" name="action" value="togo_ajax_forgot_password">
                    <input type="hidden" class="user_empty_error" name="user_empty_error" value="<?php echo $settings['user_empty_error']; ?>">
                    <input type="hidden" class="user_invalid_error" name="user_invalid_error" value="<?php echo $settings['user_invalid_error']; ?>">
                    <input type="hidden" class="reset_link_error" name="reset_link_error" value="<?php echo $settings['reset_link_error']; ?>">
                    <input type="hidden" class="send_password_error" name="send_password_error" value="<?php echo $settings['send_password_error']; ?>">
                    <input type="hidden" class="send_password_success" name="send_password_success" value="<?php echo $settings['send_password_success']; ?>">
                    <input type="hidden" class="email_subject" name="email_subject" value="<?php echo $settings['email_subject']; ?>">
                    <input type="hidden" class="email_template" name="email_template" value="<?php echo $settings['email_template']; ?>">
                    <input type="hidden" class="security" name="security" value="<?php echo wp_create_nonce('ajax-forgot-password-nonce'); ?>">
                    <button type="submit" class="submit"><?php echo $settings['submit_label']; ?></button>
                </p>
            </form>
        </div>
<?php
    }

    public function get_default_email_template()
    {
        return '<p>Hello,</p>

        <p>You have requested a password reset for the following account:</p>

        <p><b>Username:</b> {{user_login}}</p>

        <p><b>User Email:</b> {{user_email}}</p>

        <p><b>Password Reset Link:</b> {{reset_link}}</p>
        ';
    }
}
