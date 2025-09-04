<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;


class Togo_Register_Form_Widget extends Base
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
        return 'togo-register-form';
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
        return __('Register Form', 'togo-framework');
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
        return array('togo-widget-register-form');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_labels_section();
        $this->add_messages_section();
        $this->add_admin_email_template_section();
        $this->add_user_email_template_section();
    }

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
            'redirect_to',
            [
                'label' => __('Redirect To', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ],
                'placeholder' => __('https://your-link.com', 'togo-framework'),
                'description' => __('Redirect to another page after successful registration. Leave it blank to redirect to the home page.', 'togo-framework'),
            ]
        );

        $this->add_control(
            'email_to_admin',
            [
                'label' => __('Send Email To Admin', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'email_to_user',
            [
                'label' => __('Send Email To User', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'minimum_password_length',
            [
                'label' => __('Minimum Password Length', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
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
            'username_label',
            [
                'label' => __('Username Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Username', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => __('Email Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Email', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_label',
            [
                'label' => __('Password Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_confirm_label',
            [
                'label' => __('Password Confirm Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password Confirm', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'submit_label',
            [
                'label' => __('Submit Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Register', 'togo-framework'),
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
            'username_empty',
            [
                'label' => __('Username Empty', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Username cannot be empty.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'username_invalid',
            [
                'label' => __('Username Invalid', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Username already exists.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'email_empty',
            [
                'label' => __('Email Empty', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Email cannot be empty.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'email_invalid',
            [
                'label' => __('Email Invalid', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Email already exists.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_empty',
            [
                'label' => __('Password Empty', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password cannot be empty.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_length',
            [
                'label' => __('Password Length', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password must be at least {{minimum_password_length}} characters.', 'togo-framework'),
                'description' => __('{{minimum_password_length}} is the minimum password length.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_confirm_empty',
            [
                'label' => __('Password Confirm Empty', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password confirm cannot be empty.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_confirm_invalid',
            [
                'label' => __('Password Confirm Invalid', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Passwords do not match.', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'register_send',
            [
                'label' => __('Register Send', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Sending user info,please wait...', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'register_success',
            [
                'label' => __('Register Success', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Registration successful! Redirecting...', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function add_admin_email_template_section()
    {
        $this->start_controls_section(
            'admin_email_template_section',
            [
                'label' => __('Admin Email Template', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'email_to_admin' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'admin_email_subject',
            [
                'label' => __('Email Subject', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('User Registration', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'admin_email_template',
            [
                'label' => __('Email Template', 'togo-framework'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => $this->get_default_admin_email_template(),
                'description' => __('HTML tags are allowed. {{user_login}} will be replaced with the user login. {{user_email}} will be replaced with the user email. {{user_password}} will be replaced with the user password.', 'togo-framework'),
            ]
        );

        $this->end_controls_section();
    }

    protected function add_user_email_template_section()
    {
        $this->start_controls_section(
            'user_email_template_section',
            [
                'label' => __('User Email Template', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'email_to_user' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'user_email_subject',
            [
                'label' => __('Email Subject', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('User Registration', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'user_email_template',
            [
                'label' => __('Email Template', 'togo-framework'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => $this->get_default_user_email_template(),
                'description' => __('HTML tags are allowed. {{user_login}} will be replaced with the user login. {{user_email}} will be replaced with the user email. {{user_password}} will be replaced with the user password.', 'togo-framework'),
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
        <div class="togo-register-form">
            <form class="ajax-register-form" action="<?php echo home_url(); ?>" method="post">
                <p class="register-username">
                    <label for="username"><?php echo $settings['username_label']; ?><span class="required">*</span></label>
                    <input type="text" name="username" class="username" id="username">
                    <input type="hidden" name="username_empty" class="username_empty" value="<?php echo $settings['username_empty']; ?>">
                    <input type="hidden" name="username_invalid" class="username_invalid" value="<?php echo $settings['username_invalid']; ?>">
                </p>
                <p class="register-email">
                    <label for="email"><?php echo $settings['email_label']; ?><span class="required">*</span></label>
                    <input type="email" name="email" class="email" id="email">
                    <input type="hidden" name="email_empty" class="email_empty" value="<?php echo $settings['email_empty']; ?>">
                    <input type="hidden" name="email_invalid" class="email_invalid" value="<?php echo $settings['email_invalid']; ?>">
                </p>
                <p class="register-password">
                    <label for="password"><?php echo $settings['password_label']; ?><span class="required">*</span></label>
                    <input type="password" name="password" class="password" id="password">
                    <input type="hidden" name="password_empty" class="password_empty" value="<?php echo $settings['password_empty']; ?>">
                    <input type="hidden" name="minimum_password_length" class="minimum_password_length" value="<?php echo $settings['minimum_password_length']; ?>">
                    <input type="hidden" name="password_length" class="password_length" value="<?php echo $settings['password_length']; ?>">
                </p>
                <p class="register-password-confirm">
                    <label for="password-confirm"><?php echo $settings['password_confirm_label']; ?><span class="required">*</span></label>
                    <input type="password" name="password_confirm" class="password-confirm" id="password-confirm">
                    <input type="hidden" name="password_confirm_empty" class="password_confirm_empty" value="<?php echo $settings['password_confirm_empty']; ?>">
                    <input type="hidden" name="password_confirm_invalid" class="password_confirm_invalid" value="<?php echo $settings['password_confirm_invalid']; ?>">
                </p>
                <div class="register-message"></div>
                <p class="register-submit">
                    <input type="hidden" class="action" name="action" value="togo_ajax_register">
                    <input type="hidden" class="security" name="security" value="<?php echo wp_create_nonce('ajax-register-nonce'); ?>">
                    <input type="hidden" class="redirect_to" name="redirect_to" value="<?php echo esc_url($settings['redirect_to']['url']); ?>">
                    <input type="hidden" class="email_to_admin" name="email_to_admin" value="<?php echo esc_attr($settings['email_to_admin']); ?>">
                    <input type="hidden" class="email_to_user" name="email_to_user" value="<?php echo esc_attr($settings['email_to_user']); ?>">
                    <input type="hidden" class="admin_email_subject" name="admin_email_subject" value="<?php echo esc_attr($settings['admin_email_subject']); ?>">
                    <input type="hidden" class="admin_email_template" name="admin_email_template" value="<?php echo esc_attr($settings['admin_email_template']); ?>">
                    <input type="hidden" class="user_email_subject" name="user_email_subject" value="<?php echo esc_attr($settings['user_email_subject']); ?>">
                    <input type="hidden" class="user_email_template" name="user_email_template" value="<?php echo esc_attr($settings['user_email_template']); ?>">
                    <input type="hidden" class="register_send" name="register_send" value="<?php echo esc_attr($settings['register_send']); ?>">
                    <input type="hidden" class="register_success" name="register_success" value="<?php echo esc_attr($settings['register_success']); ?>">
                    <input type="hidden" class="register_send" name="register_send" value="<?php echo esc_attr($settings['register_send']); ?>">
                    <button type="submit" class="register-submit"><?php echo $settings['submit_label']; ?></button>
                </p>
            </form>
        </div>
<?php
    }

    public function get_default_admin_email_template()
    {
        return '<p>Hello,</p>

        <p>We have a new user registration.</p>

        <p>The user details are as follows:</p>

        <ul>
            <li><b>Username:</b> {{user_login}}</li>
            <li><b>Email:</b> {{user_email}}</li>
            <li><b>Password:</b> {{user_password}}</li>
        </ul>

        <p>Thank you!</p>
        ';
    }


    public function get_default_user_email_template()
    {
        return '<p>Dear {{user_login}},</p>

        <p>Thank you for registering on our website.</p>

        <p>Your username is: {{user_login}}</p>

        <p>Your email address is: {{user_email}}</p>

        <p>Your password is: {{user_password}}</p>

        <p>Thank you!</p>
        ';
    }
}
