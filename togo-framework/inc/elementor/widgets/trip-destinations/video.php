<?php

/**
 * Video widget.
 *
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor\Trip_Destinations;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Class Togo_Video_Widget.
 *
 * A widget for displaying video.
 *
 * @package Togo_Elementor
 */
class Widget_Video extends \Togo_Framework\Elementor\Base
{

    /**
     * Get the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-td-video';
    }

    /**
     * Get the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Trip Destinations - Video', 'togo-framework');
    }

    /**
     * Get the icon for the widget.
     *
     * @return string The icon for the widget.
     */
    public function get_icon_part()
    {
        return 'eicon-video-camera';
    }

    public function get_categories()
    {
        return ['trip-video'];
    }

    /**
     * Register the controls for the widget.
     *
     * @return void
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'video_height',
            [
                'label' => __('Video Height', 'togo-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 600,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .togo-td-video' => 'height: {{VALUE}}px;',
                ]
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Size', 'togo-framework'),
                'type' => Controls_Manager::TEXT,
                'default' => '1600x600',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'togo-framework'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'togo-framework'),
                'label_off' => __('No', 'togo-framework'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_content_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __('Typography', 'togo-framework'),
                'selector' => '{{WRAPPER}} h1',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Color', 'togo-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h1' => 'color: {{VALUE}};',
                ],
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
        if (!is_tax('togo_trip_destinations')) {
            return;
        }

        // Get the current taxonomy term
        $term = get_queried_object();

        $thumbnail = get_term_meta($term->term_id, 'togo_trip_destinations_thumbnail', true) ?: [];
        $thumbnail_id = array_key_exists('id', $thumbnail) ? $thumbnail['id'] : '';
        $image_size = isset($settings['image_size']) ? $settings['image_size'] : '1600x600';
        $image_url = \Togo\Helper::togo_image_resize($thumbnail_id, $image_size);
        $video = get_term_meta($term->term_id, 'togo_trip_destinations_video', true) ?: '';
        $show_title = isset($settings['show_title']) ? $settings['show_title'] : '';

        if ($video) {
            echo '<div class="togo-td-video">';
            echo '<video 
                    src="' . esc_url($video) . '" 
                    autoplay 
                    muted 
                    loop 
                    playsinline 
                  >
                  </video>';
            echo '<div class="overlay-content">';
            if ($show_title == 'yes') {
                echo '<h1>' . esc_html($term->name) . '</h1>';
            }
            echo '</div>';
            echo '</div>';
        } elseif ($image_url) {
            echo '<div class="togo-td-thumbnail">';
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '"/>';
            if ($show_title == 'yes') {
                echo '<h1>' . esc_html($term->name) . '</h1>';
            }
            echo '</div>';
        }
    }
}
