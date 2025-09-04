<?php

/**
 * Elementor widget for displaying marquee.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Utils;

defined('ABSPATH') || exit;

/**
 * Class Togo_Marquee_Widget
 *
 * Elementor widget for displaying the site logo.
 *
 * @since 1.0.0
 */
class Togo_Marquee_Widget extends Base
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
        return 'togo-marquee';
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
        return __('Marquee', 'togo-framework');
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
        return 'eicon-animation-text';
    }

    /**
     * Get the script dependencies of the widget.
     *
     * @return array
     */
    public function get_script_depends()
    {
        return array('togo-widget-marquee');
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
            'items',
            [
                'label' => __('Items', 'togo-framework'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'image',
                        'label' => __('Image', 'togo-framework'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'label_block' => true,
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Title', 'togo-framework'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Title', 'togo-framework'),
                        'label_block' => true,
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add the content style section controls.
     *
     * @since 1.0.0
     */
    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'togo-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left-to-right' => __('Left to Right', 'togo-framework'),
                    'right-to-left' => __('Right to Left', 'togo-framework'),
                ],
                'default' => 'left-to-right',
            ]
        );

        $this->add_control(
            'item_gap',
            [
                'label' => __('Item Gap', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-marquee-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_gap',
            [
                'label' => __('Icon Gap', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-marquee-item' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __('Height', 'togo-framework'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-marquee' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} .togo-marquee-title',
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'togo-framework'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .togo-marquee-title' => 'color: {{VALUE}};',
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
        $items = $settings['items'];
        $type = !empty($settings['type']) ? $settings['type'] : 'left-to-right';
        $speed = !empty($settings['speed']['size']) ? $settings['speed']['size'] : 2;
        if (empty($items)) {
            return;
        }

        echo '<div class="togo-marquee ' . esc_attr($type) . '" data-type="' . esc_attr($type) . '" data-speed="' . esc_attr($speed) . '">';
        echo '<div class="togo-marquee-inner">';
        foreach ($items as $item) {
            $image = !empty($item['image']['url']) ? '<img src="' . esc_url($item['image']['url']) . '" alt="' . esc_attr($item['title']) . '" class="togo-marquee-image">' : '';
            $title = !empty($item['title']) ? '<span class="togo-marquee-title">' . esc_html($item['title']) . '</span>' : '';
            echo '<div class="togo-marquee-item">&nbsp;' . $image . $title . '</div>';
        }
        echo '</div>';
        echo '</div>';
    }
}
