<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Togo_Canvas_Menu_Widget extends Base
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
        return 'togo-canvas-menu';
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
        return __('Canvas Menu', 'togo-framework');
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
        return 'eicon-menu-bar';
    }

    public function get_script_depends()
    {
        return array('togo-widget-canvas-menu');
    }

    /**
     * Register the widget controls.
     *
     * @since 1.0.0
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
        $this->add_button_section();
        $this->add_language_currency_section();
        $this->add_language_section();
        $this->add_currency_section();
        $this->add_content_not_logged_in_section();
        $this->add_content_logged_in_section();
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

        /**
         * Alignment of the menu icon.
         */
        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Position', 'togo-framework'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'canvas-left' => [
                        'title' => esc_html__('Left', 'togo-framework'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'canvas-right' => [
                        'title' => esc_html__('Right', 'togo-framework'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'canvas-left',
            ]
        );

        // Get list menu
        $this->add_control(
            'menu_content',
            [
                'label' => esc_html__('Menu', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => \Togo\Helper::get_all_menus(),
                'default' => 'main_menu',
            ]
        );

        // Get list menu
        $this->add_control(
            'show_profile',
            [
                'label' => esc_html__('Show Profile', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Show or hide the profile link.', 'togo-framework'),
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .canvas-menu-icon .togo-svg-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .canvas-menu-icon:hover .togo-svg-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function add_button_section()
    {
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('Button', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_enabled',
            [
                'label' => esc_html__('Enable', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
                'prefix_class' => 'button-',
                'label_on' => esc_html__('Yes', 'togo-framework'),
                'label_off' => esc_html__('No', 'togo-framework'),
                'description' => esc_html__('Enable or disable the button.', 'togo-framework'),
            ]
        );

        /**
         * Alignment of the menu icon.
         */
        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__('Icon', 'togo-framework'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Text', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => esc_html__('URL', 'togo-framework'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->end_controls_section();
    }

    protected function add_language_currency_section()
    {
        $this->start_controls_section(
            'language_currency_section',
            [
                'label' => __('Language & Currency', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => __('Label', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('EN / USD', 'togo-framework'),
            ]
        );

        $this->add_control(
            'enabled_language',
            [
                'label' => __('Enable Language', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => '1',
                'default' => '1',
                'description' => __('Multilingual plugins need to be enabled (eg WPML, Polylang....)', 'togo-framework'),
            ],
        );

        $this->add_control(
            'enabled_currency',
            [
                'label' => __('Enable Currency', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => '1',
                'default' => '1',
                'description' => __('Plugin: FOX - Currency Switcher Professional for WooCommerce need to be installed and activated', 'togo-framework'),
            ],
        );

        $this->end_controls_section();
    }

    protected function add_language_section()
    {
        $this->start_controls_section(
            'language_style_section',
            [
                'label' => __('Language', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'enabled_language' => '1',
                ],
            ]
        );

        $this->add_control(
            'language_list',
            [
                'label' => __('Language List', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'label',
                        'label' => __('Label', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],
                    [
                        'name' => 'url',
                        'label' => __('URL', 'togo-framework'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                    ]
                ],
                'default' => [
                    [
                        'label' => esc_html__('English (EN)', 'togo-framework'),
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_currency_section()
    {
        $this->start_controls_section(
            'currency_style_section',
            [
                'label' => __('Currency', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'enabled_currency' => '1',
                ],
            ]
        );

        $this->add_control(
            'currency_list',
            [
                'label' => __('Currency List', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'label',
                        'label' => __('Label', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],
                    [
                        'name' => 'url',
                        'label' => __('URL', 'togo-framework'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                    ]
                ],
                'default' => [
                    [
                        'label' => 'USD',
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function add_content_not_logged_in_section()
    {
        $this->start_controls_section(
            'content_not_logged_in_section',
            [
                'label' => __('Not Logged In', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'show_profile' => 'yes',
                ]
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
                'condition' => [
                    'show_profile' => 'yes',
                ]
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
                'condition' => [
                    'show_profile' => 'yes',
                ]
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
                'condition' => [
                    'show_profile' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'menu',
            [
                'label' => __('Menu', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
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
                'condition' => [
                    'show_profile' => 'yes',
                ]
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
        echo \Togo\Templates::canvas_menu($settings);
    }
}
