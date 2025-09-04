<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Utils;

defined('ABSPATH') || exit;

class Togo_Modern_Image_Widget extends Base
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
        return 'togo-modern-image';
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
        return __('Modern Image', 'togo-framework');
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
        return 'eicon-image-before-after';
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
            'image',
            [
                'label' => __('Choose Image', 'togo-framework'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
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

        $this->add_responsive_control(
            'height',
            [
                'label' => __('Height', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'line_background_color',
            [
                'label' => __('Line Background Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .togo-modern-image-overlay::before' => 'border-right-color: {{VALUE}};',
                    '{{WRAPPER}} .togo-modern-image-overlay::after' => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .togo-modern-image-overlay span::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .togo-modern-image-overlay span::after' => 'background-color: {{VALUE}};',
                ],
                'default' => '#fff',
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
        $image = $settings['image']['url'] ?? Utils::get_placeholder_image_src();
        $link = $settings['link']['url'] ?? '';
        $link_target = $settings['link']['is_external'] ? $settings['link']['target'] : '_self';
        $link_nofollow = $settings['link']['nofollow'] ? 'nofollow' : '';

        echo '<div class="togo-modern-image">';

        if ($link) {
            echo '<a href="' . esc_url($link) . '" target="' . esc_attr($link_target) . '" rel="' . esc_attr($link_nofollow) . '">';
        }

        echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)) . '" />';

        echo '<div class="togo-modern-image-overlay"><span></span></div>';

        if ($link) {
            echo '</a>';
        }

        echo '</div>';
    }
}
