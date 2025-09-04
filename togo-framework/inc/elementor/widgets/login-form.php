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
class Togo_Login_Form_Widget extends Base
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
        return 'togo-login-form';
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
        return __('Login Form', 'togo-framework');
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
        return array('togo-widget-login-form');
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
            'enable_remember',
            [
                'label' => __('Remember me', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_forgot_password',
            [
                'label' => __('Enable Forgot Password', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'redirect_to',
            [
                'label' => __('Redirect To', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'forgot_password_url',
            [
                'label' => __('Forgot Password URL', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'enable_forgot_password' => 'yes',
                ]
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
            'fields_section',
            [
                'label' => __('Fields', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'username_label',
            [
                'label' => __('Username Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Username or Email', 'togo-framework'),
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
            'remember_label',
            [
                'label' => __('Remember Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Remember me', 'togo-framework'),
                'label_block' => true,
                'condition' => [
                    'enable_remember' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'submit_label',
            [
                'label' => __('Submit Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Login', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'forgot_password_label',
            [
                'label' => __('Forgot Password Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Forgot Password?', 'togo-framework'),
                'label_block' => true,
                'condition' => [
                    'enable_forgot_password' => 'yes',
                ]
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
                'default' => __('Username or Email is required', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'username_invalid',
            [
                'label' => __('Username Invalid', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Username or Email is invalid', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'password_empty',
            [
                'label' => __('Password Empty', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Password is required', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'login_invalid',
            [
                'label' => __('Login Invalid', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Invalid username or password', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'login_send',
            [
                'label' => __('Login Send', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Sending user info,please wait...', 'togo-framework'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'login_success',
            [
                'label' => __('Login Success', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Login successful! Redirecting...', 'togo-framework'),
                'label_block' => true,
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
        <div class="togo-login-form">
            <form class="ajax-login-form" action="<?php echo home_url(); ?>" method="post">
                <p class="login-username">
                    <label for="username"><?php echo $settings['username_label']; ?><span class="required">*</span></label>
                    <input type="text" name="username" class="username" id="username">
                    <input type="hidden" class="username_empty" name="username_empty" value="<?php echo $settings['username_empty']; ?>">
                    <input type="hidden" class="username_invalid" name="username_invalid" value="<?php echo $settings['username_invalid']; ?>">
                </p>
                <p class="login-password">
                    <label for="password"><?php echo $settings['password_label']; ?><span class="required">*</span></label>
                    <input type="password" name="password" class="password" id="password">
                    <input type="hidden" class="password_empty" name="password_empty" value="<?php echo $settings['password_empty']; ?>">
                </p>
                <?php if ($settings['enable_remember'] == 'yes') : ?>
                    <p class="login-remember">
                        <label>
                            <input type="checkbox" name="remember" class="remember">
                            <span><?php echo $settings['remember_label']; ?></span>
                        </label>
                    </p>
                <?php endif; ?>
                <div class="login-message"></div>
                <p class="login-submit">
                    <input type="hidden" class="action" name="action" value="togo_ajax_login">
                    <input type="hidden" class="redirect_to" name="redirect_to" value="<?php echo esc_url($settings['redirect_to']['url']); ?>">
                    <input type="hidden" class="login_invalid" name="login_invalid" value="<?php echo esc_attr($settings['login_invalid']); ?>">
                    <input type="hidden" class="login_send" name="login_send" value="<?php echo esc_attr($settings['login_send']); ?>">
                    <input type="hidden" class="login_success" name="login_success" value="<?php echo esc_attr($settings['login_success']); ?>">
                    <input type="hidden" class="security" name="security" value="<?php echo wp_create_nonce('ajax-login-nonce'); ?>">
                    <button type="submit" class="login-submit"><?php echo $settings['submit_label']; ?></button>
                </p>
                <?php if ($settings['enable_forgot_password'] == 'yes') : ?>
                    <p class="lost-password"><a href="<?php echo esc_url($settings['forgot_password_url']['url']); ?>"><?php echo esc_html($settings['forgot_password_label']); ?></a></p>
                <?php endif; ?>
            </form>
        </div>
<?php
    }
}
