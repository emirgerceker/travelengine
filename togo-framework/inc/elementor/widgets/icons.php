<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Togo_Icons_Widget extends Base
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
        return 'togo-icon';
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
        return __('Icon', 'togo-framework');
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
        return 'eicon-rating';
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
            'icon',
            [
                'label' => __('Icon', 'togo-framework'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'togo-framework'),
                'type' => Controls_Manager::URL,
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
            'color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => __('Hover Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon:hover' => 'color: {{VALUE}};',
                ],
            ],
        );

        $this->add_control(
            'size',
            [
                'label' => __('Size', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-svg-icon svg' => 'width: {{SIZE}}{{UNIT}};',
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
        $icon = $settings['icon'] ? trim($settings['icon']['value']) : '';
        $icon_name = str_replace('togo-svg ', '', $icon);
        $target = $settings['link']['is_external'] ? $settings['link']['target'] : '_self';
        if ($settings['link']) {
            echo '<a href="' . $settings['link']['url'] . '" target="' . $target . '">';
        }
        echo \Togo\Icon::get_svg($icon_name);
        if ($settings['link']) {
            echo '</a>';
        }
    }
}
