<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Togo_User_Widget extends Base
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
        return 'togo-user';
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
        return __('User', 'togo-framework');
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
        return 'eicon-lock-user';
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_not_logged_in_section();
        $this->add_content_logged_in_section();
        $this->add_content_not_logged_in_style_section();
        $this->add_content_logged_in_style_section();
    }

    /**
     * Add the content section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_not_logged_in_section()
    {
        $this->start_controls_section(
            'content_not_logged_in_section',
            [
                'label' => __('Not Logged In', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'not_logged_in_url',
            [
                'label' => __('Not Logged In URL', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_logged_in_section()
    {
        $this->start_controls_section(
            'content_logged_in_section',
            [
                'label' => __('Logged In', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logged_in_url',
            [
                'label' => __('Logged In URL', 'togo-framework'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'menu',
            [
                'label' => __('Menu', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'icon',
                        'label' => __('Icon', 'togo-framework'),
                        'type' => Controls_Manager::ICONS
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Title', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Menu Item', 'togo-framework'),
                        'dynamic' => [
                            'active' => true,
                        ]
                    ],
                    [
                        'name' => 'url',
                        'label' => __('URL', 'togo-framework'),
                        'type' => Controls_Manager::URL,
                        'default' => [
                            'url' => '',
                        ],
                        'dynamic' => [
                            'active' => true,
                        ],
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_not_logged_in_style_section()
    {
        $this->start_controls_section(
            'content_not_logged_in_style_section',
            [
                'label' => __('Not Logged In', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_logged_in_style_section()
    {
        $this->start_controls_section(
            'content_logged_in_style_section',
            [
                'label' => __('Logged In', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'avatar_size',
            [
                'label' => __('Avatar Size', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
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
        $user = wp_get_current_user();

        echo '<div class="togo-user">';
        if (is_user_logged_in()) {
            $url = $settings['logged_in_url']['url'];
            $target = $settings['logged_in_url']['is_external'] ? $settings['logged_in_url']['target'] : '_self';
            $avatar = get_avatar($user->ID, $settings['avatar_size']['size'], '', $user->display_name);
            $avatar_id = get_user_meta($user->ID, 'avatar', true);
            if ($avatar_id) {
                echo wp_get_attachment_image($avatar_id, array($settings['avatar_size']['size'], $settings['avatar_size']['size']), false, array('class' => 'avatar'));
            } else {
                echo $avatar;
            }

            echo '<div class="user-submenu">';
            echo '<div class="user-name">';
            echo '<a href="' . esc_url($url) . '" target="' . esc_attr($target) . '" >';
            if ($avatar_id) {
                echo wp_get_attachment_image($avatar_id, array($settings['avatar_size']['size'], $settings['avatar_size']['size']), false, array('class' => 'avatar'));
            } else {
                echo $avatar;
            }
            echo '<span>' . $user->display_name . '</span>';
            echo '</a>';
            echo '</div>';
            if (!empty($settings['menu'])) {
                echo '<div class="user-menu">';
                foreach ($settings['menu'] as $item) {
                    echo '<a href="' . esc_url($item['url']['url']) . '" target="' . esc_attr($item['url']['is_external'] ? $item['url']['target'] : '_self') . '" >';
                    $icon = $item['icon'] ? trim($item['icon']['value']) : '';
                    $icon_name = str_replace('togo-svg ', '', $icon);
                    echo \Togo\Icon::get_svg($icon_name);
                    echo '<span>' . $item['title'] . '</span>';
                    echo '</a>';
                }
                echo '</div>';
            }
            echo '</div>';
        } else {
            $url = $settings['not_logged_in_url']['url'];
            $target = $settings['not_logged_in_url']['is_external'] ? $settings['not_logged_in_url']['target'] : '_self';

            if (!empty($url)) {
                echo '<a href="' . esc_url($url) . '" target="' . esc_attr($target) . '" >';
            }

            echo \Togo\Icon::get_svg('user-circle');

            if (!empty($url)) {
                echo '</a>';
            }
        }

        echo '</div>';
    }
}
